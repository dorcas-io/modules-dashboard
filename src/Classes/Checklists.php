<?php

namespace Dorcas\ModulesDashboard\Classes;

use Illuminate\Http\Request;
use App\Dorcas\Hub\Utilities\UiResponse\UiResponse;
use Carbon\Carbon;
use Hostville\Dorcas\Sdk;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use GuzzleHttp\Exception\ServerException;
use App\Http\Controllers\Controller;
use Dorcas\ModulesEcommerce\Http\Controllers\ModulesEcommerceStoreController;

class Checklists {

    private $request;

    private $sdk;

    private $controller;

    private $company;
    
    const GETTING_STARTED_CHECKLISTS = [];

    public function __construct(Request $request, Sdk $sdk, $company)
    {
        $this->request = $request;
        $this->sdk = $sdk;
        $this->company = $company;
        $this->controller = new Controller();
    }

    public function checkPickupAddress() : bool
    {
        $company = !empty($this->company) ? $this->company : $this->request->user()->company(true, true);

        $locations = $this->controller->getLocations($this->sdk);

        $company_data = (array) $company->extra_data;

        //return !empty($locations) && !empty($company_data['location']);
        return !empty($locations);
    }

    public function checkShippingCosts() : bool
    {
        $company = !empty($this->company) ? $this->company : $this->request->user()->company(true, true);

        $logisticsSettings = ModulesEcommerceStoreController::getLogisticsSettings((array) $company->extra_data);

        $shippingOption = $logisticsSettings['logistics_shipping'];

        $shippingCostsAreOK = false;

        switch ($shippingOption) {

            case "shipping_myself":
                
                $query = $this->sdk->createProductResource()
                ->addQueryArgument('limit', 10000)
                ->addQueryArgument('product_type', 'shipping')
                ->send('GET');
                
                if (!$query->isSuccessful() || empty($query->getData())) {
                    return false;
                }
                
                $routes = collect($query->getData())->map(function ($product) {
                    return (object) $product;
                });
                
                $shippingCostsAreOK = $routes->count() > 0;

                break;

            case "shipping_provider":

                $shippingCostsAreOK = true;

                break;

        }

        return $shippingCostsAreOK;
    }

    public function checkOnlineStore() : bool
    {
        $company = !empty($this->company) ? $this->company : $this->request->user()->company(true, true);
        
        $storeSettings = ModulesEcommerceStoreController::getStoreSettings((array) $company->extra_data);
        $logisticsSettings = ModulesEcommerceStoreController::getLogisticsSettings((array) $company->extra_data);
        $paymentSettings = ModulesEcommerceStoreController::getPaymentsSettings((array) $company->extra_data);
        
        $storeSettingsFilled = collect($storeSettings);
        $logisticsSettingsFilled = collect($logisticsSettings);
        $paymentSettingsFilled = collect($paymentSettings);

        $allFilled = collect([$storeSettingsFilled, $logisticsSettingsFilled, $paymentSettingsFilled]);

        $hasAllNonEmptyCollections = $allFilled->every(function ($collection) {
            return $collection->filter(function ($value) {
                return !empty($value);
            })->isNotEmpty();
        });

        return $hasAllNonEmptyCollections;
    }

    public function checkBankAccounts() : bool
    {

        if (empty(auth()->user())) {
            $companyUsers = $this->company->users;
            $bank_accounts = $companyUsers["data"][0]["bank_accounts"];
        } else {
            $check = $this->controller->getBankAccounts($this->sdk);
            $bank_accounts = !empty($check) ? $check : [];
        }

        



        return count($bank_accounts) > 0;
    }

    public function checkProducts() : bool
    {
        $check = $this->controller->getProducts($this->sdk);
        $products = !empty($check) ? $check : [];
        return count($products) > 0;
    }

}