@extends('layouts.tabler')

@section('head_css')
    <style type="text/css">

        .ribbon.bg-red {
            border-color: #d63939;
        }

        .bg-red {
            color: #fff!important;
            background: #d63939!important;
        }
        
        .ribbon.bg-yellow {
            border-color: #f59f00;
        }

        .bg-yellow {
            color: #fff!important;
            background: #f59f00!important;
        }
        
        .ribbon.bg-primary {
            border-color: #206bc4;
        }

        .bg-primary {
            color: #fff!important;
            background: #206bc4!important;
        }


        .ribbon {
            position: absolute;
            top: 0.75rem;
            right: -0.25rem;
            z-index: 1;
            padding: 0.25rem 0.75rem;
            font-size: .625rem;
            font-weight: 600;
            line-height: 1.5rem;
            color: #fff;
            text-align: center;
            text-transform: uppercase;
            background: #206bc4;
            border-color: #206bc4;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 2rem;
            min-width: 2rem;
        }
    </style>
@endsection

@section('body_content_header_extras')

@endsection


@section('body_content_main')
@include('layouts.blocks.tabler.alert')

<div class="row">

    <div class="col-md-12 col-xl-12" id="admin-check">


    <div class="row row-cards row-deck" id="store-statistics">
	    	<div class="col-md-12 col-lg-12">
	    		<div class="card p-3">
	    			<div class="d-flex align-items-center">
	    				<span class="stamp stamp-md bg-green mr-3">
                            <i class="fe fe-grid"></i>
                        </span>
	    				<div>
	    					<h4 class="m-0"><a href="javascript:void(0)">You Are Logged In As An Administrator</a></h4>
	    					<small class="text-muted">
                                <strong>Do you want explore as an SME OR continue as an Administrator?</strong>
                            </small>
	    				</div>
	    			</div>
	    		</div>
	    	</div>
	    </div>


        <div class="row col-md-12">

            <div class="col-md-6 col-lg-6">

                <form action="#" method="get"> <!-- class="col s12" -->
                    {{ csrf_field() }}

                    <div class="card">
                        <div class="ribbon bg-primary">SME</div>
                        <div class="card-body">
                            <h3 class="card-title">Explore As SME</h3>
                            
                            <p class="text-muted">

                                You can explore the <strong>{{ env('DORCAS_PARTNER_PRODUCT_NAME', 'eCommerce Suite') }} as an SME</strong>

                                <div class="col-md-12">
                                    <a href="{{ route('dashboard') . '?viewAsSME' }}" class="btn btn-primary">Explore As SME</a>
                                </div>

                            </p>

                        </div>
                    </div>

                </form>

            </div>


            <div class="col-md-6 col-lg-6">

                @php
                    if (!empty($vPanelUrl)) {
                        $admin_url = $vPanelUrl;
                    } else {
                        $admin_url = "#";
                    }
                @endphp

                <form action="#" method="get"> <!-- class="col s12" -->
                    {{ csrf_field() }}

                    <div class="card">
                        <div class="ribbon bg-red">ADMIN</div>
                        <div class="card-body">
                            <h3 class="card-title">Explore as Administrator</h3>
                            <p class="text-muted">

                                You can view/manage all SMEs registered on the <strong>{{ env('DORCAS_PARTNER_PRODUCT_NAME', 'eCommerce Suite') }}</strong>

                                <div class="col-md-12">
                                    <a href="{{ $admin_url }}" target="_blank" class="btn btn-danger">Explore As Admin</a>
                                </div>

                            </p>
                        </div>
                    </div>

                </form>

            </div>

        </div>



    </div>

</div>


@endsection
@section('body_js')
    <script type="text/javascript">
        new Vue({
            el: '#ecommerce-store',
            data: {

            },
            mounted: function() {
                

            },
            computed: {
                
            },
            methods: {
                
                
            },
        });
    </script>
@endsection

