
@extends('layouts.tabler')

<style>
   body{
    background-color:#eee;
    margin-top:20px;
}

/*Accordinion*/
   h1 {
       text-align: center;
   }

   /* Sets the width for the accordion. Sets the margin to 90px on the top and bottom and auto to the left and right */

   .accordion {
       /*width: 800px;*/
       margin: 90px auto;
       color: black;
       background-color: white;
       padding: 45px 45px;
   }
   .accordion .container {
       position: relative;
       margin: 10px 10px;
   }

   /* Positions the labels relative to the .container. Adds padding to the top and bottom and increases font size. Also makes its cursor a pointer */

   .accordion .label {
       position: relative;
       padding: 10px 0;
       font-size: 30px;
       color: black;
       cursor: pointer;
   }
   /* Positions the plus sign 5px from the right. Centers it using the transform property. */

   .accordion .label::before {
       content: '+';
       color: black;
       position: absolute;
       top: 50%;
       right: -5px;
       font-size: 30px;
       transform: translateY(-50%);
   }

   /* Hides the content (height: 0), decreases font size, justifies text and adds transition */

   .accordion .content {
       position: relative;
       background: white;
       height: 0;
       font-size: 20px;
       text-align: justify;
       /*width: 780px;*/
       overflow: hidden;
       transition: 0.5s;
   }

   /* Adds a horizontal line between the contents */

   .accordion hr {
       width: 100;
       margin-left: 0;
       border: 1px solid grey;
   }
   /* Unhides the content part when active. Sets the height */

   .accordion .container.active .content {
       height: 150px;
       overflow: scroll;
   }

   /* Changes from plus sign to negative sign once active */

   .accordion .container.active .label::before {
       content: '-';
       font-size: 30px;
   }
</style>
@section('body_content_main')

<div class="accordion-body">
    <div class="accordion">
{{--        <h1>Frequently Asked Questions</h1>--}}
        <hr>
        <div class="container">
            <div class="label">About Dorcas ?</div>
            <div class="content">
                Dorcas E Commerce is an all in one  solution that does not just help your business manage interactions with customers but also helps you manage other business activities such as sales.
                    <br><br>
                The Suite is a one-stop technology solutions destination for businesses. It provides access to customer, sales, e-commerce, HR and finance tools in a single online destination without the need to install any software.

            </div>
        </div>
        <hr>
        <div class="container">
            <div class="label">How much does Dorcas ECommerce cost?</div>
            <div class="content">
                Dorcas E Commerce application is Free , However we  offer flexible support  pricing plans that can be customized to suit your business needs.
                You can contact our support  team to discuss pricing options.
            </div>
        </div>
        <hr>
        <div class="container">
            <div class="label">Why should I choose Dorcas Ecommerce Suite ?</div>
            <div class="content">
                <ul>
                    <li>Dorcas E commerce suite is free and easily accessible</li>
                    <li>Dorcas E Commerce offers a wide range of tools and functionalities to help you manage your interactions with customers more effectively.</li>
                    <li>It is a cloud-based solution, which means you can access your data and tools from anywhere, at any time, as long as you have an internet connection.</li>
                    <li>Dorcas E Commerce offers HR and finance tools that can help you manage your business operations more efficiently.</li>
                    <li>It is a one-stop technology solutions destination for businesses, providing access to customer, sales, e-commerce, HR, and finance tools in a single online destination.</li>
                    <li>Dorcas E Commerce can help you streamline your business operations, improve customer satisfaction, and scale your business quickly and easily.</li>
                    <li>With Dorcas E Commerce, you don't need to install any software on your computer or server, which saves you infrastructure and IT costs.</li>

                </ul>


            </div>
        </div>
        <hr>
        <div class="container">
            <div class="label">What kind of support does Dorcas E Commerce offer ?</div>
            <div class="content">
                Dorcas E Commerce offers a range of support options, including email, phone, and chat support.
                We also provide user guides and tutorials to help you get started with the platform.
            </div>
        </div>
        <hr>
        <div class="container">
            <div class="label">How can I Contact Support ?</div>
            <div class="content">
                To contact support click on the support tab to have access to all our support channels.
            </div>
        </div>
        <hr>
        <div class="container">
            <div class="label">What are the benefits of using Dorcas E Commerce?</div>
            <div class="content">
                Some benefits of using Dorcas E Commerce include streamlining business operations, improving customer satisfaction, and having access to a wide range of tools and functionalities without the need to install any software.
            </div>
        </div>
        <hr>
        <div class="container">
            <div class="label">Is Dorcas E Commerce secure?</div>
            <div class="content">
                Yes, Dorcas E Commerce uses industry-standard security protocols to protect your data and information. They also provide regular updates and maintenance to ensure the platform remains secure.
            </div>
        </div>
        <hr>
    </div>
</div>
<script>
    const accordion = document.getElementsByClassName('container');

    for (var i=0; i<accordion.length; i++) {
        accordion[i].addEventListener('click', function () {
            this.classList.toggle('active')
        })
    }
</script>
   <script type="text/javascript" id="hs-script-loader" async defer src="//js-eu1.hs-scripts.com/27149917.js"></script>
   @endsection