<div uk-grid class="uk-grid-medium">
    <div class="uk-width-3-4">
        <div class="uk-card uk-card-body uk-padding-remove">
            <div class="yx-article-meta">
                <h2 class="uk-card-title"><?php the_title(); ?></h2>
                <ul class="uk-subnav uk-subnav-divider">
                    <li class="yx-article-meta-item">
                        <span uk-icon="calendar"></span>
                        <span class="yx-article-meta-item-text"><?php echo get_the_date(); ?></span>
                    </li>
                    <li class="yx-article-meta-item">
                        <span uk-icon="user"></span>
                        <span class="yx-article-meta-item-text">研显触控</span>
                    </li>
                    <li class="yx-article-meta-item">
                        <span uk-icon="eye"></span>
                        <span class="yx-article-meta-item-text"><?php echo absint(get_post_meta(get_the_ID(), 'post_views_count', true)); ?></span>
                    </li>
                </ul>
                <div class="yx-article-meta-tags">
                    <span class="yx-article-meta-item-text"><span uk-icon="icon: tag" class="uk-margin-small-right uk-icon"></span><a href="#" rel="tag">工业平板</a> <a href="#" rel="tag">工业平板一体机</a> <a href="#" rel="tag">工业平板一体机厂家</a></span>
                </div>
            </div>
            <hr>
            <article class="uk-article yx-article-content">
                <?php the_content(); ?>
            </article>
        </div>
    </div>
    <div class="uk-width-1-4">
        <div uk-grid class="uk-grid-small uk-child-width-1-1">
            <div>
                <?php
                set_query_var('search_placeholder', '搜索文章');
                echo do_blocks('<!-- wp:pattern {"slug":"yanxian/search"} /-->');
                ?>
            </div>
            <div>
                <?php echo do_blocks('<!-- wp:pattern {"slug":"yanxian/tags"} /-->'); ?>
            </div>
            <div>
                <?php echo do_blocks('<!-- wp:pattern {"slug":"yanxian/suggest"} /-->'); ?>
            </div>
        </div>
    </div>
    <div class="uk-width-expand">
        <hr>
        <?php echo do_blocks('<!-- wp:pattern {"slug":"yanxian/prenext"} /-->'); ?>
    </div>
</div>