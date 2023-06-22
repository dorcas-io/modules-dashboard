@extends('layouts.tabler')

@section('head_css')
<style type="text/css">
    .divide-y>:not(template):not(:last-child) {
        padding-bottom: 1rem!important;
    }
    .divide-y>:not(template):not(:first-child) {
        padding-top: 1rem!important;
    }
    .divide-y>:not(template)~:not(template) {
        border-top: 1px solid rgba(98,105,118,.16)!important;
    }

    .badge:empty {
        display: inline-block;
        width: 0.5rem;
        height: 0.5rem;
        min-width: 0;
        min-height: auto;
        padding: 0;
        border-radius: 100rem;
        vertical-align: baseline;
    }

    .bg-primary {
        color: #fff!important;
        background: #206bc4!important;
        --tblr-bg-opacity: 1;
        background-color: rgba(var(--tblr-primary-rgb),var(--tblr-bg-opacity))!important;
    }

    .badge {
        justify-content: center;
        align-items: center;
        background: #64748b;
        overflow: hidden;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        border: 1px solid transparent;
        min-width: 1.3571428571em;
        font-weight: 600;
        letter-spacing: .04em;
        vertical-align: bottom;
    }
    .visually-hidden, .visually-hidden-focusable:not(:focus):not(:focus-within) {
        position: absolute!important;
        width: 1px!important;
        height: 1px!important;
        padding: 0!important;
        margin: -1px!important;
        overflow: hidden!important;
        clip: rect(0,0,0,0)!important;
        white-space: nowrap!important;
        border: 0!important;
    }
</style>
@endsection


@section('body_content_main')

    <div class="container hopscotch-tour-box" data-tour-name="dashboard" id="dashboard">
        <div class="row" v-if="!user.is_verified">
            <div class="col-sm-12 col-md-6">
                @component('layouts.blocks.tabler.alert-with-buttons')
                    @slot('title')
                        Account Verification Pending
                    @endslot
                    Your account has not yet been verified, would you like to do that now?
                    @slot('buttons')
                    	<button v-on:click.prevent="resendVerification" class="btn btn-secondary" :class="{'btn-loading':verifying}" type="button">Send Verification Email</button>
                    @endslot
                @endcomponent
            </div>
            <div class="col-sm-12 col-md-6">
                @if(count($bank_accounts) <  0)
                    <div class="alert-danger alert mb-0">
                        <div class="d-flex align-items-center alert-danger">
                            <div class="flex-fill ms-3 text-truncate">
                                <h4>Business Bank Details Yet To be Added</h4>
                                <span class="small">The Business is yet to add Bank Details ,Payment can not be processed</span><br><br>
                                <a href="{{url('mse/settings-banking')}}" class="btn btn-danger">Add Bank Details</a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
        <br>
        <div class="row">
                @include('layouts.blocks.tabler.alert')
        </div>

    <div class="row row-cards row-deck" id="dashboard-statistics">
    	@foreach ($summary as $figures)
	    	<div class="col-6 col-sm-4 col-lg-2">
	    		<div class="card p-3">
	    			<div class="d-flex align-items-center">
	    				<span class="stamp stamp-md {{ $figures['bg'] }} mr-3">
	    					<i class="{{ $figures['icon'] }}"></i>
	    				</span>
	    				<div>
	    					<h4 class="m-0"><a href="javascript:void(0)">{{ $figures['count_formatted'] }} <small>{{ title_case($figures['name']) }}</small></a></h4>
	    					<!-- <small class="text-muted">learn more</small> -->
	    				</div>
	    			</div>
	    		</div>
	    	</div>
	    @endforeach
    </div>


	<div class="alert alert-avatar alert-primary alert-dismissible">
	  <span class="avatar" style="background-image: url({{ \Illuminate\Support\Facades\Auth::user()->photo }})"></span>
	    <p class="flow-text">Good @{{ greeting }}, <strong>{{ \Illuminate\Support\Facades\Auth::user()->firstname }}</strong>. Today is {{ \Carbon\Carbon::now()->format('l jS F, Y') }}</p>
	</div>

    <div class="row row-cards row-deck" id="dashboard-new-user" v-if="userDashboardStatus.preferences.guide_needed">
        <div class="col-sm-12 col-md-6 col-lg-4" id="new-user-welcome">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Welcome to the <em>{{ env('DORCAS_PARTNER_PRODUCT_NAME', 'eCommerce Suite') }}</em></h3>
                </div>
                <div class="card-body">
                	<div id="welcome-message">
                        Welcome! It appears you are new here. It's easy to start using the <strong>{{ env('DORCAS_PARTNER_PRODUCT_NAME', 'eCommerce Suite') }}</strong>
                        <br/></br>
                        Follow the <strong>Getting Started Checklist</strong> to get setup in no time.
                        <br/></br>
                        If you still need any help after that, you can:
                        <ul>
                            <li>View <a href="#" v-on:click.prevent="launchHelpCentre">Help Centre</a></li>
                            <li>Read <a :href="dashboardLink.documentation" target="_blank">Documentation</a></li>
                            <li>Watch <a :href="dashboardLink.videos" target="_blank">Our Help Videos</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6 col-lg-8" id="new-user-checklist">
            
            <div class="row">
                <!-- Check List Header Starts -->
                <div class="col-12">
                    <div class="card ">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-3">
                                    <img src="https://cdn.dribbble.com/users/844826/screenshots/14547977/media/e7749bd1b09d9415b8dc265a7dbe81f6.png" alt="Projects Dashboards" class="rounded">
                                </div>
                                <div class="col">
                                    <h3 class="card-title mb-1">
                                        <a href="#" class="text-reset">Getting Started Checklist</a>
                                    </h3>
                                    <div class="text-muted">
                                        {{ $checklists['meta']['done'] . " (out of " . $checklists['meta']['count'] . ")" }} tasks completed.
                                    </div>
                                    <div class="mt-3">
                                        <div class="row g-2 align-items-center">
                                            <div class="col-auto">
                                                {{ $checklists['meta']['score'] }}%
                                            </div>
                                            <div class="col">
                                                <div class="progress progress-sm">
                                                    <div class="progress-bar" style="width: {{ $checklists['meta']['score'] }}%" role="progressbar" aria-valuenow="{{ $checklists['meta']['score'] }}" aria-valuemin="0" aria-valuemax="100" aria-label="{{ $checklists['meta']['score'] }}% Complete">
                                                        <span class="visually-hidden">{{ $checklists['meta']['score'] }}% Complete</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="dropdown">
                                        <a href="#" class="btn-action" data-bs-toggle="dropdown" aria-expanded="false">
                                            <!-- Download SVG icon from http://tabler-icons.io/i/dots-vertical -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><circle cx="12" cy="12" r="1" /><circle cx="12" cy="19" r="1" /><circle cx="12" cy="5" r="1" /></svg>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <!-- <a href="#" class="dropdown-item">Import</a> -->
                                            <a href="#" class="dropdown-item text-danger" v-on:click="dashboardPanelRemove">Remove</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Check List Header Ends -->

                <!-- Scrollable Checklist Starts -->
                <div class="col-12">
                    <div class="card" style="height: 28rem">
                        <div class="card-body card-body-scrollable card-body-scrollable-shadow">
                            <div class="divide-y">
                                @foreach ($checklists['checklists'] as $checklist)
                                    <div>
                                        <div class="row">
                                            <div class="col-auto" style="vertical-align: middle;">
                                                <span class="w-1" style="vertical-align: middle;">
                                                    <input disabled readonly type="checkbox" class="form-check-input m-0 align-middle" aria-label="Checklist Done" {{ $checklist['verification'] ? 'checked' : '' }}>
                                                </span>
                                            </div>
                                            <div class="col-auto">
                                                <span class="avatar">{{ $checklist['index'] }}</span>
                                            </div>
                                            <div class="col">
                                                <div class="text-truncate">
                                                    {!! $checklist['title'] !!}
                                                </div>
                                                <div class="text-muted">{!! ($checklist['description']) !!}</div>
                                            </div>
                                            <div class="col align-self-center">
                                                <a href="{{ $checklist['button_path'] }}" class="btn btn-light btn-square w-100">
                                                    {{ $checklist['button_title'] }}
                                                </a>
                                            </div>
                                            <div class="col-auto align-self-center">
                                                <div class="badge {{ $checklist['verification'] ? 'bg-success' : 'bg-danger' }}"></div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Scrollable Checklist Ends -->

            </div>

        </div>
    </div>

    <div class="row row-cards row-deck" id="dashboard-data">
        <div class="col-sm-12 col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Orders <em>(last 2 weeks)</em></h3>
                </div>
                <div class="card-body">
                	<div id="chart-sales-graph" style="height: 20rem"></div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-md-6">
            <div class="row">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Apps</h3>
                    </div>
                    <div class="card-body">
		                <div class="table-responsive" v-if="applications.length > 0">
		                    <table class="table card-table table-striped table-vcenter">
		                        <tbody>
		                            <tr v-for="(app, index) in applications" :key="app.id">
		                                <td class="w-1">
		                                    <span class="avatar">@{{ app.name.substr(0, 1) }}</span>
		                                </td>
		                                <td>
                                            <p>@{{ app.name }}</p>
                                            <small class="text-muted">@{{ app.description }}</small>
                                        </td>
		                                <td><a href="#" v-on:click.prevent="launchApp(index)" class="btn btn-sm btn-outline-success ml-3">Launch</a></td>
		                            </tr>
		                        </tbody>
		                    </table>
		                </div>
					    <div class="row row-cards row-deck" v-if="applications.length === 0 && !apps_fetching">
					        <div class="col-sm-12">
					            @component('layouts.blocks.tabler.empty-card')
					                @slot('buttons')
					                    <div class="btn-list text-center">
					                        <a href="{{ safe_href_route('app-store-main') ? route('app-store-main').'#apps_apps-store' : '#' }}" class="btn btn-primary">Explore App Store</a>
					                    </div>
					                @endslot
					                You have no Apps installed
					            @endcomponent
					        </div>
					    </div>
                        <div class="row" v-if="applications.length === 0 && apps_fetching">
                          <div class="loader"></div>
                          <div>Loading Apps</div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        @include('modules-dashboard::modals.message')
    </div>

@endsection
@section('body_js')
    <script>
        new Vue({
            el: '#dashboard',
            data: {
                applications: [],
                meta: [],
                authorization_token: '{{ $authToken ?? '' }}',
                stats: {!! json_encode(!empty($stats) ? $stats : []) !!},
                message: '{{ $message }}',
                dashboard_message: { 'title': 'Message', 'body': '', 'action': '', 'action_url': '#' },
                verifying: false,
                user: {!! json_encode($dorcasUser) !!},
                business: {!! json_encode($business) !!},
                subscription: {!! json_encode(!empty($plan) ? $plan : []) !!},
                businessConfiguration: [],
                apps_fetching: false,
                dashboardLink: {!! json_encode($dashboard_links) !!},
                userDashboardStatus: {!! json_encode($user_dashboard_status) !!},
            },
            computed: {
                greeting: function () {
                    var hourOfDay = parseInt(moment().format('HH'), 10);
                    if (hourOfDay >= 0 && hourOfDay < 12) {
                        return 'morning';
                    } else if (hourOfDay >= 12 && hourOfDay <= 16) {
                        return 'afternoon';
                    }
                    return 'evening';
                }
            },
            mounted: function () {
                //console.log(this.business);
                if (this.message !== null && this.message.length > 0) {
                    //Materialize.toast(this.message, 4000);
                    this.dashboard_message.title = "Subscription Expired"
                    this.dashboard_message.body = "<p>" + this.message + "</p>"
                    this.dashboard_message.action = "Renew Subscription"
                    this.dashboard_message.action_url = "/mse/settings-subscription"
                    $('#dashboard-message-modal').modal('show'); 
                }
                if (typeof this.subscription.price !== 'undefined' && this.subscription.price > 0) {
                    //this.showPaystackDialog();
                }
                if (typeof this.business.extra_data !== 'undefined' && this.business.extra_data !== null) {
                    this.businessConfiguration = this.business.extra_data;
                }
                /*if (this.account_expired()) {
                    this.apps_fetching = true;
                } else {
                    this.searchAppStore(1, 12, 'installed_only');
                }*/
                this.searchAppStore(1, 12, 'installed_only');
                //console.log(this.account_expired())
                //console.log(this.applications)
                
                //console.log(this.salesGraph);

            },
            methods: {
                dashboardPanelRemove: function() {
                    this.userDashboardStatus.preferences.guide_needed = false;
                },
                processDashboard: function(processType, processPayload) {
                    axios.post("/dashboard/process-dashboard"{
                        type: processType,
                        payload: processPayload
                    })
                        .then(function (response) {
                            //swal('Title', 'Description', 'success');
                        }).catch(function (error) {
                            var message = '';
                            if (error.response) {
                                var e = error.response;
                                message = e.data.message;
                            } else if (error.request) {
                                message = 'The request was made but no response was received';
                            } else {
                                message = error.message;
                            }
                            context.verifying = false;
                            swal("Oops!", message, "warning");
                        });
                },
                account_expired: function() {
                    var expireString = 'account subscription expired'
                    var url = document.location.toString();
                    return url.match(expireString) ? true : false
                },
                resendVerification: function () {
                    var context = this;
                    this.verifying = true;
                    axios.post("/dashboard/resend-verification")
                        .then(function (response) {
                            //console.log(response)
                            context.verifying = false;
                            swal('Email Sent', 'A email was just sent to your address. Kindly follow the instructions in it.', 'success');
                        }).catch(function (error) {
                            var message = '';
                            //console.log(error);
                            if (error.response) {
                                // The request was made and the server responded with a status code
                                // that falls out of the range of 2xx
                            //var e = error.response.data.errors[0];
                            //message = e.title;
                            var e = error.response;
                            message = e.data.message;
                            } else if (error.request) {
                                // The request was made but no response was received
                                // `error.request` is an instance of XMLHttpRequest in the browser and an instance of
                                // http.ClientRequest in node.js
                                message = 'The request was made but no response was received';
                            } else {
                                // Something happened in setting up the request that triggered an Error
                                message = error.message;
                            }
                            context.verifying = false;
                            swal("Oops!", message, "warning");
                        });
                },
                showPaystackDialog: function () {
                    var context = this;
                    var handler = PaystackPop.setup({
                        key: '{{ config('services.paystack.public_key') }}',
                        email: this.user.email,
                        amount: this.subscription.price * 100,
                        channels: ['card'],
                        metadata: {
                            custom_fields: [
                                {
                                    display_name: "Mobile Number",
                                    variable_name: "mobile_number",
                                    value: this.user.phone
                                },
                                {
                                    display_name: "Business",
                                    variable_name: "business",
                                    value: this.business.name
                                },
                                {
                                    display_name: "Plan",
                                    variable_name: "plan",
                                    value: this.business.plan.data.name
                                },
                                {
                                    display_name: "Plan Type",
                                    variable_name: "plan_type",
                                    value: this.business.plan_type
                                }
                            ]
                        },
                        callback: context.verifyTransaction,
                        onClose: function() {

                        }
                    });
                    handler.openIframe();
                },
                verifyTransaction: function (response) {
                    console.log(response);
                    var context = this;
                    this.verifying = true;
                    axios.post("/xhr/billing/verify", {
                        reference: response.reference,
                        channel: 'paystack'
                    }).then(function (response) {
                        context.verifying = false;
                        window.location = "/home";
                    }).catch(function (error) {
                        var message = '';
                        console.log(error);
                        if (error.response) {
                            // The request was made and the server responded with a status code
                            // that falls out of the range of 2xx
                            //var e = error.response.data.errors[0];
                            //message = e.title;
                            var e = error.response;
                            message = e.data.message;
                        } else if (error.request) {
                            // The request was made but no response was received
                            // `error.request` is an instance of XMLHttpRequest in the browser and an instance of
                            // http.ClientRequest in node.js
                            message = 'The request was made but no response was received';
                        } else {
                            // Something happened in setting up the request that triggered an Error
                            message = error.message;
                        }
                        context.verifying = false;
                        swal("Oops!", message, "danger");
                    });
                },
                searchAppStore: function (page, limit, filt) {
                    let context = this;
                    if (typeof filt !== 'undefined') {
                        this.filter = filt;
                    }
                    if (typeof page !== 'undefined' && !isNaN(page)) {
                        this.page_number = page;
                    }
                    if (typeof limit !== 'undefined' && !isNaN(limit)) {
                        this.limit = limit;
                    }
                    this.apps_fetching = true;
                    axios.get("/map/app-store", {
                        params: {
                            search: context.search_term,
                            limit: context.limit,
                            page: context.page_number,
                            category_slug: context.category_slug,
                            filter: filt
                        }
                    }).then(function (response) {
                        context.apps_fetching = false;
                        context.applications = response.data.data;
                        context.meta = response.data.meta;
                    }).catch(function (error) {
                        var message = '';
                        context.apps_fetching = false;
                        //console.log(error.response)
                        if (error.response) {
                            var e = error.response;
                            message = e.data.message;
                        } else if (error.request) {

                            message = 'The request was made but no response was received';
                        } else {
                            message = error.message;
                        }
                        return swal("Oops!", message, "warning");
                    });
                },
                launchApp: function(index) {
                    let context = this;
                    if (this.apps_fetching) {
                        // currently processing something
                        swal('Please Wait', 'Your previous request is still processing...', 'info');
                        return;
                    }
                    let app = typeof this.applications[index] !== 'undefined' ? this.applications[index] : {};
                    if (typeof app.id === 'undefined') {
                        return;
                    }
                    if (app.is_installed && app.homepage_url !== null && app.type === 'web') {
                        let launch_url = app.homepage_url + '/install/setup?token=' + context.authorization_token;
                        window.open(launch_url);
                    }
                },

            }
        });

        @if (!empty($salesGraph))

			function ordinal_suffix_of(i) {
			    var j = i % 10,
			        k = i % 100;
			    if (j == 1 && k != 11) {
			        return i + "st";
			    }
			    if (j == 2 && k != 12) {
			        return i + "nd";
			    }
			    if (j == 3 && k != 13) {
			        return i + "rd";
			    }
			    return i + "th";
			}

            $(function() {
                c3.generate({
                    bindto: '#chart-sales-graph', // id of chart wrapper
                    data: {
                    	columns: {!! json_encode($salesGraph["columns"]) !!},
                    	type: 'line',
                    	colors: {!! json_encode($salesGraph["colors"]) !!},
                    	names: {!! json_encode($salesGraph["names"]) !!},
                    	axes: {!! json_encode($salesGraph["axes"]) !!},
                        
                    },
                    axis: {
                        x: {
                            type: 'category',
                            categories: {!! json_encode($salesGraph["categories"]) !!}
                        },
                        y: {
                        	tick: {
                        		format: d3.format(",")
                        	}
                        },
                        y2: {
                            show: true,
                        	tick: {
                        		format: d3.format(",")
                        	}
                        }
                    },
				    tooltip: {
				        format: {
				            title: function (d) { return 'Activity on ' + ordinal_suffix_of(d); },
				            value: function (value, ratio, id) {
				                var format = id === 'total' ? d3.format(',') : d3.format(',');
				                return format(value);
				            }
				//            value: d3.format(',') // apply this format to both y and y2
				        }
				    },
                    legend: {
                        position: 'inset',
                        padding: 0,
                        inset: {
                            anchor: 'top-right',
                            x: 20,
                            y: 8,
                            step: 10
                        }
                    },
                    padding: {
                        bottom: 0,
                        top: 0
                    }
                });
            });
        @endif

    </script>
@endsection
