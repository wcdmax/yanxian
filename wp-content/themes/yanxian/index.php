<?php get_header(); ?>
<div class="swiper" id="main-slide">
    <div class="swiper-wrapper">
        <div class="swiper-slide">
            <img alt="Slide 1" src="<?php echo get_template_directory_uri(); ?>/assets/images/slide1.jpg">
        </div>
        <div class="swiper-slide">
            <img alt="Slide 2" src="<?php echo get_template_directory_uri(); ?>/assets/images/slide2.jpg">
        </div>
        <div class="swiper-slide">
            <img alt="Slide 3" src="<?php echo get_template_directory_uri(); ?>/assets/images/slide3.jpg">
        </div>
        <div class="swiper-slide">
            <img alt="Slide 4" src="<?php echo get_template_directory_uri(); ?>/assets/images/slide4.jpg">
        </div>
    </div>
    <div class="uk-flex uk-flex-between uk-flex-middle uk-position-medium yx-main-slide-caption uk-position-bottom-center uk-position-z-index-high">
        <div class="swiper-pagination"></div>
        <div class="uk-flex uk-flex-column uk-flex-center uk-flex-middle yx-swiper-scroll-down uk-position-center-horizontal">
            <h4 class="uk-margin-remove">Scroll Down</h4>
            <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 16L20 24L28 16" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M12 9L20 17L28 9" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
        <div class="uk-position-relative swiper-button-container">
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
    </div>
</div>
<section class="uk-section uk-background-top-left" style="background-image: url('<?php echo get_theme_file_uri('/assets/images/bg-product.jpg'); ?>');">
    <div class="uk-container uk-container-large">
        <h2 class="uk-section-title">产品中心</h2>
        <p>源头厂家，提供全方位多行业定制方案，满足您的各种需求</p>
        <div uk-grid class="uk-grid-small uk-grid-match">
            <div class="uk-width-2-3">
                <div class="uk-card yx-card-body uk-card-hover uk-card-default uk-overflow-hidden uk-background-norepeat" style="background-image: url('<?php echo get_theme_file_uri('/assets/images/provide_customized.png'); ?>');">
                    <div class="uk-position-medium yx-card-customized uk-position-center-right">
                        <h3>Provide customized<br>
                            services for customers</h3>
                        <h3 class="uk-margin-remove">为客户提供个性化定制服务</h3>
                        <p class="uk-text-meta">屏幕尺寸、接口模块、安装方式、硬件配置、OEM/ODM...</p>
                        <a class="uk-button uk-border-pill yx-button-primary" href="#">联系我们</a>
                    </div>
                </div>
            </div>
            <div class="uk-width-1-3">
                <div class="uk-card uk-padding yx-card-body uk-card-default yx-card-category uk-overflow-hidden">
                    <div class="yx-card-title">
                        <h3>工控一体机</h3>
                        <p>Industrial All in One PC</p>
                    </div>
                    <div class="yx-card-image">
                        <img alt="工控一体机" src="<?php echo get_theme_file_uri('/assets/images/g1a.png'); ?>">
                    </div>
                </div>
            </div>
            <div class="uk-width-1-3">
                <div class="uk-card uk-padding yx-card-body uk-card-default yx-card-category uk-overflow-hidden">
                    <div class="yx-card-title">
                        <h3>工业触摸屏</h3>
                        <p>Industrial Display</p>
                    </div>
                    <div class="yx-card-image">
                        <img alt="工业触摸屏" src="<?php echo get_theme_file_uri('/assets/images/g1a.png'); ?>">
                    </div>
                </div>
            </div>
            <div class="uk-width-1-3">
                <div class="uk-card uk-padding yx-card-body uk-card-default yx-card-category uk-overflow-hidden">
                    <div class="yx-card-title">
                        <h3>商用广告机</h3>
                        <p>Commercial Advertising Machine</p>
                    </div>
                    <div class="yx-card-image">
                        <img alt="商用广告机" src="<?php echo get_theme_file_uri('/assets/images/g1a.png'); ?>">
                    </div>
                </div>
            </div>
            <div class="uk-width-1-3">
                <div class="uk-card uk-padding yx-card-body uk-card-default yx-card-category uk-overflow-hidden">
                    <div class="yx-card-title">
                        <h3>工业平板电脑</h3>
                        <p>Industrial Tablet PC</p>
                    </div>
                    <div class="yx-card-image">
                        <img alt="工业平板电脑" src="<?php echo get_theme_file_uri('/assets/images/g1a.png'); ?>">
                    </div>
                </div>
            </div>
            <div class="uk-width-expand">
                <div class="uk-padding yx-prod-system uk-background-default">
                    <div uk-grid class="uk-grid-small uk-grid-match uk-child-width-1-4">
                        <div>
                            <div class="uk-card uk-card-body uk-text-center">
                                <img alt="安卓系统" src="<?php echo get_theme_file_uri('/assets/icons/android.png'); ?>">
                                <h5 class="uk-margin-small-top uk-margin-remove-bottom">安卓系统</h5>
                                <small>Android 9/Android 10/ Android 11</small>
                            </div>
                        </div>
                        <div>
                            <div class="uk-card uk-card-body uk-text-center">
                                <img alt="Windows系统" src="<?php echo get_theme_file_uri('/assets/icons/windows.png'); ?>">
                                <h5 class="uk-margin-small-top uk-margin-remove-bottom">Windows</h5>
                                <small>Windows 7/Windows 10/Windows 11</small>
                            </div>
                        </div>
                        <div>
                            <div class="uk-card uk-card-body uk-text-center">
                                <img alt="安卓系统" src="<?php echo get_theme_file_uri('/assets/icons/linux.png'); ?>">
                                <h5 class="uk-margin-small-top uk-margin-remove-bottom">Linux系统</h5>
                                <small>统信OS/Ubuntu/麒麟系统/鸿蒙系统</small>
                            </div>
                        </div>
                        <div>
                            <div class="uk-card uk-card-body uk-text-center">
                                <img alt="其它系统" src="<?php echo get_theme_file_uri('/assets/icons/other.png'); ?>">
                                <h5 class="uk-margin-small-top uk-margin-remove-bottom">其它系统</h5>
                                <small>支持安装其它操作系统</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php get_footer(); ?>