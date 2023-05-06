
@extends('layouts.tabler')

<style>
   body{
    background-color:#eee;
    margin-top:20px;
}
.card {
    box-shadow: 0 20px 27px 0 rgb(0 0 0 / 5%);
}
.nav-tabs-custom .nav-item .nav-link.active {
    color: #6c6ff5;
}
.nav-tabs-custom .nav-item .nav-link {
    border: none;
    background: none;
	color: #6c6ff5;
	border: none;
	padding: 0;
	font: inherit;
	cursor: pointer;
	outline: inherit;
}
.text-muted {
    color: #8ca3bd!important;
}
.nav-tabs-custom .nav-item {
    position: relative;
    color: #271050;
}
.nav-tabs-custom .nav-item .nav-link.active:after {
    -webkit-transform: scale(1);
    transform: scale(1);
}
.nav-tabs-custom .nav-item .nav-link::after {
    content: "";
    background: #6c6ff5;
    height: 2px;
    position: absolute;
    width: 100%;
    left: 0;
    bottom: -1px;
    -webkit-transition: all 250ms ease 0s;
    transition: all 250ms ease 0s;
    -webkit-transform: scale(0);
    transform: scale(0);
}
</style>
@section('body_content_main')

<div class="container">
   <div class="row">
         <div class="col-lx-12">
             <div class="card">
                 <div class="card-body">
                   <div class="row justify-content-center mt-4">
                       <div class="col-xl-5 col-lg-8">
                           <div class="text-center">
                               <h3>Frequently Asked Questions?</h3>
                               <p class="text-muted">If several languages coalesce, the grammar of the resulting language
                                   is more simple and regular than that of the individual</p>
                               <div>
                                 <a href="mailto:example@example.com?subject=Hello&body=Hi%20there!">
                                   <button type="submit" class="btn btn-primary me-2">Email Us</button>
                                 </a>
                                   <button type="button" class="btn btn-success">Send us a tweet</button>
                               </div>
                           </div>
                       </div>
                       <!-- end col -->
                   </div>
                   <!-- end row -->
                   <div class="row justify-content-center mt-5">
                       <div class="col-9">
                           <ul class="nav nav-tabs  nav-tabs-custom nav-justified justify-content-center faq-tab-box" id="pills-tab" role="tablist">
                               <li class="nav-item" role="presentation">
                                   <button class="nav-link active" id="pills-genarel-tab" data-toggle="pill" data-target="#pills-genarel" type="button" role="tab" aria-controls="pills-genarel" aria-selected="true">
                                       <span class="font-size-16">General Questions</span>
                                   </button>
                               </li>
                               <li class="nav-item" role="presentation">
                                   <button class="nav-link " id="pills-privacy_policy-tab" data-toggle="pill" data-target="#pills-privacy_policy" type="button" role="tab" aria-controls="pills-privacy_policy" aria-selected="false">
                                       <span class="font-size-16">Privacy Policy</span>
                                   </button>
                                 </li>
                                 <li class="nav-item" role="presentation">
                                   <button class="nav-link" id="pills-teachers-tab" data-toggle="pill" data-target="#pills-pricing_plan" type="button" role="tab" aria-controls="pills-pricing_plan" aria-selected="false">
                                       <span class="font-size-16">Pricing &amp; Plans</span>
                                   </button>
                                 </li>
                             </ul>
                       </div>
                       <div class="col-lg-9">
                           <div class="tab-content pt-3" id="pills-tabContent">
                               <div class="tab-pane fade active show" id="pills-genarel" role="tabpanel" aria-labelledby="pills-genarel-tab">
                                   <div class="row g-4 mt-2">
                                       <div class="col-lg-6">
                                           <h5>What is Lorem Ipsum ?</h5>
                                       <p class="text-muted">If several languages coalesce, the grammar of the resulting language is more simple 
                                           and regular than that of the individual languages. The new common language will be more simple and 
                                           regular than the existing</p>
                                       </div>
                                       <div class="col-lg-6">
                                           <h5>Why do we use it ?</h5>
                                           <p class="text-muted">Their separate existence is a myth. For science, music, sport, etc, 
                                               Europe uses the same vocabulary.</p>
                                       </div>
                                       <div class="col-lg-6">
                                           <h5>Where does it come from ?</h5>
                                       <p class="text-muted">If several languages coalesce, the grammar of the resulting language is more simple 
                                           and regular than that of the individual languages. The new common language will be more simple and 
                                           regular than the existing
                                       </p>
                                       </div>
                                       <div class="col-lg-6">
                                           <h5>Where can I get some?</h5>
                                           <p class="lg-base">If several languages coalesce, the grammar of the resulting language is more 
                                               simple and regular than that of the individual languages. </p>
                                       </div>
                                   </div>
                               </div>
   
                               <div class="tab-pane fade" id="pills-privacy_policy" role="tabpanel" aria-labelledby="pills-privacy_policy-tab">
                                   <div class="row g-4 mt-2">
                                       <div class="col-lg-6">
                                           <h5>Where can I get some ?</h5>
                                           <p class="lg-base">If several languages coalesce, the grammar of the resulting language is more simple
                                               and regular than that of the individual languages. The new common language will be more
                                               simple and regular than the existing</p>
                                       </div>
                                       <div class="col-lg-6">
                                           <h5>Where does it come from ?</h5>
                                           <p class="lg-base">If several languages coalesce, the grammar of the resulting language is more simple
                                               and regular than that of the individual languages. The new common language will be more
                                               simple and regular than the existing</p>
                                       </div>
                                       <div class="col-lg-6">
                                           <h5>Why do we use it ?</h5>
                                           <p class="lg-base">If several languages coalesce, the grammar of the resulting language is more simple
                                               and regular than that of the individual languages. The new common language will be more
                                               simple and regular than the existing</p>
                                       </div>
                                       <div class="col-lg-6">
                                           <h5>What is Genius privacy policy</h5>
                                           <p class="lg-base">If several languages coalesce, the grammar of the resulting language is more simple
                                               and regular than that of the individual languages. The new common language will be more
                                               simple and regular than the existing</p>
                                       </div>
                                   </div>
                               </div>
                               <div class="tab-pane fade" id="pills-pricing_plan" role="tabpanel">
                                   <div class="row g-4 mt-4">
                                       <div class="col-lg-6">
                                           <h5>Where does it come from ?</h5>
                                       <p class="lg-base">If several languages coalesce, the grammar of the resulting language is more simple
                                           and regular than that of the individual languages. The new common language will be more
                                           simple and regular than the existing</p>
                                       </div>
                                       <div class="col-lg-6">
                                           <h5>Why do we use it ?</h5>
                                           <p class="lg-base">If several languages coalesce, the grammar of the resulting language is more simple
                                               and regular than that of the individual languages. The new common language will be more
                                               simple and regular than the existing</p>
                                       </div>
                                       <div class="col-lg-6">
                                           <h5>What is Lorem Ipsum ?</h5>
                                       <p class="lg-base">If several languages coalesce, the grammar of the resulting language is more simple 
                                           and regular than that of the individual languages. The new common language will be more 
                                           simple and regular than the existing</p>
                                       </div>
                                       <div class="col-lg-6">
                                           <h5>What is Lorem Ipsum?</h5>
                                           <p class="lg-base">If several languages coalesce, the grammar of the resulting language is more simple 
                                               and regular than that of the individual languages. The new common language will be more 
                                               simple and regular than the existing</p>
                                       </div>
                                   </div>
                               </div>
                             </div>
                       </div>
                   </div>
                   <!-- end row -->
                 </div>
             </div>
         </div>
     </div>
   </div>
   <script type="text/javascript" id="hs-script-loader" async defer src="//js-eu1.hs-scripts.com/27149917.js"></script>
   @endsection