<?php

/**
 * Title: 分类二级菜单
 * Slug: yanxian/category-subnav
 * Categories: yanxian, category, subnav
 * Description: 显示当前分类所属顶级分类下的所有二级分类菜单
 */

// 只在分类页面显示
if (is_category()) :
    // 获取当前分类
    $category = get_queried_object();
    // 获取所属顶级分类
    $ancestors = get_ancestors($category->term_id, 'category');
    // 如果已经是顶级分类，则直接获取顶级分类
    $top_category = empty($ancestors) ? $category : get_term(end($ancestors), 'category');

    // 获取顶级分类下的所有子分类（二级分类）
    $child_categories = get_terms([
        'order' => 'ASC',
        'hide_empty' => false,
        'taxonomy' => 'category',
        'orderby' => 'term_order',
        'parent' => $top_category->term_id,
    ]);
?>

    <!-- 二级分类菜单 Start -->
    <ul class="uk-subnav yx-subnav uk-margin-remove uk-subnav-divider" uk-scrollspy="cls: uk-animation-slide-top-small; target: > li; delay: 100">
        <?php if (!empty($child_categories)) : ?>
            <?php foreach ($child_categories as $child_cat) : ?>
                <?php
                // 判断是否为当前分类或当前分类的父级
                $is_active = ($child_cat->term_id == $category->term_id) ||
                    in_array($child_cat->term_id, get_ancestors($category->term_id, 'category'));
                ?>
                <li<?php echo $is_active ? ' class="uk-active"' : ''; ?>>
                    <a href="<?php echo get_category_link($child_cat->term_id); ?>" <?php echo !$is_active ? ' class="uk-link-text"' : ''; ?>>
                        <?php echo esc_html($child_cat->name); ?>
                    </a>
                </li>
                <?php endforeach; ?>
            <?php else : ?>
                <!-- 如果没有子分类，显示顶级分类本身 -->
                <li class="uk-active">
                    <a href="<?php echo get_category_link($top_category->term_id); ?>">
                        <?php echo esc_html($top_category->name); ?>
                    </a>
                </li>
            <?php endif; ?>
    </ul>
    <!-- 二级分类菜单 End -->
<?php endif; ?>