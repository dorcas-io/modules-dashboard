
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
{{--                               <p class="text-muted">If several languages coalesce, the grammar of the resulting language--}}
{{--                                   is more simple and regular than that of the individual</p>--}}
{{--                               <div>--}}
{{--                                 <a href="mailto:example@example.com?subject=Hello&body=Hi%20there!">--}}
{{--                                   <button type="submit" class="btn btn-primary me-2">Email Us</button>--}}
{{--                                 </a>--}}
{{--                                   <button type="button" class="btn btn-success">Send us a tweet</button>--}}
{{--                               </div>--}}
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
                                       <span class="font-size-16">Website FAQs</span>
                                   </button>
                               </li>
                               <li class="nav-item" role="presentation">
                                   <button class="nav-link " id="pills-privacy_policy-tab" data-toggle="pill" data-target="#pills-privacy_policy" type="button" role="tab" aria-controls="pills-privacy_policy" aria-selected="false">
                                       <span class="font-size-16">IN APP FAQs </span>
                                   </button>
                                 </li>
                                 <li class="nav-item" role="presentation">
                                   <button class="nav-link" id="pills-teachers-tab" data-toggle="pill" data-target="#pills-pricing_plan" type="button" role="tab" aria-controls="pills-pricing_plan" aria-selected="false">
                                       <span class="font-size-16">Manage Product </span>
                                   </button>
                                 </li>
                             </ul>
                       </div>
                       <div class="col-lg-9">
                           <div class="tab-content pt-3" id="pills-tabContent">
                               <div class="tab-pane fade active show" id="pills-genarel" role="tabpanel" aria-labelledby="pills-genarel-tab">
                                   <div class="row g-4 mt-2">
                                       <div class="col-lg-6">
                                           <h5>What is Dorcas all about?</h5>
                                       <p>
                                           Dorcas Ecommerce suite is a Business Management software solution that does not just help your business manage interactions with customers but also helps you manage other business activities such as sales.
                                           The Business Dashboard on the Application contains easy access to key Modules such as Customers, Sales, Addons and Settings.  At any time, within a Module, you can click the Get Support  Button to either get a quick user-interface tour or launch an assistant with useful options.

                                       </p>
                                       </div>
                                       <div class="col-lg-6">
                                           <h5>
                                               How much does Dorcas ECommerce cost?
                                           </h5>
                                           <p>
                                               Dorcas E Commerce application is Free , However we  offer flexible support  pricing plans that can be customized to suit your business needs.
                                               You can contact our support  team to discuss pricing options.
                                           </p>
                                       </div>
                                       <div class="col-lg-6">
                                           <h5>What kind of businesses can use Dorcas E Commerce?</h5>
                                       <p>
                                           Dorcas E Commerce can be used by businesses of all sizes and across a variety of industries,
                                           from startups to established enterprises.
                                       </p>
                                       </div>
                                       <div class="col-lg-6">
                                           <h5>Can I Integrate other apps with my website?</h5>
                                           <p class="lg-base">Yes you can integrate your other apps with your website
                                               All you need to do is to contact our support team via email on support@dorcas.io </p>
                                       </div>
                                       <div class="col-lg-6">
                                           <h5>What kind of businesses can use Dorcas E Commerce?</h5>
                                           <p>
                                               Dorcas E Commerce can be used by businesses of all sizes and across a variety of industries,
                                               from startups to established enterprises.
                                           </p>
                                       </div>
                                       <div class="col-lg-6">
                                           <h5>Is Dorcas Available only in Nigeria </h5>
                                           <p class="lg-base">No,Dorcas is available for use outside Nigeria , all you need to do is to contact our support to get you started.
                                               <ul>
                                               <li> Click the get started button</li>
                                               <li>And fill the required information</li>
                                               <li>Our support team will get back to within 24hrs to get you on Dorcas</li>
                                              </ul>
                                           </p>
                                       </div>
                                       <div class="col-lg-6">
                                           <h5>Can I lose all my data/informations</h5>
                                           <p class="lg-base">Your data on Dorcas is completely cloud - based, which means your data cannot get lost.
                                               It also means that you do not have to worry about maintaining software or servers. </p>
                                       </div>
                                       <div class="col-lg-6">
                                           <h5>What device is Compatible with Dorcas?</h5>
                                           <p>
                                               You can use Dorcas on your Android Mobile Device and Computers
                                           </p>
                                       </div>
                                       <div class="col-lg-6">
                                           <h5>Is Dorcas Suite Free ?</h5>
                                           <p class="lg-base">Dorcas Ecommerce Suite  is free however you may need to pay for specific
                                               features tailored to your business as well as support packages  </p>
                                       </div>
                                       <div class="col-lg-6">
                                           <h5>Am I paying to get my online store set up?</h5>
                                           <p >
                                               No, You do not need to pay to get your business all set up on Dorcas Ecommerce suite all you need to do is
                                               download the app on playstore  and follow the following Process
                                               <ul>
                                               <li> Click the get started button </li>
                                               <li>And fill the required information</li>
                                               <li>Our support team will get back to within 24hrs to get you on Dorcas</li>
                                               </ul>
                                           </p>
                                       </div>
                                       <div class="col-lg-6">
                                           <h5>What kind of support does Dorcas E Commerce offer?</h5>
                                           <p class="lg-base">
                                               Dorcas E Commerce offers a range of support options, including email, phone, and chat support.
                                               We also provide user guides and tutorials to help you get started with the platform.
                                           </p>
                                       </div>
                                       <div class="col-lg-6">
                                           <h5>How do I contact support?</h5>
                                           <p class="lg-base">
                                               To contact support click on the support tab to have access to all our support channels
                                           </p>
                                       </div>
                                       <div class="col-lg-6">
                                           <h5>What are the benefits of using Dorcas E Commerce?</h5>
                                           <p class="lg-base">
                                               Some benefits of using Dorcas E Commerce include streamlining business operations, improving customer satisfaction,
                                               and having access to a wide range of tools and functionalities without the need to install any software.
                                           </p>
                                       </div>
                                       <div class="col-lg-6">
                                           <h5>Is Dorcas E Commerce secure?</h5>
                                           <p class="lg-base">
                                               Yes, Dorcas E Commerce uses industry-standard security protocols to protect your data and information.
                                               They also provide regular updates and maintenance to ensure the platform remains secure.
                                           </p>
                                       </div>
                                   </div>
                               </div>
   
                               <div class="tab-pane fade" id="pills-privacy_policy" role="tabpanel" aria-labelledby="pills-privacy_policy-tab">
                                   <div class="row g-4 mt-2">
                                       <div class="col-lg-6">
                                           <h5>About Dorcas</h5>
                                           <p class="lg-base">
                                               Dorcas E Commerce is an all in one  solution that does not just help your business manage
                                               interactions with customers but also helps you manage other business activities such as sales.
                                                    <br><br>
                                               The Suite is a one-stop technology solutions destination for businesses. It provides access
                                               to customer, sales, e-commerce, HR and finance tools in a single online destination without the need
                                               to install any software.

                                           </p>
                                       </div>
                                       <div class="col-lg-6">
                                           <h5>How much does Dorcas ECommerce cost?</h5>
                                           <p class="lg-base">
                                               Dorcas E Commerce application is Free , However we  offer flexible support  pricing plans that can be customized to suit your business needs.
                                               You can contact our support  team to discuss pricing options.
                                           </p>
                                       </div>
                                       <div class="col-lg-6">
                                           <h5>Why should I choose Dorcas Ecommerce Suite </h5>
                                           <p class="lg-base">
                                                <ul>
                                               <li>Dorcas E commerce suite is free and easily accessible</li>
                                               <li>Dorcas E Commerce offers a wide range of tools and functionalities to help you manage your interactions with customers more effectively.</li>
                                               <li>It is a cloud-based solution, which means you can access your data and tools from anywhere, at any time, as long as you have an internet connection.</li>
                                               <li>Dorcas E Commerce offers HR and finance tools that can help you manage your business operations more efficiently.</li>
                                               <li>It is a one-stop technology solutions destination for businesses, providing access to customer, sales, e-commerce, HR, and finance tools in a single online destination.</li>
                                               <li>Dorcas E Commerce can help you streamline your business operations, improve customer satisfaction, and scale your business quickly and easily.</li>
                                               <li> With Dorcas E Commerce, you don't need to install any software on your computer or server, which saves you infrastructure and IT costs.</li>

                                               </ul>
                                           </p>
                                       </div>
                                       <div class="col-lg-6">
                                           <h5>What kind of support does Dorcas E Commerce offer?</h5>
                                           <p class="lg-base">
                                               Dorcas E Commerce offers a range of support options, including email, phone, and chat support.
                                               We also provide user guides and tutorials to help you get started with the platform.
                                           </p>
                                       </div>
                                       <div class="col-lg-6">
                                           <h5>How can I Contact Support ?</h5>
                                           <p class="lg-base">
                                               To contact support click on the support tab to have access to all our support channels.
                                           </p>
                                       </div>
                                       <div class="col-lg-6">
                                           <h5>What are the benefits of using Dorcas E Commerce?</h5>
                                           <p class="lg-base">
                                               Some benefits of using Dorcas E Commerce include streamlining business operations, improving customer satisfaction,
                                               and having access to a wide range of tools and functionalities without the need to install any software.
                                           </p>
                                       </div>
                                       <div class="col-lg-6">
                                           <h5>Is Dorcas E Commerce secure?</h5>
                                           <p class="lg-base">
                                               Yes, Dorcas E Commerce uses industry-standard security protocols to protect your data and information.
                                               They also provide regular updates and maintenance to ensure the platform remains secure.
                                           </p>
                                       </div>
                                   </div>
                               </div>
                               <div class="tab-pane fade" id="pills-pricing_plan" role="tabpanel">
                                   <div class="row g-4 mt-4">
                                       <div class="col-lg-6">
                                           <h5>How can I add product categories ?</h5>
                                       <p class="lg-base">
                                           To do this :
                                           <ul>
                                               <li>Click on the Sales Button on the top bar;</li>
                                               <li>Click on Categories from the drop down list;</li>
                                               <li>Click  the Add Product Category Button;</li>
                                               <li>Fill in the required field;</li>
                                               <li>Click the Save Button. </li>
                                           </ul>


                                       </p>
                                       </div>
                                       <div class="col-lg-6">
                                           <h5>How  Can I categorize my product?</h5>
                                           <p class="lg-base">
                                               To categorize your products do this:
                                               <ul>
                                               <li>Click the Sales Button on the top bar;</li>
                                               <li>Click Product;</li>
                                               <li>Click the View Button next to the product you will like to add to a Category;</li>
                                               <li>From the displayed screen choose the category(ies) you will like to add the product to (you can select one or more </li>
                                               <li>Click the Add Categories Button.</li>

                                           </ul>

                                           </p>
                                       </div>
                                       <div class="col-lg-6">
                                           <h5>Can i upload Product images ?</h5>
                                       <p class="lg-base">
                                           Yes , You canTo do this:
                                           <ul>
                                               <li>Click the Sales Button on the top bar;</li>
                                               <li>Click Product;</li>
                                               <li>Click the View Button next to the product you will like to upload its image</li>
                                               <li>Click the Add an image button</li>
                                               <li>Click the Browse button to select an image from your device</li>
                                               <li>Click upload once you selected you preferred image</li>
                                           </ul>


                                       </p>
                                       </div>
                                       <div class="col-lg-6">
                                           <h5>Can i record all my offline orders ?</h5>
                                           <p class="lg-base">
                                               Yes  you can record your orders on the Dorcas Ecommerce suite
                                               <ul>
                                               <li>To do this:</li>
                                               <li>Click on the Sales Button on the top bar;</li>
                                               <li>Click on Order and Invoice;</li>
                                               <li>Click on the Add Invoice Button at the upper left side of the displayed screen;</li>
                                               <li>Input required information</li>
                                               </ul>


                                           </p>
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