@extends('layouts.tabler')
@section('body_content_header_extras')

@endsection
@section('body_content_main')
@include('layouts.blocks.tabler.alert')

<div class="row">
    @include('layouts.blocks.tabler.sub-menu')

    <div class="col-md-9 col-lg-9" id="welcome-overview">
	    
    	<div class="row ">
				<div class="alert alert-avatar alert-success col-md-12" id="welcome-area">
				  <span class="avatar" style="background-image: url({{ cdn('images/avatar/avatar-9.png') }})"></span>
				  <h4>Welcome back!</h4>
                  <p>
                    Below are details of the available features of Hub platform. To proceed, you can do one the following:
                  </p>
				  <p>
				  	<ul>
				  		<li><strong>Watch A Video</strong>: Get an overview of the Dorcas Hub Platform</li>
				  		<li><strong>Take A Tour</strong>: Understand what each Menu is for</li>
				  		<li><strong>Go To Dashboard</strong>: Start Using the Hub</li>
				  		<!-- <li><strong>Go To Library</strong>: Learn more about how technology helps your business</li> -->
				  	</ul>
				  </p>
                  <div class="btn-list">
                  	<button class="btn btn-info" type="button" v-on:click.prevent="watchVideo"><i class="fe fe-play mr-2"></i> Watch A Video</button>
                    <button class="btn btn-success d-none d-md-inline" type="button" v-on:click.prevent="startHubTour"><i class="fa fa-hand-o-right mr-2"></i> Take A Quick Tour</button>
                    <a class="btn btn-secondary" href="{{ route('welcome-setup') }}">Go to Setup</a>
                    <a class="btn btn-primary" href="{{ route('dashboard') }}"><i class="fa fa-home mr-2"></i>Go to Dashboard</a>
                  </div>

				</div>
    	</div>
    	@include('modules-dashboard::modals.welcome-video')

        <div class="container col-md-12" id="listing_overview">
				                                            
        	@if ( count($assistantModules) > 0 )
	            <div class="row">

					<form id="form-welcome-features" action="/dashboard/features" method="post" v-on:submit.prevent="submitWelcomeFeatures">
						<div class="form-group">
			            	<div>
			            		<h4>Features List</h4>
			            		<p>
			            			<em>A tick mark is shown on features that are currently selected and active. If a feature is not enabled, you can always select it to activate or try it out.</em>
			            		</p>
			            	</div>
			            	<div class="row gutters-sm">
			            		@foreach ($setupUiFields as $field)
			            			@php
			            				$path = $field["path"];
			            				$field_id = $field["id"];
			            				$feature = !is_array($path) && !empty($assistantModules[$path]) ? $assistantModules[$path] : [];
			            				if (count($feature)>0) {
			            					$image = cdn($feature['display_image']);
			            				} else {
			            					$image = cdn("images/overview/dashboard.jpg");
			            				}
			            			@endphp
			            			@continue($field['is_readonly'] === true)
				            		<div class="col-md-4 col-sm-6">
				            			<label class="imagecheck mb-4">
				            				<input type="checkbox" name="selected_apps[]" multiple value="{{ $field['id'] }}" class="imagecheck-input"  {{ !empty($field['enabled']) ? 'checked' : '' }} {{ !empty($field['is_readonly']) ? 'disabled' : '' }} />
				            				<figure class="imagecheck-figure">
				            					<img src="{{ $image }}" alt="}" class="imagecheck-image" width="250">
				            				</figure>
				            				<strong>{{ $field['name'] }}</strong><br/>
											<!--
				            				@if (!empty($field['enabled']))
				            					@if (!empty($featureSubscriptions[$field_id]))
				            						@php
				            							$old_package_title = $featureSubscriptions[$field_id]["subscription_title"];
				            							$new_package_title = $featureSubscriptions[$field_id]["subscription_title"];
				            						@endphp
				            						{{ $featureSubscriptions[$field_id]["subscription_title"] }} Package | Expires <em>{{ Carbon\Carbon::parse($featureSubscriptions[$field_id]["expires"])->format('l jS F, Y') }}</em><br/>
				            						<a href="#" v-on:click.prevent="upgradeShow('{{ $old_package_title }}')">Upgrade</a> | <a href="#">Benefits</a><br/>
				            						{{ implode(",", $featureSubscriptions[$field_id]["subscription"]) }}<br/>
				            						{{ implode(",", $featureSubscriptions[$field_id]["subscriptions"]) }}
				            					@endif
				            				@endif
											-->
				            			</label>
				            		</div>
				            	@endforeach
			            	</div>
			            </div>
			        </form>

	            	@foreach ($assistantModules as $feature)
		             <!-- <div class="card card-aside col-md-12 col-lg-6">
		             	<a href="#" class="card-aside-column" style="background-image: url({{ cdn($feature['display_image']) }})"></a>
		             	<div class="card-body d-flex flex-column">
		             		<h4>{{ $feature['title'] }}</h4>
		             		<div class="text-muted">{{ $feature['description'] }}</div>
		             		<div class="d-flex align-items-center pt-5 mt-auto">
		             			<div>
		             				<small class="d-block text-muted align-top">
		             					<ul>
			             					@foreach ($feature['action_list'] as $action)
			             						{!! $action !!}
			             					@endforeach
			             				</ul>
		             				</small>
		             			</div>
		             			@if (!empty($feature['action_title']))
			             			<div class="ml-auto text-muted">
			             				<a href="{{ $feature['action_url'] }}" class="btn btn-sm btn-outline-primary">{{ $feature['action_title'] }}</a>
			             			</div>
			             		@endif
		             		</div>
		             	</div>
		             </div> -->
		             @endforeach
	            </div>

	            <div class="row">
			           <button type="submit" form="form-welcome-features" class="btn btn-primary btn-block" name="action" :class="{'btn-loading':submittingFeatures}" value="save_features">Update Feature Selection</button>
	            </div>

            @endif
        </div>

    @include('modules-dashboard::modals.upgrade')
    </div>

</div>


@endsection
@section('body_js')
    <script type="text/javascript">


        var vmOverviewModal = new Vue({
            el: '#welcome-overview',
            data: {
                viewMode: headerAuthVue.viewMode,
                submittingFeatures: false,
                upgrade_subscription: {'title': 'Message', 'body': '','package_old': '','package_new': '', 'action': 'Upgrade', 'action_url': '#' },
            },
            mounted: function () {

            },
            methods: {
            	startHubTour: function() {
            		assistantVue.startHubTour(welcomeTour);
            	},
            	upgradeShow: function(package_old) {
            		this.upgrade_subscription.title = 'Upgrade';
            		this.upgrade_subscription.package_old = package_old;
                    /*this.dashboard_message.title = "Subscription Expired"
                    this.dashboard_message.body = "<p>" + this.message + "</p>"
                    this.dashboard_message.action = "Renew Subscription"
                    this.dashboard_message.action_url = "/mse/settings-subscription"*/
                    $('#dashboard-upgrade-modal').modal('show'); 
            	},
                watchVideo: function () {
                    $('#welcome-video-modal').modal('show');

                    $('#welcome-video-modal').on('shown.bs.modal', function (e) {
                      $("#welcome-video").attr('src', "https://www.youtube.com/embed/SqBXm0acWNQ?autoplay=1&amp;modestbranding=1&amp;showinfo=0&amp;rel=0" );
                      var exp = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig;
                      let resource_linked = ''.replace(exp, "<a target=\"_blank\" href='$1'>$1</a>");
                      $("#welcome-video-description").html(resource_linked);
                    });
                    $('#welcome-video-modal').on('hide.bs.modal', function (e) {
                      $("#welcome-video").attr('src', 'https://www.youtube.com/embed/SqBXm0acWNQ' );
                    });
                },
                submitWelcomeFeatures: function () {
                    let context = this;
                    this.submittingFeatures = true;
                    var selected_apps = [];
		            $.each($("input[name='selected_apps[]']:checked"), function() {
		            	selected_apps.push($(this).val());
		            });
		            var selectedapps = selected_apps.join(", ");

                    let formValues = {
                        selected_apps: selected_apps
                    }
                    //console.log(formValues)

                    axios.post('{{ route("save-dashboard-features") }}', formValues)
                    .then(function (response) {
                        //console.log(response);
                        context.submittingSetup = false;
                        //Materialize.toast('Group added.', 3000);
                        window.location = '{{ route("dashboard")."?fromsetup" }}'
                        return swal("Great!", "We've saved your feature selection", "success");
                    })
                        .catch(function (error) {
                            var message = '';
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
                            context.submittingFeatures = false;
                            //Materialize.toast('Error: '+message, 4000);
                            swal("Features Setup Failed:", message, "warning");
                        });
                }
            },
            computed: {

            }
        });
    </script>
@endsection

