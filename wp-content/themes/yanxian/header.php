<?php
$top_category = null;
$categories = get_the_category();
if (!empty($categories) && isset($categories[0])) {
    $top_category = $categories[0];
    while ($top_category->parent) {
        $top_category = get_category($top_category->parent);
    }
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <title>深圳市研显触控科技有限公司</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="研显触控,工控一体机,工业触摸屏,工业平板电脑">
    <meta name="description" content="研显触摸是集工控一体机、工业触摸屏、工业级主机研发、生产、销售于一体的高新技术企业。">
    <link rel="stylesheet" href="<?php echo get_theme_file_uri('/assets/css/basic.css'); ?>">
    <script type="text/javascript" lang="javascript" src="<?php echo get_theme_file_uri('/assets/js/basic.js'); ?>"></script>
    <?php if (is_front_page()): ?>
        <script type="text/javascript" language="javascript" src="<?php echo get_theme_file_uri('/assets/js/index.js'); ?>"></script>
    <?php endif; ?>
    <?php if (is_single() && $top_category && $top_category->slug === 'product'): ?>
    <script type="text/javascript" lang="javascript" src="<?php echo get_theme_file_uri('/assets/js/prod-magnifier.js'); ?>"></script>
    <?php endif; ?>
    <script type="text/javascript" lang="javascript" src="//static.yxtouch.com/assets/js/uikit.min.js"></script>
    <script type="text/javascript" lang="javascript" src="//static.yxtouch.com/assets/js/uikit-icons.min.js"></script>
    <script type="text/javascript" lang="javascript" src="//static.yxtouch.com/assets/js/swiper-bundle.min.js"></script>
    <script type="text/javascript" lang="javascript" src="//static.yxtouch.com/assets/js/swiper.animate1.0.3.min.js"></script>
    <?php wp_head(); ?>
</head>

<body>
    <header class="uk-position-top yx-navbar-header" uk-sticky="start: 170; animation: uk-animation-slide-top; sel-target: .uk-navbar-container; cls-active: uk-navbar-sticky; cls-inactive: uk-light uk-navbar-transparent; end: ! *;">
        <nav class="uk-navbar-container">
            <div class="uk-container uk-container-large">
                <div uk-navbar>
                    <div class="uk-navbar-left">
                        <a href="/" class="uk-logo uk-navbar-item">
                            <img alt="研显触控" title="研显触控" uk-scrollspy="cls: uk-animation-scale-up;" src="//static.yxtouch.com/assets/icons/logo.png">
                        </a>
                    </div>
                    <div class="uk-navbar-center">
                        <?php
                        wp_nav_menu(array(
                            'container' => false,
                            'theme_location' => 'main_menu',
                            'walker' => new Main_Menu_Walker(),
                            'menu_class' => 'uk-navbar-nav yx-navbar-nav',
                            'items_wrap' => '<ul id="%1$s" class="%2$s" uk-scrollspy="cls: uk-animation-slide-top-small; target: > li; delay: 100">%3$s</ul>'
                        ));
                        ?>
                    </div>
                    <div class="uk-navbar-right yx-navbar-right">
                        <ul class="uk-navbar-nav">
                            <li class="uk-navbar-item" uk-scrollspy="cls: uk-animation-slide-top-small; delay: 800">
                                <img alt="400电话" title="400电话" src="//static.yxtouch.com/assets/icons/phone.png">
                                <p class="yx-navbar-phone-number"></p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </header>
    <?php wp_body_open(); ?>