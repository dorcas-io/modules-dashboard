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
use GuzzleHttp\Exception\ServerException;
use Dorcas\ModulesAssistant\Http\Controllers\ModulesAssistantController as Assistant;
use App\Http\Controllers\HubController as HubControl;

class ModulesDashboardController extends Controller {

    const SUMMARY_ICONS = [
        'cash' => ['icon' => 'fa fa-money', 'bg' => 'bg-green'],
        'custom_fields' => ['icon' => 'import_contacts', 'bg' => 'bg-green'],
        'customers' => ['icon' => 'fa fa-group', 'bg' => 'bg-blue'],
        'departments' => ['icon' => 'fe fe-grid', 'bg' => 'bg-purple'],
        'employees' => ['icon' => 'fa fa-briefcase', 'bg' => 'bg-purple'],
        'groups' => ['icon' => 'group', 'bg' => 'bg-green'],
        'locations' => ['icon' => 'room', 'bg' => 'bg-green'],
        'orders' => ['icon' => 'fa fa-bar-chart', 'bg' => 'bg-red'],
        'products' => ['icon' => 'whatshot', 'bg' => 'bg-green'],
        'services' => ['icon' => 'domain', 'bg' => 'bg-green'],
        'teams' => ['icon' => 'group', 'bg' => 'bg-green']
    ];
    
    /** @var array  */
    /*const SETUP_UI_COMPONENTS = [
        ['name' => 'App Store', 'id' => 'appstore', 'enabled' => true, 'is_readonly' => false, 'path' => 'app-store'],
        ['name' => 'Customers', 'id' => 'customers', 'enabled' => true, 'is_readonly' => false, 'path' => 'apps/crm'],
        ['name' => 'eCommerce', 'id' => 'ecommerce', 'enabled' => true, 'is_readonly' => false, 'path' => 'apps/ecommerce'],
        ['name' => 'Finance', 'id' => 'finance', 'enabled' => true, 'is_readonly' => false, 'path' => 'apps/finance'],
        ['name' => 'Integrations', 'id' => 'integrations', 'enabled' => true, 'is_readonly' => false, 'path' => 'integrations'],
        ['name' => 'People', 'id' => 'organisation', 'enabled' => true, 'is_readonly' => false, 'path' => 'apps/people'],
        ['name' => 'Sales', 'id' => 'sales', 'enabled' => true, 'is_readonly' => false, 'path' => ['apps/inventory', 'apps/invoicing']],
        ['name' => 'Settings', 'id' => 'settings', 'enabled' => true, 'is_readonly' => false, 'path' => 'settings'],
        ['name' => 'Professional Services', 'id' => 'services', 'enabled' => true, 'is_readonly' => true],
        ['name' => 'Product Vendors', 'id' => 'vendors', 'enabled' => true, 'is_readonly' => true],
    ];*/

    const SETUP_UI_COMPONENTS = [
        ['name' => 'Dashboard', 'base' => true, 'id' => 'dashboard', 'enabled' => true, 'is_readonly' => true, 'path' => 'dashboard', 'children' => []],
        ['name' => 'Customers', 'base' => true, 'id' => 'customers', 'enabled' => true, 'is_readonly' => false, 'path' => 'mcu', 'children' => []],
        ['name' => 'eCommerce', 'base' => false, 'id' => 'ecommerce', 'enabled' => true, 'is_readonly' => false, 'path' => 'mec', 'children' => []],
        ['name' => 'People', 'base' => false, 'id' => 'people', 'enabled' => true, 'is_readonly' => false, 'path' => 'mpe', 'children' => []],
        ['name' => 'Finance', 'base' => false, 'id' => 'finance', 'enabled' => true, 'is_readonly' => false, 'path' => 'mfn', 'children' => []],
        ['name' => 'Sales', 'base' => false, 'id' => 'sales', 'enabled' => true, 'is_readonly' => false, 'path' => 'msl', 'children' => []],
        ['name' => 'Operations', 'base' => true, 'id' => 'operations', 'enabled' => true, 'is_readonly' => false, 'path' => 'mop', 'children' => []],
        //['name' => 'Addons', 'id' => 'addons', 'enabled' => true, 'is_readonly' => false, 'path' => ['mda', 'mmp', 'map', 'mit'], 'children' => []],
        ['name' => 'Addons', 'base' => true, 'id' => 'addons', 'enabled' => true, 'is_readonly' => true, 'path' => ['mda', 'mit'], 'children' => []],
        ['name' => 'Settings', 'base' => true, 'id' => 'settings', 'enabled' => true, 'is_readonly' => true, 'path' => 'mse', 'children' => []],
        ['name' => 'Services', 'base' => true, 'id' => 'services', 'enabled' => true, 'is_readonly' => true, 'path' => ['mps', 'mpp', 'map'], 'children' => []],
        ['name' => 'Analytics', 'base' => true, 'id' => 'analytics', 'enabled' => true, 'is_readonly' => true, 'path' => ['man'], 'children' => []],
        ['name' => 'Vendors', 'base' => true, 'id' => 'vendors', 'enabled' => true, 'is_readonly' => true, 'path' => 'mvd', 'children' => []],
    ];

    public function __construct()
    {
        $this->middleware( 'auth');
        parent::__construct();
        $this->data = [
            'page' => ['title' => config('modules-dashboard.title')],
            'header' => ['title' => config('modules-dashboard.title')],
            'selectedMenu' => 'modules-dashboard'
        ];
    }

    public function business(Request $request, Sdk $sdk)
    {
        $this->setViewUiResponse($request);
        $company = $request->user()->company(true, true);
        # get the company
        $this->data['message'] = $request->query('message');
        # a message in the URL
        $viewMode = $request->session()->get('viewMode', null);
        # get the current view mode
        $userConfigurations = (array) $request->user()->extra_configurations;
        $userUiSetup = $userConfigurations['ui_setup'] ?? [];
        $configurations = (array) $company->extra_data;
        $this->data['isConfigured'] = true;
        if (empty($userUiSetup)) {
            # user's UI is not configured
            //$this->data['isFirstConfiguration'] = empty($configurations['ui_setup']);
            $this->data['isFirstConfiguration'] = isset($configurations['ui_setup']["customers"]);
            # use missing base customers modules as a check of first time
            if ($request->has('show_ui_wizard')) {
                $this->data['isConfigured'] = false;
            } else {
                $this->data['isConfigured'] = !$this->data['isFirstConfiguration'];
            }
            # check if the UI has been configured
            $currentUiSetup = $configurations['ui_setup'] ?? [];
            $this->data['setupUiFields'] = collect(self::SETUP_UI_COMPONENTS)->map(function ($field) use ($currentUiSetup) {
                if (!empty($field['is_readonly'])) {
                    return $field;
                }
                if (empty($currentUiSetup)) {
                    return $field;
                }
                $field['enabled'] = in_array($field['id'], $currentUiSetup);
                return $field;
            });
            # add the UI components
        }
        $this->data['countries'] = $countries = $this->getCountries($sdk);
        # get the countries listing
        $nigeria = !empty($countries) && $countries->count() > 0 ? $countries->where('iso_code', 'NG')->first() : null;
        # get the nigeria country model
        if (!empty($nigeria)) {
            $this->data['states'] = $this->getDorcasStates($sdk, $nigeria->id);
            # get the states
        }


        $dorcasUser = $request->user();
        if (!empty($dorcasUser)) {
            if (!empty($dorcasUser->partner) && !empty($dorcasUser->partner['data'])) {
                $partner = (object) $dorcasUser->partner['data'];
                $partnerConfig = (array) $partner->extra_data;
                $hubConfig = $partnerConfig['hubConfig'] ?? [];
            }
        }

        if (!$this->data['isConfigured']) {
            return redirect(route('welcome-setup'));
        }
        # first time users

        $daysAgo = Carbon::now()->subDays(config('hub.dashboard.graph.days_ago'));

        if (!empty($viewMode) && ($viewMode === 'professional' || $viewMode === 'vendor')) {
            
            $this->data['professionalProfile'] = $profile = $this->getProfessionalProfile($sdk);
            $this->data['summary'] = [
                'credentials' => [
                    'icon' => 'fa fa-list-alt',
                    'number' => !empty($profile->professional_credentials) ? count($profile->professional_credentials['data']) : 0,
                    'bg' => 'bg-purple'
                ],
                'experience' => [
                    'icon' => 'fa fa-building',
                    'number' => !empty($profile->professional_experiences) ? count($profile->professional_experiences['data']) : 0,
                    'bg' => 'bg-red'
                ],
                'services' => [
                    'icon' => 'fa fa-briefcase',
                    'number' => !empty($profile->professional_services) ? count($profile->professional_services['data']) : 0,
                    'bg' => 'bg-blue'
                ],
            ];
            
            $metricsData = Cache::remember('company.metrics.directory.'.$viewMode.'.'.$request->user()->id, 30, function () use ($sdk, $daysAgo, $viewMode) {
                $metrics = $sdk->createMetricsService();
                $metricQuery = [
                    'resource' => 'professional',
                    'metrics' => ['requests_count', 'requests_pending', 'requests_accepted', 'requests_rejected']
                ];
                $metricsData = $metrics->addBodyParam('metrics', [$metricQuery])
                                        ->addBodyParam('from_date', $daysAgo->format('Y-m-d'))
                                        ->send('POST');
                # send a post request to get the data
                if (!$metricsData->isSuccessful()) {
                    return null;
                }
                return $metricsData->getData(true);
            });
            $this->data['daysAgo'] = 30;
            $this->data['requestGraph'] = $graph = $this->processRequestsGraphData($metricsData->professional);
            # we get the graph data

            //re-parse for c3
            $professionalGraphColumns = [];
            $professionalGraphColors = new \stdClass();
            $professionalGraphNames = new \stdClass();
            $professionalGraphAxes = new \stdClass();
            $professionalGraphCategories = [];

            $professionalGraphOld = array_slice($this->data['requestGraph'], 14); //reduce to 2 weeks
            //the fields we are plotting for
            $professionalGraphSeries = [
                "count" => ["title" => "Total", "color" => "#467fcf", "axes" => "y"],
                "accepted" => ["title" => "Accepted", "color" => "#5eba00", "axes" => "y"],
                "pending" => ["title" => "Pending", "color" => "#a55eea", "axes" => "y"],
                "rejected" => ["title" => "Rejected", "color" => "#cd201f", "axes" => "y"]
            ];

            foreach ($professionalGraphSeries as $skey => $svalue) {
                $column = [$skey];
                $professionalGraphColors->{$skey} = $svalue["color"];
                $professionalGraphNames->{$skey} = $svalue["title"];
                $professionalGraphAxes->{$skey} = $svalue["axes"];
                foreach ($professionalGraphOld as $okey => $ovalue) {
                    $column[] = $ovalue[$skey];
                }
                $professionalGraphColumns[] = $column;
            }

            foreach ($professionalGraphOld as $dkey => $dvalue) {
                $date = explode(" ", $dvalue["date"]);
                $professionalGraphCategories[] = $this->ordinal((int) $date[0]);
            }

            $this->data['requestGraph'] = [
                "columns" => $professionalGraphColumns,
                "colors" => $professionalGraphColors,
                "names" => $professionalGraphNames,
                "axes" => $professionalGraphAxes,
                "categories" => $professionalGraphCategories
            ];

            $template = 'modules-dashboard::professional';
            $this->data['page']['title'] = 'Professional Dashboard';
            $this->data['header']['title'] = 'Professional Dashboard';
            
        } else {
            # default view mode
            $metricsData = Cache::remember('company.metrics.'.$company->id, 30, function () use ($sdk, $daysAgo) {
                $metrics = $sdk->createMetricsService();
                $metricsData = $metrics->addBodyParam('metrics', [
                        ['resource' => 'products', 'metrics' => ['sales_total']]
                    ])
                    ->addBodyParam('from_date', $daysAgo->format('Y-m-d'))
                    ->send('POST');
                # send a post request to get the data
                if (!$metricsData->isSuccessful()) {
                    return null;
                }
                return $metricsData->getData(true);
            });
            $this->data['daysAgo'] = 30;
            $salesData = !empty($metricsData->products) ? ($metricsData->products['sales_total'] ?? []) : [];
            $this->data['salesGraph'] = $graph = $this->processGraphData($salesData);
            # we get the graph data

            //re-parse for c3
            $salesGraphColumns = [];
            $salesGraphColors = new \stdClass();
            $salesGraphNames = new \stdClass();
            $salesGraphAxes = new \stdClass();
            $salesGraphCategories = [];

            $salesGraphOld = array_slice($this->data['salesGraph'], 14); //reduce to 2 weeks
            //the fields we are plotting for
            $salesGraphSeries = [
                "count" => ["title" => "Orders", "color" => "#467fcf", "axes" => "y"],
                "total" => ["title" => "Sales", "color" => "#5eba00", "axes" => "y2"]
            ];

            foreach ($salesGraphSeries as $skey => $svalue) {
                $column = [$skey];
                $salesGraphColors->{$skey} = $svalue["color"];
                $salesGraphNames->{$skey} = $svalue["title"];
                $salesGraphAxes->{$skey} = $svalue["axes"];
                foreach ($salesGraphOld as $okey => $ovalue) {
                    $column[] = $ovalue[$skey];
                }
                $salesGraphColumns[] = $column;
            }

            foreach ($salesGraphOld as $dkey => $dvalue) {
                $date = explode(" ", $dvalue["date"]);
                $salesGraphCategories[] = $this->ordinal((int) $date[0]);
            }

            $this->data['salesGraph'] = [
                "columns" => $salesGraphColumns,
                "colors" => $salesGraphColors,
                "names" => $salesGraphNames,
                "axes" => $salesGraphAxes,
                "categories" => $salesGraphCategories
            ];


            $response = $sdk->createCompanyService()->send('GET', ['status']);
            # get the company status
            $this->data['summary'] = self::prepareSummary(
                $response->getData()['counts'] ?? [],
                ['employees', 'customers', 'orders']
            ); //, 'cash'
            $template = 'modules-dashboard::business';
            $this->data['page']['title'] = 'Business Dashboard';
            $this->data['header']['title'] = 'Business Dashboard';
        }

        $expiry = Carbon::parse($company->access_expires_at);
        # get the expiry
        if ($expiry->lessThanOrEqualTo(Carbon::now()) && empty($company->extra_data['paystack_auth_code'])) {
            # subscription expiry in effect, and there is no automatic authorization code for charging; we need one now
            $plan = $company->plan['data'];
            # get the plan
            if (empty($plan)) {
                throw new \RuntimeException('Something went wrong, and we could not determine your pricing plan.');
            }
            $this->data['plan']['price'] = $plan['price_' . $company->plan_type]['raw'];
        }
        
        $this->data['authToken'] = $sdk->getAuthorizationToken();
        return view($template, $this->data);
    }
    
    /**
     * @param Request $request
     * @param Sdk     $sdk
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function welcome_post(Request $request, Sdk $sdk)
    {
        //dd($request);
        $this->validate($request, [
            'business_name' => 'required|string|max:80',
            'business_type' => 'required|string|max:80',
            'business_sector' => 'required|string|max:80',
            'business_size' => 'required|string|max:80',
            'business_country' => 'required|string|max:80',
            'business_state' => 'nullable|string|max:80',
            'currency' => 'nullable|string|size:3'
        ]);
        // additional validation entry => 'selected_apps.*' => 'string'
        # validate the request
        $company = $this->getCompany();
        # get the company
        $configurations = (array) $company->extra_data;
        $this->data['isConfigured'] = !empty($configurations['ui_setup']);
        
        
        $readonlyExtend = collect(self::SETUP_UI_COMPONENTS)->filter(function ($field) {
            return !empty($field['is_readonly']) && !empty($field['enabled']);
        })->pluck('id');
        # get the enabled-readonly values
        
        $readonlyRemovals = collect(self::SETUP_UI_COMPONENTS)->filter(function ($field) {
            return !empty($field['is_readonly']) && empty($field['enabled']);
        })->pluck('id');
        # get the disabled-readonly values
        
        $selectedApps = collect($request->input('selected_apps', []))->merge($readonlyExtend);
        # set the selected apps
        
        $selectedApps = $selectedApps->filter(function ($id) use ($readonlyRemovals) {
            return !$readonlyRemovals->contains($id);
        });
        # remove them
        
        try {
            $configurations['business_type'] = $request->input('business_type');
            $configurations['business_size'] = $request->input('business_size');
            $configurations['business_sector'] = $request->input('business_sector');
            
            $configurations['country_id'] = $request->input('business_country');
            $configurations['state_id'] = $request->input('business_state');
            $configurations['currency'] = strtoupper($request->input('currency', 'NGN'));
            $configurations['ui_setup'] = $selectedApps->unique()->all();
            
            $query = $sdk->createCompanyService()->addBodyParam('name', $request->business_name, true)
                                                ->addBodyParam('extra_data', $configurations)
                                                ->send('PUT');


            //Cache::forget('business.employees.'.$company->id);                                     

                                                //dd($query);
            # send the request
            if (!$query->isSuccessful()) {
                throw new \RuntimeException('Failed while updating your business information. Please try again.');
            }
            //$message = ['Successfully updated business information for '.$request->name];
            //$response = (tabler_ui_html_response([$message]))->setType(UiResponse::TYPE_SUCCESS);
        } catch (ServerException $e) {
            //$message = json_decode((string) $e->getResponse()->getBody(), true);
            //$response = (tabler_ui_html_response([$message['message']]))->setType(UiResponse::TYPE_ERROR);
            throw new \RuntimeException($message['message']);
        } catch (\Exception $e) {
            //$response = (tabler_ui_html_response([$e->getMessage()]))->setType(UiResponse::TYPE_ERROR);
            //return redirect(url()->current())->with('UiResponse', $response);
            throw new \RuntimeException($e->getMessage());
        }
        //return redirect(url()->current())->with('UiResponse', $response);
        return response()->json($query->getData());

    }


    /**
     * @param Request $request
     * @param Sdk     $sdk
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function welcome_features(Request $request, Sdk $sdk)
    {
        //dd($request);
        /*$this->validate($request, [
            'business_name' => 'required|string|max:80',
            'business_type' => 'required|string|max:80',
            'business_sector' => 'required|string|max:80',
            'business_size' => 'required|string|max:80',
            'business_country' => 'required|string|max:80',
            'business_state' => 'nullable|string|max:80',
            'currency' => 'nullable|string|size:3'
        ]);*/
        // additional validation entry => 'selected_apps.*' => 'string'
        # validate the request
        $company = $this->getCompany();
        # get the company
        $configurations = (array) $company->extra_data;
        $this->data['isConfigured'] = !empty($configurations['ui_setup']);
        
        
        $baseFeatures = collect(self::SETUP_UI_COMPONENTS)->filter(function ($field) {
            return !empty($field['base']);
        })->pluck('id');
        # get the base values
        
        
        $readonlyExtend = collect(self::SETUP_UI_COMPONENTS)->filter(function ($field) {
            return !empty($field['is_readonly']) && !empty($field['enabled']);
        })->pluck('id');
        # get the enabled-readonly values
        
        $readonlyRemovals = collect(self::SETUP_UI_COMPONENTS)->filter(function ($field) {
            return !empty($field['is_readonly']) && empty($field['enabled']);
        })->pluck('id');
        # get the disabled-readonly values
        
        //$selectedApps = collect($request->input('selected_apps', []))->merge($readonlyExtend);
        $selectedApps = collect($request->input('selected_apps', []))->merge($readonlyExtend)->merge($baseFeatures);
        # set the selected apps
        
        $selectedApps = $selectedApps->filter(function ($id) use ($readonlyRemovals) {
            return !$readonlyRemovals->contains($id);
        });
        # remove them
        
        try {
            $configurations['ui_setup'] = $selectedApps->unique()->all();

            $partnerSubscriptionMeta = HubControl::getPartnerSubscriptionMeta($request, $sdk);

            $partnerSubscriptionValue = $partnerSubscriptionMeta["partnerSubscriptionValue"];

            if ($partnerSubscriptionValue["plan"]=="starter")  { //starting sub
                $starterSubscription = true;
                $subscriptionPackagesCurrent = collect($partnerSubscriptionMeta["partnerSubscriptionModulesCurrent"])->first();
            } else {
                $starterSubscription = false;
                $subscriptionPackagesCurrent = collect($partnerSubscriptionMeta["partnerSubscriptionModulesCurrent"])->all();
            }
            $this->data['starterSubscription'] = $starterSubscription;

            $subscriptionPackagesUpgrade = collect($partnerSubscriptionMeta["partnerSubscriptionModulesUpgrade"])->all();

            $partnerSubscriptionModules = collect($partnerSubscriptionMeta["partnerSubscriptionModules"]);

            //update subscription memory data
            $memory_config = $configurations['memory_config'] ?? [];
            $subscription = $memory_config["subscription"] ?? [];

            foreach ($selectedApps->unique()->all() as $app) {
                //$sub = $subscription[$app] ?? [];
                $sub = [];
                if (empty($sub)) {
                    $sub["plan"] = $partnerSubscriptionValue["plan"];
                    $sub["title"] = $partnerSubscriptionValue["title"];
                    $subscription_id = $subscriptionPackagesCurrent["subscription_id"];
                    if ($starterSubscription) {
                        $sub["subscription_title"] = $subscriptionPackagesCurrent["title"];
                        $sub["subscription_slug"] = $subscriptionPackagesCurrent["slug"];
                        $sub["subscription"] = $partnerSubscriptionMeta["partnerSubscriptions"][$subscription_id];
                        $sub["subscriptions"] = $partnerSubscriptionModules->filter(function ($value, $key) use ($app) {
                            return in_array($app, $value["modules"]);
                        })->pluck('slug')->all();
                        $sub["mode"] = "Active";
                        $sub["expires"] = Carbon::now()->addDays($subscriptionPackagesCurrent["trial_days"])->format('Y-m-d H:i:s');       
                    } else {

                    }

                }
                $subscription[$app] = $sub;
            }
            
            $memory_config['subscription'] = $subscription;
            $configurations['memory_config'] = $memory_config;
            $query = $sdk->createCompanyService()->addBodyParam('extra_data', $configurations)
                                                ->send('PUT');

            //Cache::forget('business.employees.'.$company->id);                                     
                                                //dd($query);
            # send the request
            if (!$query->isSuccessful()) {
                throw new \RuntimeException('Failed while updating your business information. Please try again.');
            }
            //$message = ['Successfully updated business information for '.$request->name];
            //$response = (tabler_ui_html_response([$message]))->setType(UiResponse::TYPE_SUCCESS);
        } catch (ServerException $e) {
            //$message = json_decode((string) $e->getResponse()->getBody(), true);
            //$response = (tabler_ui_html_response([$message['message']]))->setType(UiResponse::TYPE_ERROR);
            throw new \RuntimeException($message['message']);
        } catch (\Exception $e) {
            //$response = (tabler_ui_html_response([$e->getMessage()]))->setType(UiResponse::TYPE_ERROR);
            //return redirect(url()->current())->with('UiResponse', $response);
            throw new \RuntimeException($e->getMessage());
        }
        //return redirect(url()->current())->with('UiResponse', $response);
        return response()->json($query->getData());

    }


    /**
     * @param array $metrics
     *
     * @return array
     */
    protected function processGraphData(array $metrics): array
    {
        $graph = [];
        foreach ($metrics as $dateKey => $value) {
            $date = Carbon::parse($dateKey);
            $graph[] = [
                'date' => $date->format('d M'),
                'count' => $value['NGN']['count'] ?? 0,
                'total' => $value['NGN']['total'] ?? 0
            ];
        }
        return $graph;
    }
    
    /**
     * @param array $metrics
     *
     * @return array
     */
    protected function processRequestsGraphData(array $metrics): array
    {
        $sections = [];
        $temp = [];
        foreach ($metrics as $section => $data) {
            $sections[] = $section;
            $temp[$section] = [];
            foreach ($data as $dateKey => $value) {
                $date = Carbon::parse($dateKey);
                $temp[$section][] = [
                    'date' => $date->format('d M'),
                    'count' => is_numeric($value) ? $value : count($value)
                ];
            }
        }
        $graphs = [];
        foreach ($sections as $section) {
            $entryKey = substr($section, 9);
            # get the substring
            foreach ($temp[$section] as $entry) {
                if (!isset($graphs[$entry['date']])) {
                    $graphs[$entry['date']] = ['date' => $entry['date']];
                }
                $graphs[$entry['date']][$entryKey] = $entry['count'];
            }
        }
        return array_values($graphs);
    }

    /**
     * @param array $rawStatuses
     * @param array $only
     *
     * @return array
     */
    public static function prepareSummary(array $rawStatuses, array $only = []): array
    {
        $summary = [];
        $only = empty($only) ? array_keys(static::SUMMARY_ICONS) : $only;
        # set the keys to pull up
        foreach ($only as $key) {
            $number = $rawStatuses[$key] ?? 0;
            $key = $key === 'contact_fields' ? 'custom_fields' : $key;
            $prefix = $key === 'cash' ? 'NGN ' : '';
            $summary[] = [
                'name' => str_replace('_', ' ', $key),
                'count' => $number,
                'count_formatted' => $prefix . number_format($number),
                'icon' => self::SUMMARY_ICONS[$key]['icon'] ?? 'poll',
                'bg' => self::SUMMARY_ICONS[$key]['bg'] ?? 'bg-blue'
            ];
        }
        return $summary;
    }

    public function ordinal($number) {
        $ends = array('th','st','nd','rd','th','th','th','th','th','th');
        if ((($number % 100) >= 11) && (($number%100) <= 13))
            return $number. 'th';
        else
            return $number. $ends[$number % 10];
    }


    public function welcome_setup(Request $request, Sdk $sdk) {

        $hubname = !empty($hubConfig['product_name']) ? $hubConfig['product_name'] : config('app.name');
        $this->data['page']['title'] = 'Welcome to the ' . $hubname;
        $this->data['header']['title'] = 'Welcome to the ' . $hubname;
        $this->data['submenuConfig'] = 'navigation-menu.modules-dashboard.sub-menu';
        $this->data['selectedSubMenu'] = 'welcome-setup';
        $this->data['submenuAction'] = '';

        $this->setViewUiResponse($request);

        $company = $request->user()->company(true, true);
        
        $userConfigurations = (array) $request->user()->extra_configurations;
        $userUiSetup = $userConfigurations['ui_setup'] ?? [];
        $configurations = (array) $company->extra_data;
        $this->data['isConfigured'] = true;
        if (empty($userUiSetup)) {
            # user's UI is not configured
            $this->data['isFirstConfiguration'] = empty($configurations['ui_setup']);
            if ($request->has('show_ui_wizard')) {
                $this->data['isConfigured'] = false;
            } else {
                $this->data['isConfigured'] = !$this->data['isFirstConfiguration'];
            }
            # check if the UI has been configured
            $currentUiSetup = $configurations['ui_setup'] ?? [];
            $this->data['setupUiFields'] = collect(self::SETUP_UI_COMPONENTS)->map(function ($field) use ($currentUiSetup) {
                if (!empty($field['is_readonly'])) {
                    return $field;
                }
                if (empty($currentUiSetup)) {
                    return $field;
                }
                $field['enabled'] = in_array($field['id'], $currentUiSetup);
                return $field;
            });
            # add the UI components
        }
        $this->data['countries'] = $countries = $this->getCountries($sdk);
        # get the countries listing
        $nigeria = !empty($countries) && $countries->count() > 0 ? $countries->where('iso_code', 'NG')->first() : null;
        # get the nigeria country model
        if (!empty($nigeria)) {
            $this->data['states'] = $this->getDorcasStates($sdk, $nigeria->id);
            # get the states
        }


        $dorcasUser = $request->user();
        if (!empty($dorcasUser)) {
            if (!empty($dorcasUser->partner) && !empty($dorcasUser->partner['data'])) {
                $partner = (object) $dorcasUser->partner['data'];
                $partnerConfig = (array) $partner->extra_data;
                $hubConfig = $partnerConfig['hubConfig'] ?? [];
            }
        }

        /*if (!$this->data['isConfigured']) {
            return view('modules-dashboard::welcome.setup', $this->data);
        } else {
            return redirect(route('dashboard'));
        }*/
        return view('modules-dashboard::welcome.setup', $this->data);
    }


    public function welcome_overview(Request $request, Sdk $sdk) {

        //$hubname = !empty($hubConfig['product_name']) ? $hubConfig['product_name'] : config('app.name');
        $this->data['page']['title'] = 'Features of the Hub';
        $this->data['header']['title'] = 'Features of the Hub';
        $this->data['submenuConfig'] = 'navigation-menu.modules-dashboard.sub-menu';
        $this->data['selectedSubMenu'] = 'welcome-overview';
        $this->data['submenuAction'] = '';

        $this->data['assistantModules'] = (new Assistant())->getModules($request);

        $this->setViewUiResponse($request);

        $company = $request->user()->company(true, true);
        
        $userConfigurations = (array) $request->user()->extra_configurations;
        $userUiSetup = $userConfigurations['ui_setup'] ?? [];
        $configurations = (array) $company->extra_data;
        $this->data['isConfigured'] = true;
        if (empty($userUiSetup)) {
            # user's UI is not configured
            $this->data['isFirstConfiguration'] = empty($configurations['ui_setup']);
            if ($request->has('show_ui_wizard')) {
                $this->data['isConfigured'] = false;
            } else {
                $this->data['isConfigured'] = !$this->data['isFirstConfiguration'];
            }
            # check if the UI has been configured
            $currentUiSetup = $configurations['ui_setup'] ?? [];
            $this->data['setupUiFields'] = collect(self::SETUP_UI_COMPONENTS)->map(function ($field) use ($currentUiSetup) {
                if (!empty($field['is_readonly'])) {
                    return $field;
                }
                if (empty($currentUiSetup)) {
                    return $field;
                }
                $field['enabled'] = in_array($field['id'], $currentUiSetup);
                return $field;
            });
            # add the UI components
        }


        $memory_config = $configurations['memory_config'] ?? [];
        $subscription = $memory_config["subscription"] ?? [];

        $partnerSubscriptionMeta = HubControl::getPartnerSubscriptionMeta($request, $sdk);


        $this->data['partnerSubscriptionMeta'] = $partnerSubscriptionMeta;

        $this->data['featureSubscriptions'] = $subscription;

        return view('modules-dashboard::welcome.overview', $this->data);
        
    }


    /**
     * @param Request $request
     * @param Sdk     $sdk
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function resendVerification(Request $request, Sdk $sdk)
    {
        $user = $request->user();
        # get the authenticated user
        $query = $sdk->createUserResource($user->id)->send('POST', ['resend-verification']);
        # send the request
        if (!$query->isSuccessful()) {
            throw new \RuntimeException($query->errors[0]['title'] ?? 'Could not resend the verification email.');
        }
        return response()->json($query->getData());
    }


}