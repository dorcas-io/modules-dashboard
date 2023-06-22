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

    public function checkOnlinePayment() : array
    {
        $bank_accounts = $this->controller->getBankAccounts($this->sdk);
        return $bank_accounts->count() > 0;
    }

    public function checkBankAccounts() : array
    {
        $bank_accounts = $this->controller->getBankAccounts($this->sdk);
        return $bank_accounts->count() > 0;
    }

    public function checkProducts() : bool
    {
        $products = $this->controller->getProducts($this->sdk);
        return count($products) > 0;
    }

}