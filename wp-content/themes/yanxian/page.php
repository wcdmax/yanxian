<?php
get_header();
$slug = $post->post_name;

$template = match (true) {
    str_contains($slug, 'about') => 'about',                // 关于我们
    str_contains($slug, 'contact') => 'contact',            // 联系我们
    str_contains($slug, 'after-sales') => 'after-sales',    // 售后政策
    default => ''
};
?>

<!-- 分类Banner Start -->
<section class="uk-flex uk-section uk-flex-middle uk-height-large uk-background-cover yx-animation-background" style="background-image: url('//static.yxtouch.com/assets/images/banner.jpg');">
    <div class="uk-container uk-container-large">
        <div uk-grid class="uk-grid-small uk-grid-match uk-width-expand uk-child-width-1-2">
            <div>
                <h2 class="yx-text-white" uk-scrollspy="cls: uk-animation-slide-bottom-small;"><?php the_title(); ?></h2>
                <p class="yx-text-white uk-margin-remove" uk-scrollspy="cls: uk-animation-slide-bottom-small; delay: 200;"><?php echo wp_strip_all_tags(get_the_excerpt(), true); ?></p>
            </div>
        </div>
    </div>
</section>
<!-- 分类Banner End -->

<?php get_template_part('template-parts/page', $template); ?>

<?php get_footer(); get_sidebar(); ?>