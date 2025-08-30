<?php
get_header();
// 获取当前分类
$category = get_queried_object();
// 获取所属顶级分类
$ancestors = get_ancestors($category->term_id, 'category');
// 如果已经是顶级分类，则直接获取顶级分类的slug
$top_category = empty($ancestors) ? $category : get_term(end($ancestors), 'category');
$category_slug = $top_category->slug; // 获取顶级分类的slug

$template = match (true) {
    str_contains($category_slug, 'product') => 'product',
    str_contains($category_slug, 'service') => 'download',
    default => 'article'
};
?>
<!-- 分类Banner Start -->
<section class="uk-flex uk-section uk-flex-middle uk-height-large uk-background-cover" style="background-image: url('<?php echo get_theme_file_uri('/assets/images/banner.jpg'); ?>');">
    <div class="uk-container uk-container-large">
        <div uk-grid class="uk-grid-small uk-grid-match uk-width-expand uk-child-width-1-2">
            <div>
                <h2 class="yx-text-white"><?php echo single_cat_title(); ?></h2>
                <p class="yx-text-white"><?php echo $category->description; ?></p>
            </div>
        </div>
    </div>
</section>
<!-- 分类Banner End -->
<section class="uk-section uk-padding-remove uk-box-shadow-small">
    <div class="uk-container uk-container-large">
        <div class="uk-flex uk-flex-middle uk-flex-between uk-padding-small uk-padding-remove-horizontal">
            <!-- 二级分类菜单 Start -->
            <?php echo do_blocks('<!-- wp:pattern {"slug":"yanxian/category-subnav"} /-->'); ?>
            <!-- 二级分类菜单 End -->
            <!-- 面包屑导航 Start -->
            <?php echo do_blocks('<!-- wp:pattern {"slug":"yanxian/breadcrumb"} /-->'); ?>
            <!-- 面包屑导航 End -->
        </div>
    </div>
</section>
<section class="uk-section">
    <div class="uk-container uk-container-large">
        <h2>分类页面</h2>
        <h4>工控一体机产品</h4>
    </div>
</section>
<?php get_footer();
get_sidebar(); ?>