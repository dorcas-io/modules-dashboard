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
                    Below are details of the available features of Hub platform; reading through  will help get you started
                  </p>
				  <p>
				  	Additionally, you can do the following:
				  </p>
                  <div class="btn-list">
                    <button class="btn btn-success" type="button" v-on:click.prevent="startHubTour"><i class="fa fa-hand-o-right mr-2"></i> Take A Quick Tour</button>
                    <a class="btn btn-secondary" href="{{ route('welcome-setup') }}">Go to Setup</a>
                    <a class="btn btn-primary" href="{{ route('dashboard') }}"><i class="fa fa-home mr-2"></i>Go to Dashboard</a>
                  </div>

				</div>
    	</div>

        <div class="container col-md-12" id="listing_overview">
				                                            
        	@if ( count($assistantModules) > 0 )
	            <div class="row">
	            	@foreach ($assistantModules as $feature)
		             <div class="card card-aside col-md-12 col-lg-6">
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
		             </div>
		             @endforeach
	            </div>
            @endif
        </div>

    </div>

</div>


@endsection
@section('body_js')
    <script type="text/javascript">


        var vmOverviewModal = new Vue({
            el: '#welcome-overview',
            data: {
                viewMode: headerAuthVue.viewMode
            },
            mounted: function () {

            },
            methods: {
            	startHubTour: function() {
            		assistantVue.startHubTour(welcomeTour);
            	}
            },
            computed: {

            }
        });
    </script>
@endsection

