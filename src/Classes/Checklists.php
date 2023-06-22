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
    
    const GETTING_STARTED_CHECKLISTS = [];

    public function __construct(Request $request, Sdk $sdk)
    {
        $this->request = $request;
        $this->sdk = $sdk;
    }

    public function checkPickupAddress() : bool
    {
        $company = $this->request->user()->company(true, true);

        $locations = Controller::getLocations($this->sdk);

        $company_data = (array) $company->extra_data;

        return !empty($locations) && !empty($company_data['location']);
    }

    public function checkOnlinePayment() : array
    {
        $bank_accounts = Controller::getBankAccounts($sdk);
        return $bank_accounts->count() > 0;
    }

    public function checkBankAccounts() : array
    {
        $bank_accounts = Controller::getBankAccounts($sdk);
        return $bank_accounts->count() > 0;
    }

    public function checkProducts(Sdk $sdk) : bool
    {
        $products = Controller::getProducts();
        return count($products) > 0;
    }

}