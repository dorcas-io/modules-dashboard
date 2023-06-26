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
    
    const GETTING_STARTED_CHECKLISTS = [];

    public function __construct(Request $request, Sdk $sdk)
    {
        $this->request = $request;
        $this->sdk = $sdk;
        $this->controller = new Controller();
    }

    public function checkPickupAddress() : bool
    {
        $company = $this->request->user()->company(true, true);

        $locations = $this->controller->getLocations($this->sdk);

        $company_data = (array) $company->extra_data;

        return !empty($locations) && !empty($company_data['location']);
    }

    public function checkOnlinePayment() : bool
    {
        $bank_accounts = $this->controller->getBankAccounts($this->sdk);
        return $bank_accounts->count() > 0;
    }

    public function checkOnlineStore() : bool
    {
        $company = $this->request->user()->company(true, true);
        
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
        $check = $this->controller->getBankAccounts($this->sdk);
        $bank_accounts = !empty($check) ? $check : [];
        return $bank_accounts->count() > 0;
    }

    public function checkProducts() : bool
    {
        $check = $this->controller->getProducts($this->sdk);
        $products = !empty($check) ? $check : [];
        return count($products) > 0;
    }

}