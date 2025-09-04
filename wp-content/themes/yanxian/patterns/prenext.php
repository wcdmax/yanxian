<?php

/**
 * Title: 上一篇/下一篇导航
 * Slug: yanxian/prenext
 * Categories: yanxian, prenext
 * Description: 显示上一篇和下一篇文章的导航链接
 */
?>
<div class="uk-flex uk-margin-small <?php echo wp_is_mobile() ? 'uk-flex-column' : 'uk-flex-between'; ?>">
    <div class="uk-width-auto">
        <?php
        $prev_post = get_previous_post();
        if (!empty($prev_post)) : ?>
            <a href="<?php echo get_permalink($prev_post->ID); ?>" class="uk-flex uk-flex-middle yx-part-article-prev">
                <span uk-icon="icon: chevron-left; ratio: 1.2" class="uk-margin-small-right"></span>
                <span class="uk-text-truncate"><?php echo $prev_post->post_title; ?></span>
            </a>
        <?php else: ?>
            <span class="uk-flex uk-flex-middle uk-text-muted yx-part-article-prev">
                <span uk-icon="icon: chevron-left; ratio: 1.2" class="uk-margin-small-right"></span>
                <span>已经是第一篇了</span>
            </span>
        <?php endif; ?>
    </div>
    <div class="uk-width-auto">
        <?php
        $next_post = get_next_post();
        if (!empty($next_post)) : ?>
            <a href="<?php echo get_permalink($next_post->ID); ?>" class="uk-flex uk-flex-middle yx-part-article-next">
                <span class="uk-text-truncate"><?php echo $next_post->post_title; ?></span>
                <span uk-icon="icon: chevron-right; ratio: 1.2" class="uk-margin-small-left"></span>
            </a>
        <?php else: ?>
            <span class="uk-flex uk-flex-middle uk-text-muted yx-part-article-next">
                <span>已经是最后一篇了</span>
                <span uk-icon="icon: chevron-right; ratio: 1.2" class="uk-margin-small-left"></span>
            </span>
        <?php endif; ?>
    </div>
</div>