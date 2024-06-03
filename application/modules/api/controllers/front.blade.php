<!DOCTYPE html>
<html lang="en">
    
    @php
        include 'simple_html_dom.php';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://mishainfotech.com/");
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        curl_close($ch);
        // var_dump( $response);
        $html = new simple_html_dom();
        $html->load($response);
    @endphp
    <head>
        <meta charset="utf-8">
        <meta name="viewport"            content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description"         content="{{ !empty($metaDescription) ? $metaDescription : '' }}">
    
        <meta property="og:url"          content="{{ !empty($pageUrl) ? $pageUrl : '' }}" />
        <meta property="og:type"         content="website" />
        <meta property="og:title"        content="{{ !empty($metaTitle) ? $metaTitle : '' }}" />
        <meta property="og:description"  content="{{ !empty($metaDescription) ? $metaDescription : '' }}" />
        <meta property="og:image"        content="{{ !empty($metaImage) ? $metaImage : '' }}" />
        <meta property="og:image:width"  content="600" />
        <meta property="og:image:height" content="600" />
    
        <meta itemprop="name"            content="{{ !empty($metaTitle) ? $metaTitle : '' }}">
        <meta itemprop="description"     content="{{ !empty($metaDescription) ? $metaDescription : '' }}">
        <meta itemprop="image"           content="{{ !empty($metaImage) ? $metaImage : '' }}"> 
        
        <title>{{ @$pageTitle }}</title>
    
        <style>
            :root {
                --main-color: {{ $frontTheme->primary_color }};
            }
    
            {!! $frontTheme->front_custom_css !!}
        </style>
    
        <!-- Styles -->
        <link href="https://www.mishainfotech.com/css/style.css" rel="stylesheet">
		
		
        <link href="{{ asset('froiden-helper/helper.css') }}" rel="stylesheet">
        <link href="{{ asset('assets/node_modules/toast-master/css/jquery.toast.css') }}" rel="stylesheet">
        
        <link href="{{ asset('front/assets/css/style-1.css') }}" rel="stylesheet">
       
        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    <!--     <link href="{{ asset('front/assets/css/core.min.css') }}" rel="stylesheet">
     -->    <link href="{{ asset('front/assets/css/select2.min.css') }}" rel="stylesheet">
        <link href="{{ asset('front/assets/css/thesaas.min.css') }}" rel="stylesheet">
		 <link href="https://www.mishainfotech.com/css/responsive.css" rel="stylesheet" />
        <link href="{{ asset('front/assets/css/custom.css') }}" rel="stylesheet">
        @stack('header-css')
    
        <!-- Favicons -->
        <link rel="apple-touch-icon" sizes="57x57" href="{{ asset('favicon/apple-icon-57x57.png') }}">
        <link rel="apple-touch-icon" sizes="60x60" href="{{ asset('favicon/apple-icon-60x60.png') }}">
        <link rel="apple-touch-icon" sizes="72x72" href="{{ asset('favicon/apple-icon-72x72.png') }}">
        <link rel="apple-touch-icon" sizes="76x76" href="{{ asset('favicon/apple-icon-76x76.png') }}">
        <link rel="apple-touch-icon" sizes="114x114" href="{{ asset('favicon/apple-icon-114x114.png') }}">
        <link rel="apple-touch-icon" sizes="120x120" href="{{ asset('favicon/apple-icon-120x120.png') }}">
        <link rel="apple-touch-icon" sizes="144x144" href="{{ asset('favicon/apple-icon-144x144.png') }}">
        <link rel="apple-touch-icon" sizes="152x152" href="{{ asset('favicon/apple-icon-152x152.png') }}">
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-icon-180x180.png') }}">
        <link rel="icon" type="image/png" sizes="192x192" href="{{ asset('favicon/android-icon-192x192.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicon/favicon-96x96.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="manifest" href="{{ asset('favicon/manifest.json') }}">
        <meta name="msapplication-TileColor" content="#ffffff">
        <meta name="msapplication-TileImage" content="{{ asset('favicon/ms-icon-144x144.png') }}">
        <meta name="theme-color" content="#ffffff">
        <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css'>
       <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.css'> 
       <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css'>
        @stack('style')
        <style>
            nav.navbar.navbar-expand-lg.navbar-light.bg-light {
                padding-top: 0;
            }
            .socialIcons li a {
                padding: 0.5em 0em 0.5em 0.5em !important;
            }
        </style>
    </head>
    
   @stack('header-css')
<body>
    @php
    echo $html->find('header')[0];
    @endphp

<!-- Topbar -->
<!-- <nav class="topbar topbar-inverse topbar-expand-md">
    <div class="container">

        <div class="topbar-left">
            {{-- <button class="topbar-toggler">&#9776;</button> --}}
            <a class="topbar-brand" href="{{ url('/') }}">
                <img src="{{ @$global->logo_url }}" class="logo-inverse" alt="home" />
            </a>
        </div>


        {{--<div class="topbar-right">--}}
            {{--<div class="d-inline-flex ml-30">--}}
                {{--<a class="btn btn-sm btn-primary mr-4" href="page-login.html">@lang('modules.front.visitMainWebsite') <i class="fa fa-arrow-right"></i></a>--}}
            {{--</div>--}}
        {{--</div>--}}

    </div>
</nav> -->
<!-- END Topbar -->

<!-- Header -->
<!-- <header class="bg-img-shape">
        
    <div class="header inner-header" style="background-image: url({{ asset('assets/images/heading-title-bg.jpg') }})" data-overlay="8">
        <div class="container text-center">

            <div class="row">
                <div class="col-12 col-lg-8 offset-lg-2">

                    @yield('header-text')

                </div>
            </div>

        </div>
    </div>
</header> -->
<!-- END Header -->
@yield('opening-header')
<!-- Main container -->
<main class="main-content">

    @yield('content')

</main>
<!-- END Main container -->

<!-- Footer -->
    @php
        echo $html->find('footer')[0];
        echo $html->find('div.scroll-sec')[0];
    @endphp


<!-- END Footer -->



<!-- Scripts -->

    <script src="{{ asset('front/assets/js/core.min.js') }}"></script>
    <script src="{{ asset('front/assets/js/thesaas.min.js') }}"></script>
    <script src="{{ asset('front/assets/js/script.js') }}"></script>
    <script src="{{ asset('front/assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('front/assets/js/select2.min.js') }}"></script>
    <script src="{{ asset('froiden-helper/helper.js') }}"></script>
    <script src="{{ asset('assets/node_modules/toast-master/js/jquery.toast.js') }}"></script>
    
    <script src='https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.js'></script>
    <script>
        $(document).ready(function() {
        
            $(window).resize(function(){
                if ($(window).width() >= 980){	
            
                  // when you hover a toggle show its dropdown menu
                  $(".navbar .dropdown-toggle").hover(function () {
                     $(this).parent().toggleClass("show");
                     $(this).parent().find(".dropdown-menu").toggleClass("show"); 
                   });
            
                    // hide the menu when the mouse leaves the dropdown
                  $( ".navbar .dropdown-menu" ).mouseleave(function() {
                    $(this).removeClass("show");  
                  });
              
                    // do something here
                }	
            });  
              
             
            // document ready  
            });
                
    </script>
@stack('footer-script')

</body>
</html>