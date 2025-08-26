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
    <div class="swiper-pagination"></div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
</div>
<section class="uk-section">
    <div class="uk-container uk-container-large">
        <h2 class="uk-section-title">欢迎来到研显触控</h2>
        <p>我们专注于提供高质量的工业触摸屏解决方案。</p>
    </div>
</section>
<?php get_footer(); ?>