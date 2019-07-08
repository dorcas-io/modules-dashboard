@extends('layouts.tabler')
@section('body_content_header_extras')

@endsection
@section('body_content_main')
@include('layouts.blocks.tabler.alert')

<div class="row">
    @include('layouts.blocks.tabler.sub-menu')

    <div class="col-md-9 col-lg-9" id="welcome-overview">

	    
    	<div class="row ">
				<div class="alert alert-avatar alert-success alert-dismissible col-md-12">
				  <span class="avatar" style="background-image: url({{ cdn('images/avatar/avatar-9.png') }})"></span>
				  Welcome back! We would like to give you a tour of the available features as well as the new Hub interface
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
                viewMode: headerAuthVue.viewMode,
            },
            mounted: function () {

            },
            computed: {

            }
        });
    </script>
@endsection

