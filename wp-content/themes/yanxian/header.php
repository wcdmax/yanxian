<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <title>研显触控</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="研显触控,工控一体机,工业触摸屏,工业平板电脑">
    <meta name="description" content="研显触摸是集工控一体机、工业触摸屏、工业级主机研发、生产、销售于一体的高新技术企业。">
    <link rel="stylesheet" href="<?php echo get_theme_file_uri('/assets/css/basic.css'); ?>">
    <link rel="stylesheet" href="<?php echo get_theme_file_uri('/assets/css/animate.min.css'); ?>">
    <script type="text/javascript" lang="javascript" src="<?php echo get_theme_file_uri('/assets/js/basic.js'); ?>"></script>
    <?php if (is_front_page()): ?>
        <script type="text/javascript" language="javascript" src="<?php echo get_theme_file_uri('/assets/js/index.js'); ?>"></script>
    <?php endif; ?>
    <script type="text/javascript" lang="javascript" src="<?php echo get_theme_file_uri('/assets/js/uikit.min.js'); ?>"></script>
    <script type="text/javascript" lang="javascript" src="<?php echo get_theme_file_uri('/assets/js/uikit-icons.min.js'); ?>"></script>
    <script type="text/javascript" lang="javascript" src="<?php echo get_theme_file_uri('/assets/js/swiper-bundle.min.js'); ?>"></script>
    <script type="text/javascript" lang="javascript" src="<?php echo get_theme_file_uri('/assets/js/swiper.animate1.0.3.min.js'); ?>"></script>
    <?php wp_head(); ?>
</head>

<body>
    <header class="uk-position-top yx-navbar-header" uk-sticky="start: 170; animation: uk-animation-slide-top; sel-target: .uk-navbar-container; cls-active: uk-navbar-sticky; cls-inactive: uk-light uk-navbar-transparent; end: ! *;">
        <nav class="uk-navbar-container">
            <div class="uk-container uk-container-large">
                <div uk-navbar>
                    <div class="uk-navbar-left">
                        <a href="/" class="uk-logo uk-navbar-item">
                            <img alt="研显触控" title="研显触控" uk-scrollspy="cls: uk-animation-scale-up;" src="<?php echo get_theme_file_uri('/assets/icons/logo.png'); ?>">
                        </a>
                    </div>
                    <div class="uk-navbar-center">
                        <ul class="uk-navbar-nav yx-navbar-nav" uk-scrollspy="cls: uk-animation-slide-top-small; target: > li; delay: 100">
                            <li class="uk-active"><a href="#">首页</a></li>
                            <li>
                                <a href="#">产品中心<span uk-navbar-parent-icon></span></a>
                                <div class="uk-navbar-dropdown yx-navbar-dropdown" uk-dropdown="animation: reveal-top; animate-out: true; duration: 500">
                                    <ul class="uk-nav uk-navbar-dropdown-nav">
                                        <li><a href="#">工控一体机</a></li>
                                        <li><a href="#">工业触摸屏</a></li>
                                        <li><a href="#">商用广告机</a></li>
                                        <li><a href="#">工业平板电脑</a></li>
                                    </ul>
                                </div>
                            </li>
                            <li>
                                <a href="#">解决方案<span uk-navbar-parent-icon></span></a>
                                <div class="uk-navbar-dropdown yx-navbar-dropdown" uk-dropdown="animation: reveal-top; animate-out: true; duration: 500">
                                    <ul class="uk-nav uk-navbar-dropdown-nav">
                                        <li><a href="#">智能制造</a></li>
                                        <li><a href="#">医疗政务</a></li>
                                        <li><a href="#">酒店餐饮</a></li>
                                        <li><a href="#">交通枢纽</a></li>
                                    </ul>
                                </div>
                            </li>
                            <li><a href="#">样机借测</a></li>
                            <li><a href="#">关于我们</a></li>
                            <li><a href="#">联系我们</a></li>
                            <li><a href="#">服务支持</a></li>
                        </ul>
                    </div>
                    <div class="uk-navbar-right yx-navbar-right">
                        <ul class="uk-navbar-nav">
                            <li class="uk-navbar-item" uk-scrollspy="cls: uk-animation-slide-top-small; delay: 800">
                                <img alt="400电话" title="400电话" src="<?php echo get_theme_file_uri('/assets/icons/phone.png'); ?>">
                                <p class="yx-navbar-phone-number"></p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <?php wp_body_open(); ?>