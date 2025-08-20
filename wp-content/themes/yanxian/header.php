<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <title>研显触控</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="研显触控,工控一体机,工业触摸屏,工业平板电脑">
    <meta name="description" content="研显触摸是集工控一体机、工业触摸屏、工业级主机研发、生产、销售于一体的高新技术企业。">
    <link rel="stylesheet" href="<?php echo get_theme_file_uri('/assets/css/basic.css'); ?>">
    <script type="text/javascript" lang="javascript" src="<?php echo get_theme_file_uri('/assets/js/basic.js'); ?>"></script>
    <?php if (is_front_page()): ?>
    <script type="text/javascript" language="javascript" src="<?php echo get_theme_file_uri('/assets/js/index.js'); ?>"></script>
    <?php endif; ?>
    <script type="text/javascript" lang="javascript" src="<?php echo get_theme_file_uri('/assets/js/uikit.min.js'); ?>"></script>
    <script type="text/javascript" lang="javascript" src="<?php echo get_theme_file_uri('/assets/js/swiper-bundle.min.js'); ?>"></script>
    <?php wp_head(); ?>
</head>

<body>
    <header class="yx-navbar-container">
        <nav uk-navbar class="uk-container uk-container-large">
            <div class="uk-navbar-left">
                <a href="/" class="uk-logo uk-navbar-item">
                    <img alt="研显触控" title="研显触控" src="<?php echo get_theme_file_uri('/assets/icons/logo.png'); ?>">
                </a>
            </div>
            <div class="uk-navbar-center">
                <ul class="uk-navbar-nav yx-navbar-nav">
                    <li class="uk-active"><a href="#">首页</a></li>
                    <li><a href="#">产品中心</a></li>
                    <li><a href="#">解决方案</a></li>
                    <li><a href="#">样机借测</a></li>
                    <li><a href="#">关于我们</a></li>
                    <li><a href="#">联系我们</a></li>
                    <li><a href="#">服务与支持</a></li>
                </ul>
            </div>
            <div class="uk-navbar-right">
                <ul class="uk-navbar-nav yx-navbar-nav">
                    <li><a href="#" class="uk-navbar-item">登录</a></li>
                    <li><a href="#" class="uk-navbar-item">注册</a></li>
                </ul>
            </div>
        </nav>
    </header>
    <?php wp_body_open(); ?>