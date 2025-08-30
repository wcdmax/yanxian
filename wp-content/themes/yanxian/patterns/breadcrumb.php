<?php
/**
 * Title: 面包屑导航
 * Slug: yanxian/breadcrumb
 * Categories: yanxian, navigation，breadcrumb
 * Description: 显示当前页面的层级导航路径，支持分类、文章和页面
 */
?>

<section class="uk-section uk-padding-small uk-padding-remove-horizontal">
    <div class="uk-container uk-container-large">
        <nav aria-label="Breadcrumb" class="uk-padding-small uk-padding-remove-horizontal uk-border-rounded uk-background-default">
            <ul class="uk-breadcrumb yx-breadcrumb uk-margin-remove-bottom">
                <li><a title="首页" href="<?php echo home_url(); ?>"><span class="yx-text-primary" uk-icon="icon: home; ratio: 0.8"></span> 首页</a></li>
                <?php
                if (is_category()) :
                    // 分类页面的面包屑
                    $category = get_queried_object();
                    $ancestors = array_reverse(get_ancestors($category->term_id, 'category'));
                    foreach ($ancestors as $ancestor) : $ancestor_obj = get_term($ancestor, 'category'); ?>
                        <li>
                            <a title="<?php echo esc_attr($ancestor_obj->name); ?>" href="<?php echo esc_url(get_category_link($ancestor)); ?>">
                                <?php echo esc_html($ancestor_obj->name); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                    <li><span><?php echo esc_html($category->name); ?></span></li>
                <?php elseif (is_single()) :
                    // 文章页面的面包屑
                    $categories = get_the_category();
                    if (!empty($categories)) :
                        $category = $categories[0];
                        $ancestors = array_reverse(get_ancestors($category->term_id, 'category'));
                        foreach ($ancestors as $ancestor) : $ancestor_obj = get_term($ancestor, 'category'); ?>
                            <li>
                                <a title="<?php echo esc_attr($ancestor_obj->name); ?>" href="<?php echo esc_url(get_category_link($ancestor)); ?>">
                                    <?php echo esc_html($ancestor_obj->name); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                        <li>
                            <a title="<?php echo esc_attr($category->name); ?>" href="<?php echo esc_url(get_category_link($category->term_id)); ?>">
                                <?php echo esc_html($category->name); ?>
                            </a>
                        </li>
                        <li><span><?php the_title(); ?></span></li>
                    <?php endif; elseif (is_page()) :
                    // 页面的面包屑
                    $post = get_post();
                    if ($post->post_parent) :
                        $ancestors = array_reverse(get_post_ancestors($post->ID));
                        foreach ($ancestors as $ancestor) : ?>
                            <li>
                                <a title="<?php echo esc_attr(get_the_title($ancestor)); ?>" href="<?php echo esc_url(get_permalink($ancestor)); ?>">
                                    <?php echo esc_html(get_the_title($ancestor)); ?>
                                </a>
                            </li>
                        <?php endforeach; endif; ?>
                    <li><span><?php the_title(); ?></span></li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</section>