<?php if (!isset($pageTitle)) { $pageTitle = 'ARPANN TOWNSHIP- Saharanpur Uttar Pradesh'; } ?>
<!DOCTYPE html>
<html lang="en">


<head>
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    <link rel="icon" href="images/icon.webp" type="image/webp">
    <meta content="text/html;charset=utf-8" http-equiv="Content-Type">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Residem" name="description">
    <meta content="" name="keywords">
    <meta content="" name="author">
    <!-- CSS Files
    ================================================== -->
    <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" id="bootstrap">
    <link href="css/plugins.css" rel="stylesheet" type="text/css">
    <link href="css/swiper.css" rel="stylesheet" type="text/css">
    <link href="css/style.css" rel="stylesheet" type="text/css">
    <link href="css/coloring.css" rel="stylesheet" type="text/css">
    <!-- custom-css -->
    <link href="css/swiper-custom-1.css" rel="stylesheet" type="text/css">
    <!-- color scheme -->
    <link id="colors" href="css/colors/scheme-01.css" rel="stylesheet" type="text/css">

    <style>
        .gradient-edge-top {
            display: none;
        }

        header.header-mobile {
            position: fixed !important;
            background: #103c3b;
            top: 0;
            padding-top: 10px;
            padding-bottom: 10px;
        }

        .logo-premium {
            display: inline-block;
            margin-top: 8px;
            margin-bottom: 8px;
        }

        .logo-premium img {
            display: block;
            width: 120px;
            padding: 10px 16px;
            background: #103c3b;
            /* border: 1.5px solid #cda45e; */
            border-radius: 6px;
            /* animation: logoGlow 2.5s ease-in-out infinite; */
        }

        @keyframes logoGlow {

            0%,
            100% {
                box-shadow: 0 0 4px rgba(205, 164, 94, 0.35);
            }

            50% {
                box-shadow: 0 0 14px rgba(205, 164, 94, 0.85);
            }
        }

        @media (max-width: 992px) {
            header.logo-center .container-fluid {
                padding-left: 14px;
                padding-right: 14px;
            }

            header.logo-center .row,
            header.logo-center .col-lg-12 {
                margin-left: 0;
                margin-right: 0;
                padding-left: 0;
                padding-right: 0;
            }

            header.logo-center .col-center {
                order: -1;
                justify-content: flex-start;
                flex-grow: 0;
            }

            header.logo-center .col-start {
                flex-grow: 0;
            }

            header.logo-center #mainmenu {
                left: 0 !important;
            }

            .logo-premium img {
                width: 85px;
                padding: 6px 10px;
            }
        }

        .hero-info-bar {
            display: inline-flex;
            align-items: center;
            gap: 22px;
            background: rgba(16, 60, 59, 0.35);
            backdrop-filter: blur(8px);
            -webkit-backdrop-filter: blur(8px);
            border: 1px solid rgba(205, 164, 94, 0.4);
            padding: 12px 24px;
            border-radius: 50px;
        }

        .hero-address {
            display: flex;
            align-items: center;
            gap: 8px;
            margin: 0;
            letter-spacing: 0.3px;
        }

        .hero-address svg {
            flex-shrink: 0;
        }

        .hero-map-btn {
            display: inline-flex !important;
            align-items: center;
            border: 1.5px solid #cda45e !important;
            color: #f0d9a8 !important;
            background: transparent !important;
            border-radius: 30px !important;
            padding: 8px 24px !important;
            letter-spacing: 1px;
            transition: all .35s ease;
        }

        .hero-map-btn:hover {
            background: #cda45e !important;
            color: #103c3b !important;
        }

        .hero-info-bar {
            flex-wrap: wrap;
            row-gap: 12px;
        }

        .hero-address-short {
            display: none;
        }

        @media (max-width: 767px) {
            .hero-section.mh-800 {
                min-height: 100svh;
            }

            .hero-content-wrap {
                bottom: 80px !important;
            }

            .hero-title {
                font-size: 8vw !important;
            }

            .hero-info-bar {
                flex-wrap: wrap;
                gap: 10px;
                padding: 10px 16px;
                border-radius: 20px;
            }

            .hero-address {
                font-size: 13px;
                flex-basis: 100%;
            }

            .hero-address-full {
                display: none;
            }

            .hero-address-short {
                display: inline;
            }

            .hero-address svg {
                width: 15px;
                height: 15px;
            }

            .hero-map-btn {
                padding: 6px 16px !important;
                font-size: 12px;
            }

            .icon_pin.fs-60,
            .icon_phone.fs-60 {
                font-size: 34px !important;
            }

            #wrapper {
                padding-bottom: 62px;
            }
        }

        .mobile-cta-bar {
            display: none;
        }

        @media (max-width: 767px) {
            .mobile-cta-bar {
                display: flex;
                position: fixed;
                left: 0;
                bottom: 0;
                width: 100%;
                z-index: 10001;
                background: #103c3b;
                border-top: 1px solid rgba(205, 164, 94, 0.4);
            }

            .mobile-cta-bar a {
                flex: 1;
                display: flex;
                flex-direction: column;
                align-items: center;
                justify-content: center;
                gap: 4px;
                padding: 10px 4px;
                font-size: 11px;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: .2px;
                color: #fff;
                text-decoration: none;
                border-right: 1px solid rgba(255, 255, 255, 0.12);
                white-space: nowrap;
            }

            .mobile-cta-bar a:last-child {
                border-right: none;
            }

            .mobile-cta-bar a.mobile-cta-call {
                background: #cda45e;
                color: #103c3b;
            }

            .mobile-cta-bar a.mobile-cta-whatsapp {
                background: #103c3b;
                color: #cda45e;
            }

            .mobile-cta-bar a.mobile-cta-map {
                background: #cda45e;
                color: #103c3b;
            }

            .mobile-cta-bar a.mobile-cta-brochure {
                background: #103c3b;
                color: #cda45e;
            }

            .mobile-cta-bar svg {
                flex-shrink: 0;
                width: 16px;
                height: 16px;
            }
        }

        .stat-card {
            background: #fff;
            border-radius: 16px;
            padding: 36px 20px 28px;
            box-shadow: 0 10px 30px rgba(16, 60, 59, 0.08);
            border-top: 3px solid #cda45e;
            transition: transform .4s ease, box-shadow .4s ease;
            height: 100%;
        }

        .stat-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 45px rgba(205, 164, 94, 0.28);
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            margin: 0 auto 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: rgba(205, 164, 94, 0.12);
            animation: statIconPulse 2.6s ease-in-out infinite;
        }

        @keyframes statIconPulse {

            0%,
            100% {
                box-shadow: 0 0 0 0 rgba(205, 164, 94, 0.35);
            }

            50% {
                box-shadow: 0 0 0 10px rgba(205, 164, 94, 0);
            }
        }

        .stat-card h3 {
            color: #103c3b;
            font-weight: 700;
            font-size: clamp(24px, 2.8vw, 44px);
            white-space: nowrap;
        }

        .stat-label {
            color: #7a7a7a;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            font-size: 13px;
            margin-top: 6px;
        }

        .amenity-list {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            flex-wrap: wrap;
            gap: 14px;
        }

        .amenity-list li {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 11px 20px 11px 12px;
            border-radius: 50px;
            border: 1px solid rgba(205, 164, 94, 0.45);
            background: rgba(205, 164, 94, 0.07);
            transition: transform .35s ease, background .35s ease, box-shadow .35s ease, color .35s ease, border-color .35s ease;
            opacity: 0;
            animation: pillPop .6s ease forwards;
        }

        .amenity-list li:nth-child(1) {
            animation-delay: .05s;
        }

        .amenity-list li:nth-child(2) {
            animation-delay: .15s;
        }

        .amenity-list li:nth-child(3) {
            animation-delay: .25s;
        }

        .amenity-list li:nth-child(4) {
            animation-delay: .35s;
        }

        .amenity-list li:nth-child(5) {
            animation-delay: .45s;
        }

        .amenity-list li:nth-child(6) {
            animation-delay: .55s;
        }

        @keyframes pillPop {
            from {
                opacity: 0;
                transform: translateY(14px) scale(.92);
            }

            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .amenity-list li:hover {
            background: #cda45e;
            border-color: #cda45e;
            color: #103c3b;
            transform: translateY(-4px);
            box-shadow: 0 12px 28px rgba(205, 164, 94, 0.35);
        }

        .amenity-check {
            flex-shrink: 0;
            width: 24px;
            height: 24px;
            border-radius: 50%;
            border: 1.5px solid #cda45e;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background .35s ease, border-color .35s ease;
        }

        .amenity-check svg {
            stroke: #cda45e;
            transition: stroke .35s ease;
        }

        .amenity-list li:hover .amenity-check {
            background: #103c3b;
            border-color: #103c3b;
        }

        .amenity-list li:hover .amenity-check svg {
            stroke: #cda45e;
        }

        .amenity-copy {
            margin-top: 26px;
            padding-top: 0;
        }

        .amenity-copy p {
            color: rgba(255, 255, 255, 0.65);
            line-height: 1.7;
        }

        .high-street-img {
            height: 420px;
            object-fit: cover;
            display: block;
            width: 100%;
        }

        @media (max-width: 991px) {
            .high-street-img {
                height: 300px;
            }
        }

        @media (max-width: 575px) {
            .high-street-img {
                height: 220px;
            }
        }

        .commercial-script {
            font-style: italic;
            font-weight: 500;
            font-size: clamp(28px, 3vw, 38px);
            color: #cda45e;
            line-height: 1.2;
            margin-bottom: 6px;
        }

        .commercial-title {
            color: rgba(255, 255, 255, 0.85);
            font-weight: 700;
            letter-spacing: .5px;
        }

        .tagline-premium {
            font-size: clamp(22px, 2.2vw, 30px);
            font-weight: 700;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            background: linear-gradient(120deg, #cda45e, #f5d9a0, #cda45e);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            margin-top: 10px;
            margin-bottom: 18px;
        }
    </style>

</head>

<body>

    <div id="wrapper">

        <div class="float-text show-on-scroll">
            <span><a href="#">Scroll to top</a></span>
        </div>
        <div class="scrollbar-v show-on-scroll"></div>

        <!-- page preloader begin -->
        <!-- <div id="de-loader"></div> -->
        <!-- page preloader close -->

        <header class="transparent logo-center">
            <div class="container-fluid px-lg-5 px-3">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="de-flex">
                            <div class="col-start">
                                <ul id="mainmenu">
                                    <li><a class="menu-item" href="index.php">Home</a>
                                    </li>

                                    <li><a class="menu-item" href="#">Facilities</a></li>
                                    <li><a class="menu-item" href="#">About Company</a></li>
                                    <li><a class="menu-item" href="#">Latest News</a></li>
                                    <li><a class="menu-item" href="contact.php">Contact us</a></li>
                                </ul>
                            </div>
                            <div class="col-center">
                                <a href="index.php" class="logo-premium"><img src="images/logo.png"
                                        alt=""></a>
                            </div>
                            <div class="col-end">
                                <div class="menu_side_area">
                                    <a href="contact.php"
                                        class="btn-main btn-line bg-blur fx-slide sm-hide"><span>Schedule a
                                            Visit</span></a>
                                    <span id="menu-btn"></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
