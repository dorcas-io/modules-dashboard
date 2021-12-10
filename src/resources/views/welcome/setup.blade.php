@extends('layouts.tabler')
@section('body_content_header_extras')

@endsection
@section('body_content_main')
@include('layouts.blocks.tabler.alert')

<div class="row">
    @include('layouts.blocks.tabler.sub-menu')

    <div class="col-md-9 col-lg-9" id="welcome-setup">
    	<div class="row">

            <div class="card col-md-12">
                <div class="card-header">
                    <h3 class="card-title">What is the Hub?</h3>
                </div>
                <div class="card-body">

					<div class="alert alert-avatar alert-primary col-sm-12" id="welcome-area">
					  <span class="avatar" style="background-image: url({{ cdn('images/avatar/avatar-9.png') }})"></span>
					  The Hub is an all-in-one productivity software platform that helps you run your entire business better.
					  <br/><br/>
					  It is basically a collection of tools that you can use to digitally manage e-commerce, sales, people, accounting and so much more!
					  <br/><br/>
					  <div class="btn-list">
					    <button class="btn btn-success" type="button" v-on:click.prevent="watchVideo"><i class="fe fe-play mr-2"></i> Watch A Video</button>
                        @if ($isConfigured)
                            <!-- <a v-if="isConfigured" class="btn btn-secondary" href="{{ route('welcome-overview') }}">Explore Features</a> -->
                            <button v-if="isConfigured" class="btn btn-primary d-none d-md-inline" type="button" v-on:click.prevent="startHubTour"><i class="fa fa-hand-o-right mr-2"></i> Take A Tour</button>
                            <a v-if="isConfigured" class="btn btn-primary" href="{{ route('dashboard') }}"><i class="fa fa-home mr-2"></i>Go to Dashboard</a>
                        @endif
					  </div> 
					</div>
                	 
                </div>
            </div>
        </div>
        <div class="row card col-md-12">
            <div class="card-header">
                <h3 class="card-title">Get Started</h3>
            </div>
            <div class="card-body">
				<p>
					Before we go further, please provide the following information to help us customize better and get the platform all setup for you
				</p>
				<form id="form-welcome-setup" action="{{ route('welcome-setup-post') }}" method="post"> <!-- v-on:submit.prevent="submitWelcomeSetup"-->
                    @csrf
                    <div class="row">
                        <div class="form-group col-sm-12 col-md-12">
                            <input placeholder="Business Name" id="business_name" name="business_name" type="text"
                                   class="form-control" required v-model="business.name">
                            <label class="form-label" for="business_name">Business Name</label>
                        </div>
                        <div class="form-group col-sm-12 col-md-6">
                            <select class="form-control" name="business_type" id="business_type" v-model="businessConfiguration.business_type" required>
                                <option value="">Choose your Business Type</option>
                                <option value="sole proprietorship">Sole Proprietorship</option>
                                <option value="limited liability">Limited Liability</option>
                            </select>
                            <label class="form-label" for="business_name">Business Type</label>
                        </div>
                        <div class="form-group col-sm-12 col-md-6">
                            <select class="form-control" name="business_sector" id="business_sector" v-model="businessConfiguration.business_sector" required>
                                <option value="">Choose your Business Area</option>
                                <option value="Aerospace">Aerospace</option>
                                <option value="Agriculture">Agriculture</option>
                                <option value="Banking & Financial Services">Banking & Financial Services</option>
                                <option value="Chemical & Pharmaceutical">Chemical & Pharmaceutical</option>
                                <option value="Computer & IT">Computer & IT</option>
                                <option value="Construction">Construction</option>
                                <option value="Consulting">Consulting</option>
                                <option value="Defense">Defense</option>
                                <option value="Education">Education</option>
                                <option value="Energy">Energy</option>
                                <option value="Electrical & Electronics">Electrical & Electronics</option>
                                <option value="Entertainment">Entertainment</option>
                                <option value="Food">Food</option>
                                <option value="Insurance">Insurance</option>
                                <option value="Healthcare">Healthcare</option>
                                <option value="Hospitality">Hospitality</option>
                                <option value="Information, News & Media">Information, News & Media</option>
                                <option value="Mining">Mining</option>
                                <option value="Music & Film">Music & Film</option>
                                <option value="Manufacturing">Manufacturing</option>
                                <option value="Retail">Retail</option>
                                <option value="Steel">Steel</option>
                                <option value="Telecommunications">Telecommunications</option>
                                <option value="Transport">Transport</option>
                                <option value="Water">Water</option>
                                <option value="others">Others</option>
                            </select>
                            <label class="form-label" for="business_sector">Business Sector</label>
                        </div>

                        <div class="form-group col-sm-12 col-md-6">
                            <select class="form-control" name="business_size" id="business_size" v-model="businessConfiguration.business_size" required>
                                <option value="">Choose your Business Type</option>
                                <option value="1">1 Person (Just You)</option>
                                <option value="2 - 9">2 - 9 People</option>
                                <option value="10 - 49">10 - 49 People</option>
                                <option value="50 - 99">50 - 99 People</option>
                                <option value="100+">100+ People</option>
                            </select>
                            <label class="form-label" for="business_size">Business Size</label>
                        </div>
                        <div class="form-group col-sm-12 col-md-6">
                            <select class="form-control" name="business_country" id="business_country" v-model="businessConfiguration.country_id" required>
                                <option value="">Choose your Country</option>
                                @if (!empty($countries))
                                    @foreach ($countries as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <label class="form-label" for="business_country">Country</label>
                        </div>
                        <div class="form-group col-sm-12 col-md-6">
                            <select class="form-control" name="business_state" id="business_state" v-model="businessConfiguration.state_id">
                                <option value="">Choose your State (Nigeria Only)</option>
                                <option value="non-nigerian">Non-Nigerian</option>
                                @if (!empty($states))
                                    @foreach ($states as $state)
                                        <option value="{{ $state->id }}">{{ $state->name }}</option>
                                    @endforeach
                                @endif
                            </select>
                            <label class="form-label" for="business_state">State/Region</label>
                        </div>
                        <div class="form-group col-sm-12 col-md-6">
                            <select class="form-control" name="currency" id="currency" v-model="businessConfiguration.currency" required>
                                <option value="">Choose your Currency</option>
                                @foreach ($isoCurrencies as $currency)
                                    <option value="{{ $currency['alphabeticCode'] }}">{{ $currency['currency'] }} - {{ $currency['alphabeticCode'] }}</option>
                                @endforeach
                            </select>
                            <label class="form-label" for="currency">Currency</label>
                        </div>
                    </div>

                    <div class="row mt-4"> <!--  d-none -->
                        <div class="col-sm-12 col-md-12">
                            <p class="text-uppercase">
                                Select all <strong>Hub modules</strong> would you like to activate
                                <hr>
                            </p>
                        </div>

                            @foreach ($setupUiFields as $field)
    		                    <div class="col-md-4 col-lg-3">
    		                        <label class="custom-switch">
    		                            <input type="checkbox" name="selected_apps[]" multiple value="{{ $field['id'] }}" {{ !empty($field['enabled']) ? 'checked' : '' }} {{ !empty($field['is_readonly']) ? 'disabled' : '' }} class="custom-switch-input">
    		                            <span class="custom-switch-indicator"></span>
    		                            <span class="custom-switch-description">{{ $field['name'] }}</span>
    		                        </label>
    		                    </div>
                            @endforeach

                    </div>

				</form>
				@include('modules-dashboard::modals.welcome-video')
            </div>
            <div class="card-footer">
	            <button type="submit" form="form-welcome-setup" class="btn btn-primary btn-block" name="action" :class="{'btn-loading':submittingSetup}" value="save_preferences">Save Preferences</button>
            </div>
        </div>

    </div>

</div>


@endsection
@section('body_js')
    <script>
        new Vue({
            el: '#welcome-setup',
            data: {
                user: {!! json_encode($dorcasUser) !!},
                business: {!! json_encode($business) !!},
                subscription: {!! json_encode(!empty($plan) ? $plan : []) !!},
                businessConfiguration: [],
                submittingSetup: false,
                isConfigured: {!! $isConfigured !!}
            },
            computed: {

            },
            mounted: function () {
                if (typeof this.business.extra_data !== 'undefined' && this.business.extra_data !== null) {
                    this.businessConfiguration = this.business.extra_data;
                }
                //console.log(this.isConfigured);
            },
            methods: {
                /*isConfigured: function() {
                    if (this.isConfigured) {
                        console.log('confi')
                    } else {
                        console.log('not confi')
                    }

                },*/
                startHubTour: function() {
                    assistantVue.startHubTour(welcomeTour);
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
                submitWelcomeSetup: function () {
                    let context = this;
                    this.submittingSetup =  true;
                    var selected_apps = [];
		            $.each($("input[name='selected_apps[]']:checked"), function() {
		            	selected_apps.push($(this).val());
		            });
		            var selectedapps = selected_apps.join(", ");

                    let formValues = {
                        business_name: context.business.name,
                        business_type: context.businessConfiguration.business_type,
                        business_sector: context.businessConfiguration.business_sector,
                        business_size: context.businessConfiguration.business_size.toString(),
                        business_country: context.businessConfiguration.country_id,
                        business_state: context.businessConfiguration.state_id,
                        currency: context.businessConfiguration.currency,
                        selected_apps: selected_apps
                    }
                    //console.log(formValues)

                    axios.post("/dashboard/setup/", formValues)
                    .then(function (response) {
                        //console.log(response);
                        context.submittingSetup = false;
                        
                        window.location = '{{ route("welcome-overview")."?fromsetup" }}'
                        return swal("Great!", "Thanks for providing the information", "success");
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
                            context.submittingSetup = false;
                            //Materialize.toast('Error: '+message, 4000);
                            swal("Setup Failed:", message, "warning");
                        });
                },
            }
        });

    </script>
@endsection

