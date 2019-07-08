@extends('layouts.tabler')
@section('body_content_main')

    <div class="container hopscotch-tour-box" data-tour-name="dashboard" id="dashboard">
        <div class="row" v-if="!user.is_verified">
            <div class="col s12">
                @component('layouts.blocks.tabler.alert-with-buttons')
                    @slot('title')
                        Account Verification Pending
                    @endslot
                    Your account has not yet been verified, would you like to do that now?
                    @slot('buttons')
                    	<button v-on:click.prevent="resendVerification" class="btn btn-secondary" type="button">Send Verification Email</button>
                    @endslot
                @endcomponent
            </div>
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
                authorization_token: '{{ $authToken or '' }}',
                stats: {!! json_encode(!empty($stats) ? $stats : []) !!},
                message: '{{ $message }}',
                dashboard_message: { 'title': 'Message', 'body': '', 'action': '', 'action_url': '#' },
                verifying: false,
                user: {!! json_encode($dorcasUser) !!},
                business: {!! json_encode($business) !!},
                subscription: {!! json_encode(!empty($plan) ? $plan : []) !!},
                businessConfiguration: [],
                apps_fetching: false
                /*salesGraph: {!! json_encode($salesGraph) !!}*/
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
                account_expired: function() {
                    var expireString = 'account subscription expired'
                    var url = document.location.toString();
                    return url.match(expireString) ? true : false
                },
                resendVerification: function () {
                    var context = this;
                    this.verifying = true;
                    axios.post("/xhr/account/resend-verification")
                        .then(function (response) {
                            console.log(response)
                            context.verifying = false;
                            swal('Email Sent', 'A email was just sent to your address. Kindly follow the instructions in it.', 'success');
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
                        console.log(error.response)
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
