<?php
get_header();
// 更新浏览量
$post_id = get_the_ID();
$views = absint(get_post_meta($post_id, 'post_views_count', true));
update_post_meta($post_id, 'post_views_count', ++$views);
// 获取当前分类
$categories = get_the_category();
if (!empty($categories)) {
    $category = $categories[0];
    // 获取所属顶级分类
    $ancestors = get_ancestors($category->term_id, 'category');
    // 如果已经是顶级分类，则直接获取顶级分类的slug
    $top_category = empty($ancestors) ? $category : get_term(end($ancestors), 'category');
    $category_slug = $top_category->slug; // 获取顶级分类的slug
}
$template = match (true) {
    str_contains($category_slug, 'product') => 'product',
    str_contains($category_slug, 'service') => 'download',
    default => 'article'
};
?>
<!-- 详情Banner Start -->
<section class="uk-flex uk-section uk-flex-middle uk-height-large uk-background-cover yx-animation-background" style="background-image: url('//static.yxtouch.com/assets/images/banner.jpg');">
    <div class="uk-container uk-container-large">
        <div uk-grid class="uk-grid-small uk-grid-match uk-width-expand uk-child-width-1-2">
            <div>
                <h2 class="yx-text-white" uk-scrollspy="cls: uk-animation-slide-bottom-small;"><?php echo single_cat_title(); ?></h2>
                <p class="yx-text-white uk-margin-remove" uk-scrollspy="cls: uk-animation-slide-bottom-small; delay: 200;"><?php echo $category->description; ?></p>
            </div>
        </div>
    </div>
</section>
<section class="uk-section uk-padding-remove-top">
    <div class="uk-container uk-container-large">
        <div class="uk-padding uk-padding-remove-horizontal">
            <?php echo do_blocks('<!-- wp:pattern {"slug":"yanxian/breadcrumb"} /-->'); ?>
        </div>
        <?php get_template_part('template-parts/single', $template); ?>
    </div>
</section>
<!-- 详情Banner End -->
<?php get_footer();
get_sidebar(); ?>