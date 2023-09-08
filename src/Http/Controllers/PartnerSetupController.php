<?php

namespace Dorcas\ModulesDashboard\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Dorcas\ModulesDashboardController\Models\ModulesDashboard;
use App\Dorcas\Hub\Utilities\UiResponse\UiResponse;
use Carbon\Carbon;
use App\Http\Controllers\HomeController;
use Hostville\Dorcas\Sdk;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Exception\ServerException;
use Dorcas\ModulesAssistant\Http\Controllers\ModulesAssistantController as Assistant;
use App\Http\Controllers\HubController as HubControl;
use Dorcas\ModulesDashboard\Classes\Checklists;

class PartnerSetupController extends Controller {

    public function __construct()
    {
        $this->middleware( 'auth');
        parent::__construct();

    }


    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function createPartnerECommerceWallets($usePreset = true)
    {
        $response_status = false;
        $response_message = "";
        $response_data = [];

        // Only Process for Multi-tenant for now
        if (env("DORCAS_EDITION", "business") != "enterprise") {

            $response_message = "Wallet Creation only for Enterprise Edition";

            $response = [
                "status" => $response_status,
                "message" => $response_message,
                "data" => $response_data,
            ];
    
            return response()->json($response);

        }


        $proceedWithUpdate = false;

        $company = DB::connection('core_mysql')->table("companies")->where('id', 1)->first();
        $company_data = (array) json_decode($company->extra_data);
        $user = DB::connection('core_mysql')->table("users")->where('id', 1)->first();

        $presetWallets = [
            "sales_sme" => "PSA297DAACB532001991", //8543402980
            "sales_vas" => "PSA549FC2545D2028417", //8543403000
            "logistics" => "PSA5386027D832052142", //8543403010
            "partner" => "PSA0A054850A12072194", //8543403020
            "dorcas" => "PSA6CCD89E9AA2085918" //8543403030
        ];

        $readyWalletData = [];

        if ($usePreset) {

            $collection = collect($presetWallets);

            $foundEmpty = false;
            
            $collection->each(function ($item) use (&$foundEmpty) {
                if (empty($item)) {
                    $foundEmpty = true;
                    return false;
                }
            });
            
            if ($foundEmpty) {
                $response_message = "Invalid Preset Wallet Data";
            } else {
                $readyWalletData = $presetWallets;
                $proceedWithUpdate = true;
            }

        } else {

            //check current details before attempting to create with provider

            if (!isset($company_data["global_partner_settings"]) || empty($company_data["global_partner_settings"]) ) {
                
                //proceed with creation

                $provider = env('SETTINGS_ECOMMERCE_PAYMENT_PROVIDER', 'flutterwave');
                $country = env('SETTINGS_COUNTRY', 'NG');
                $provider_config = ucfirst($provider). strtoupper($country) . '.php';
                $provider_class = ucfirst($provider). strtoupper($country) . 'Class.php';
                $provider_config_path = base_path('vendor/dorcas/modules-ecommerce/src/Config/Providers/Payments/' . ucfirst($provider). '/' . $provider_config);
                $config = require_once($provider_config_path);
                $provider_class_path = base_path('vendor/dorcas/modules-ecommerce/src/Config/Providers/Payments/' . ucfirst($provider). '/' . $provider_class);
                require_once($provider_class_path);


                $walletsData = [
                    "sales_sme" => [
                        "account_name" => $company->name . " SME Sales Wallet",
                        "email" => "smesales-" . $company->uuid . "@dorcas.io",
                    ],
                    "sales_vas" => [
                        "account_name" => $company->name . " VAS Sales Wallet",
                        "email" => "vassales-" . $company->uuid . "@dorcas.io",
                    ],
                    "logistics" => [
                        "account_name" => $company->name . " Logistics Wallet",
                        "email" => "logistics-" . $company->uuid . "@dorcas.io",
                    ],
                    "partner" => [
                        "account_name" => $company->name . " Partner Wallet",
                        "email" => "partner-" . $company->uuid . "@dorcas.io",
                    ],
                    "dorcas" => [
                        "account_name" => $company->name . " Dorcas Wallet",
                        "email" => "dorcas-" . $company->uuid . "@dorcas.io",
                    ]
                ];

                $walletLoopStatus = false;
                $walletLoopIssue = "";
                $walletLoopData = [
                    "sales_sme" => "",
                    "sales_vas" => "",
                    "logistics" => "",
                    "partner" => "",
                    "dorcas" => ""
                ];

                //dd([$company_data, $walletsData]);

                foreach ($walletsData as $elementK => $elementV) {

                    $providerParams = [
                        "account_name" => $elementV["account_name"],
                        "email" => $elementV["email"],
                        "mobilenumber" => $this->format_intl_code($user->phone, "234"),
                        "country" => $country
                    ];

                    $c = $config["class"];

                    $provider = new $c($providerParams);
    
                    $wallet_creation = $provider->createSubAccount('payout', $providerParams);
                
                    // Check if the function was successful (you can customize this condition)
                    if ($wallet_creation->status !== "success") {
                        $walletLoopStatus = false;
                        $walletLoopIssue = $wallet_creation->message;
                        break;
                    }
                    $walletLoopStatus = true;
                    $walletLoopData[$elementK] = $wallet_creation->data->account_reference;
                    sleep(1);
                }

                if ($walletLoopStatus) {
                    $readyWalletData = $walletLoopData;
                    $proceedWithUpdate = true;
                } else {
                    $response_message = "Error Creating Wallets: " . $wallet_creation->message;
                }

            } else {
                if (isset($company_data["global_partner_settings"]["ecommerce"]) && !empty($company_data["global_partner_settings"]["ecommerce"]) ) {
                    $response_message = "Preset Wallet Data Exists";
                }
            }


        }

        if ($proceedWithUpdate) {

            // ensure ecommerce data
            if (!isset($company_data["global_partner_settings"]) || empty($company_data["global_partner_settings"]) ) {
                $company_data["global_partner_settings"] = [
                    "ecommerce" => [
                        "transaction_fees" => [
                            "total" => 10,
                            "partner" => 2.5,
                            "dorcas" => 7.5
                        ],
                        "subaccounts" => [
                            "sales_sme" => "",
                            "sales_vas" => "",
                            "logistics" => "",
                            "partner" => "",
                            "dorcas" => ""
                        ]
                    ]
                ];
            }

            $company_data["global_partner_settings"]["ecommerce"]["subaccounts"] = $readyWalletData;

            $update = DB::connection('core_mysql')->table('companies')
            ->where('id', '=', 1)
            ->update([
                'extra_data' => json_encode($company_data),
            ]);

            if ($update) {
                $response_status = true;
                $response_message = "eCommerce Wallets Creation Successful";
                $response_data = $readyWalletData;
            }


        }
        

        $response = [
            "status" => $response_status,
            "message" => $response_message,
            "data" => $response_data,
        ];

        return response()->json($response);
    }


    /**
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function createPartnerVASProducts($usePreset = true)
    {
        $response_status = false;
        $response_message = "";
        $response_data = [];

        // Only Process for Multi-tenant for now
        if (env("DORCAS_EDITION", "business") != "enterprise") {

            $response_message = "VAS Products Creation only for Enterprise Edition";

            $response = [
                "status" => $response_status,
                "message" => $response_message,
                "data" => $response_data,
            ];
    
            return response()->json($response);

        }


        $proceedWithUpdate = false;

        $company = DB::connection('core_mysql')->table("companies")->where('id', 1)->first();
        $company_data = (array) json_decode($company->extra_data);
        $user = DB::connection('core_mysql')->table("users")->where('id', 1)->first();

        $presetWallets = [
            "sales_sme" => "PSA297DAACB532001991", //8543402980
            "sales_vas" => "PSA549FC2545D2028417", //8543403000
            "logistics" => "PSA5386027D832052142", //8543403010
            "partner" => "PSA0A054850A12072194", //8543403020
            "dorcas" => "PSA6CCD89E9AA2085918" //8543403030
        ];

        $readyWalletData = [];

        if ($usePreset) {

            $collection = collect($presetWallets);

            $foundEmpty = false;
            
            $collection->each(function ($item) use (&$foundEmpty) {
                if (empty($item)) {
                    $foundEmpty = true;
                    return false;
                }
            });
            
            if ($foundEmpty) {
                $response_message = "Invalid Preset Wallet Data";
            } else {
                $readyWalletData = $presetWallets;
                $proceedWithUpdate = true;
            }

        } else {

            //check current details before attempting to create with provider

            if (!isset($company_data["global_partner_settings"]) || empty($company_data["global_partner_settings"]) ) {
                
                //proceed with creation

                $provider = env('SETTINGS_ECOMMERCE_PAYMENT_PROVIDER', 'flutterwave');
                $country = env('SETTINGS_COUNTRY', 'NG');
                $provider_config = ucfirst($provider). strtoupper($country) . '.php';
                $provider_class = ucfirst($provider). strtoupper($country) . 'Class.php';
                $provider_config_path = base_path('vendor/dorcas/modules-ecommerce/src/Config/Providers/Payments/' . ucfirst($provider). '/' . $provider_config);
                $config = require_once($provider_config_path);
                $provider_class_path = base_path('vendor/dorcas/modules-ecommerce/src/Config/Providers/Payments/' . ucfirst($provider). '/' . $provider_class);
                require_once($provider_class_path);


                $walletsData = [
                    "sales_sme" => [
                        "account_name" => $company->name . " SME Sales Wallet",
                        "email" => "smesales-" . $company->uuid . "@dorcas.io",
                    ],
                    "sales_vas" => [
                        "account_name" => $company->name . " VAS Sales Wallet",
                        "email" => "vassales-" . $company->uuid . "@dorcas.io",
                    ],
                    "logistics" => [
                        "account_name" => $company->name . " Logistics Wallet",
                        "email" => "logistics-" . $company->uuid . "@dorcas.io",
                    ],
                    "partner" => [
                        "account_name" => $company->name . " Partner Wallet",
                        "email" => "partner-" . $company->uuid . "@dorcas.io",
                    ],
                    "dorcas" => [
                        "account_name" => $company->name . " Dorcas Wallet",
                        "email" => "dorcas-" . $company->uuid . "@dorcas.io",
                    ]
                ];

                $walletLoopStatus = false;
                $walletLoopIssue = "";
                $walletLoopData = [
                    "sales_sme" => "",
                    "sales_vas" => "",
                    "logistics" => "",
                    "partner" => "",
                    "dorcas" => ""
                ];

                //dd([$company_data, $walletsData]);

                foreach ($walletsData as $elementK => $elementV) {

                    $providerParams = [
                        "account_name" => $elementV["account_name"],
                        "email" => $elementV["email"],
                        "mobilenumber" => $this->format_intl_code($user->phone, "234"),
                        "country" => $country
                    ];

                    $c = $config["class"];

                    $provider = new $c($providerParams);
    
                    $wallet_creation = $provider->createSubAccount('payout', $providerParams);
                
                    // Check if the function was successful (you can customize this condition)
                    if ($wallet_creation->status !== "success") {
                        $walletLoopStatus = false;
                        $walletLoopIssue = $wallet_creation->message;
                        break;
                    }
                    $walletLoopStatus = true;
                    $walletLoopData[$elementK] = $wallet_creation->data->account_reference;
                    sleep(1);
                }

                if ($walletLoopStatus) {
                    $readyWalletData = $walletLoopData;
                    $proceedWithUpdate = true;
                } else {
                    $response_message = "Error Creating Wallets: " . $wallet_creation->message;
                }

            } else {
                if (isset($company_data["global_partner_settings"]["ecommerce"]) && !empty($company_data["global_partner_settings"]["ecommerce"]) ) {
                    $response_message = "Preset Wallet Data Exists";
                }
            }


        }

        if ($proceedWithUpdate) {

            // ensure ecommerce data
            if (!isset($company_data["global_partner_settings"]) || empty($company_data["global_partner_settings"]) ) {
                $company_data["global_partner_settings"] = [
                    "ecommerce" => [
                        "transaction_fees" => [
                            "total" => 10,
                            "partner" => 2.5,
                            "dorcas" => 7.5
                        ],
                        "subaccounts" => [
                            "sales_sme" => "",
                            "sales_vas" => "",
                            "logistics" => "",
                            "partner" => "",
                            "dorcas" => ""
                        ]
                    ]
                ];
            }

            $company_data["global_partner_settings"]["ecommerce"]["subaccounts"] = $readyWalletData;

            $update = DB::connection('core_mysql')->table('companies')
            ->where('id', '=', 1)
            ->update([
                'extra_data' => json_encode($company_data),
            ]);

            if ($update) {
                $response_status = true;
                $response_message = "eCommerce Wallets Creation Successful";
                $response_data = $readyWalletData;
            }


        }
        

        $response = [
            "status" => $response_status,
            "message" => $response_message,
            "data" => $response_data,
        ];

        return response()->json($response);
    }


}