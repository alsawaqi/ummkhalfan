<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">

        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">



        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
        <meta name="description" content="welcome to um khalfan kitchen">
        <meta name="author" content="Yusr Recruitment">
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta property="og:title" content="umkhalfan">
        <meta property="og:description" content="welcome to um khalfan kitchen">
        <meta property="og:url" content="{{ Request::root()}}">
        <meta property="og:image" content="{{ Request::root()}}/logo.png">
        <meta property="og:type" content="website">
        <meta name="twitter:card" content="yusr">
        <meta name="twitter:site" content="@YourTwitterHandle">
        <meta name="twitter:title" content="umkhalfan">
        <meta name="twitter:description" content="welcome to um khalfan kitchen">
        <meta name="twitter:image" content="{{ Request::root()}}/logo.png">
        <meta name="robots" content="index, follow">
        <link rel="alternate" href="{{ Request::root()}}" hreflang="en-us">
        <link rel="canonical" href="{{ Request::root()}}">



    <link rel="shortcut icon" href="{{ Request::root()}}/logo.png">


        <!-- Google Web Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Raleway:wght@600;800&display=swap" rel="stylesheet">

        <!-- Icon Font Stylesheet -->
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css"/>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

        <!-- Libraries Stylesheet -->
        <link href="{{ asset('lib/lightbox/css/lightbox.min.css')}}" rel="stylesheet">
        <link href="{{ asset('lib/owlcarousel/assets/owl.carousel.min.css')}}" rel="stylesheet">


        <!-- Customized Bootstrap Stylesheet -->
        <link href="{{ asset('css/bootstrap.min.css')}}" rel="stylesheet">

        <!-- Template Stylesheet -->
        <link href="{{ asset('css/style.css')}}" rel="stylesheet">

        <style>
            .loading{
                    width: 50px;
                    height: 50px;
            }

            .no-js #loader { display: none;  }
            .js #loader {
                 display: block;
                 position: absolute;
                 left: 100px;
                 top: 0;
                }
            .se-pre-con {
                position: fixed;
                left: 0px;
                top: 0px;
                width: 100%;
                height: 100%;
                z-index: 9999;
                background: url(https://umkhalfan.com/logo.png) center no-repeat #fff;
            }
           </style>

           <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.5.2/jquery.min.js"></script>
           <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.2/modernizr.js"></script>
            <script>
                  $(window).load(function() {
                     $(".se-pre-con").fadeOut("slow");
                 });
            </script>

           @vite(['resources/js/app.js'])
</head>

<body>
