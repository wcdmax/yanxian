<?php

/**
 * Yanxian Theme Functions
 *
 * @package Yanxian
 */

require_once 'includes/site-info.php';

// 启用菜单
add_theme_support('nav-menus');

// 注册菜单位置
add_action('init', function () {
    register_nav_menus(array(
        'main_menu' => '顶部导航',
        'footer_menu' => '底部导航',
    ));
});

/**
 * 启用特色图片
 */
add_theme_support('post-thumbnails');

/**
 * 文章摘要长度
 */
add_filter('excerpt_length', function () {
    return 80;
}, 999);

/**
 * 禁用缩略图
 */

add_action('init', function () {
    // 防止生成默认缩略图
    update_option('large_size_w', 0);
    update_option('large_size_h', 0);
    update_option('medium_large_size_w', 0);
    update_option('medium_large_size_h', 0);
});

add_filter('intermediate_image_sizes_advanced', function ($sizes) {
    // 移除不需要的图片尺寸，彻底防止生成
    unset($sizes['large']);
    unset($sizes['1536x1536']);
    unset($sizes['2048x2048']);
    unset($sizes['medium_large']);
    return $sizes;
});

// 判断是否get请求
function is_get_method(): bool
{
    return $_SERVER['REQUEST_METHOD'] === 'GET';
}

// 判断是否post请求
function is_post_method(): bool
{
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

// 重写分类链接
add_filter('category_link', function ($url) {
    return str_replace('/category/', '/', $url);
});

/**
 * 调整分类名称
 */
add_filter('get_the_archive_title', function ($title) {
    if (is_category()) {
        $title = single_cat_title('', false);
    }
    return $title;
});

// 移除emoji
remove_action('wp_print_styles', 'print_emoji_styles');
remove_action('wp_head', 'print_emoji_detection_script', 7);

// 移除全局样式（global-styles-inline-css）
remove_action('wp_enqueue_scripts', 'wp_enqueue_global_styles');
// 移除经典主题样式（classic-theme-styles-inline-css）
remove_action('wp_enqueue_scripts', 'wp_enqueue_classic_theme_styles');

// 移除区块样式
remove_action('wp_enqueue_scripts', 'wp_common_block_scripts_and_styles');

/**
 * 清除footer_menu缓存
 */
function clear_footer_menu_cache()
{
    delete_transient('footer_menu_rest_data');
}

// 当菜单更新时清除缓存
add_action('wp_update_nav_menu', 'clear_footer_menu_cache');
add_action('wp_delete_nav_menu', 'clear_footer_menu_cache');
add_action('wp_create_nav_menu', 'clear_footer_menu_cache');

// 当菜单项更新时清除缓存
add_action('wp_update_nav_menu_item', 'clear_footer_menu_cache');
add_action('wp_delete_nav_menu_item', 'clear_footer_menu_cache');

/**
 * 启用友链管理
 */
add_filter('pre_option_link_manager_enabled', '__return_true');

/**
 * 添加描述字段
 */
apply_filters('activate_tinymce_for_media_description', true);

/**
 * 移除后台菜单
 */
add_action('admin_menu', function () {
    // 移除评论菜单
    remove_menu_page('edit-comments.php');
    // 移除隐私菜单
    remove_submenu_page('options-general.php', 'options-privacy.php');
    // 移除撰写菜单
    remove_submenu_page('options-general.php', 'options-writing.php');
    // 移除讨论菜单
    remove_submenu_page('options-general.php', 'options-discussion.php');
});

/**
 * 是否微信浏览器
 */
function isWeixinBrowser(): bool
{
    return str_contains($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger');
}

/**
 * 调整菜单在外观菜单中的显示顺序
 */
add_action('admin_menu', function () {
    global $submenu;

    // 找到外观菜单的子菜单
    if (isset($submenu['themes.php'])) {
        // 找到菜单项的位置
        $menu_position = null;
        foreach ($submenu['themes.php'] as $position => $item) {
            if (isset($item[2]) && $item[2] === 'nav-menus.php') {
                $menu_position = $position;
                break;
            }
        }

        // 如果找到菜单项,调整其位置到第三个
        if ($menu_position !== null) {
            $menu_item = $submenu['themes.php'][$menu_position];
            unset($submenu['themes.php'][$menu_position]);
            $submenu['themes.php'][3] = $menu_item;
            ksort($submenu['themes.php']);
        }
    }
});

/**
 * 保存菜单连接打开方式
 */
add_action('wp_update_nav_menu_item', function ($menu_id, $menu_item_db_id) {
    if (isset($_POST['menu-item-target'][$menu_item_db_id])) {
        update_post_meta(
            $menu_item_db_id,
            '_menu_item_target',
            sanitize_text_field($_POST['menu-item-target'][$menu_item_db_id])
        );
    }
}, 10, 2);

/**
 * 保存菜单自定义链接ID字段
 */
add_action('wp_update_nav_menu_item', function ($menu_id, $menu_item_db_id) {
    if (isset($_POST['menu-item-id'][$menu_item_db_id])) {
        update_post_meta(
            $menu_item_db_id,
            '_menu_item_id',
            sanitize_text_field($_POST['menu-item-id'][$menu_item_db_id])
        );
    }
}, 10, 2);

/**
 * 添加菜单连接打开方式
 */
add_action('wp_nav_menu_item_custom_fields', function ($item_id, $item) {
    if ($item->type === 'custom') {
        return;
    }
    $target = get_post_meta($item_id, '_menu_item_target', true);
?>
    <p class="description description-wide">
        <label for="edit-menu-item-target-<?php echo $item_id; ?>">
            打开方式<br>
            <select id="edit-menu-item-target-<?php echo $item_id; ?>" class="widefat edit-menu-item-target" name="menu-item-target[<?php echo $item_id; ?>]">
                <option value="" <?php selected($target, ''); ?>>默认</option>
                <option value="_self" <?php selected($target, '_self'); ?>>当前窗口</option>
                <option value="_blank" <?php selected($target, '_blank'); ?>>新的窗口</option>
            </select>
        </label>
    </p>
<?php
}, 10, 2);

/**
 * 增加文章浏览量
 * @return void
 * @author Kevin
 * @param $post
 * @param $update
 * @param $post_id
 * @createAt 2024/11/19 11:35
 */
function add_post_views_count($post_id, $post, $update): void
{
    // 检查是否是自动保存
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // 检查用户权限
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // 添加或更新自定义字段
    $meta_key = 'post_views_count';
    $meta_value = absint(get_post_meta($post_id, $meta_key, true));

    if ($meta_value == 0) {
        $meta_value = rand(369, 1024);
        add_post_meta($post_id, $meta_key, $meta_value, true);
    }
}

add_action('save_post', 'add_post_views_count', 10, 3);

/**
 * 获取SEO标题
 * @return string
 * @author Kevin
 * @createAt 2024/7/2 下午7:32
 */
// function get_seo_title(): string
// {
//     $blog_name = get_bloginfo('name');
//     switch (true) {
//         case is_home():
//             return $blog_name . ' ' . get_bloginfo('description');
//         case is_page():
//         case is_single():
//             $seo_title = get_field('seo_title');
//             if (!empty($seo_title)) {
//                 return $seo_title . ' - ' . $blog_name;
//             }

//             return the_title() . ' - ' . $blog_name;
//         case is_search():
//             $paged = get_query_var('paged');
//             if ($paged > 1) {
//                 return '搜索结果：' . get_search_query() . '_第' . $paged . '页 - ' . $blog_name;
//             }
//             return '搜索结果：' . get_search_query() . ' - ' . $blog_name;
//         case is_tag():
//         case is_category():
//             // 获取当前分页
//             $paged = get_query_var('paged');
//             // 获取分类自定义标题
//             $cat_title = get_term_meta(get_query_var('cat'), 'cat_title', true);

//             // 如果存在自定义标题
//             if (!empty($cat_title)) {
//                 // 如果不是第一页,添加分页信息
//                 if ($paged > 1) {
//                     return $cat_title . '_第' . $paged . '页 - ' . $blog_name;
//                 }
//                 // 第一页直接返回标题
//                 return $cat_title . ' - ' . $blog_name;
//             }

//             // 如果没有自定义标题且不是第一页,使用默认标题并添加分页信息
//             if ($paged > 1) {
//                 return wp_strip_all_tags(get_the_archive_title()) . '_第' . $paged . '页 - ' . $blog_name;
//             }
//             return wp_strip_all_tags(get_the_archive_title()) . ' - ' . $blog_name;
//         default:
//             return $blog_name;
//     }
// }

/**
 * 获取关键词
 * @return string
 * @author Kevin
 * @createAt 2024/7/2 下午7:32
 */
// function get_seo_keywords(): string
// {
//     $default_keywords = get_option('global_keywords');
//     switch (true) {
//         case is_page():
//         case is_single():
//             return get_field('seo_keywords') ?: $default_keywords;
//         case is_tag():
//             $tag = single_tag_title('', false);
//             $suffix = ["价格", "品牌", "产品", "厂家", "报价"];
//             foreach ($suffix as $item) {
//                 $tag .= ',' . single_tag_title('', false) . $item;
//             }
//             return $tag;
//         case is_search():
//             $keywords = get_search_query();
//             $suffix = ["价格", "品牌", "产品", "厂家", "报价"];
//             foreach ($suffix as $item) {
//                 $keywords .= ',' . get_search_query() . $item;
//             }
//             return $keywords;
//         case is_category():
//             $category_id = 'category_' . get_query_var('cat');
//             return get_field('cat_keywords', $category_id) ?: $default_keywords;
//         default:
//             return $default_keywords;
//     }
// }

/**
 * 获取SEO描述
 * @return string
 * @author Kevin
 * @createAt 2024/7/2 下午7:31
 */
// function get_seo_description(): string
// {
//     $default_description = get_option('global_description');
//     switch (true) {
//         case is_page():
//         case is_single():
//             return get_field('seo_description') ?: (get_the_excerpt() ?: $default_description);
//         case is_category():
//             $archive_description = get_the_archive_description();
//             $category_id = 'category_' . get_query_var('cat');
//             $plain_text_description = wp_strip_all_tags($archive_description);
//             return get_field('seo_description', $category_id) ?: $plain_text_description;
//         case is_tag():
//             global $wp_query;
//             $paged = get_query_var('paged') ? get_query_var('paged') : 1;
//             return single_tag_title('标签：', false) . '，第' . $paged . '页，共' . $wp_query->found_posts . '条记录。';
//         case is_search():
//             global $wp_query;
//             $paged = get_query_var('paged') ? get_query_var('paged') : 1;
//             return '搜索结果：' . get_search_query() . '，第' . $paged . '页，共' . $wp_query->found_posts . '条记录。';
//         default:
//             return $default_description;
//     }
// }

/**
 * 将搜索URL转换为伪静态格式
 */
add_action('template_redirect', function () {
    // 只有当URL不是/search/开头时才进行重定向
    if (is_search() && !empty($_GET['s']) && !str_starts_with($_SERVER['REQUEST_URI'], '/search/')) {
        wp_redirect(home_url("/search/") . urlencode(get_query_var('s')));
        exit();
    }
});

/**
 * 修改下载类型文章的固定链接结构为 顶级分类/子分类/post-id.html
 */
add_filter('post_type_link', function ($post_link, $post) {
    if ($post->post_type === 'download' || $post->post_type === 'product') {
        // 获取文章所属的分类
        $terms = get_the_terms($post->ID, 'category');
        if (!empty($terms)) {
            $term = $terms[0];
            $category_path = '';

            // 获取完整的分类层级路径
            if ($term->parent !== 0) {
                $ancestors = array_reverse(get_ancestors($term->term_id, 'category'));
                foreach ($ancestors as $ancestor) {
                    $ancestor_term = get_term($ancestor, 'category');
                    $category_path .= $ancestor_term->slug . '/';
                }
            }
            // 添加当前分类
            $category_path .= $term->slug;
            // 生成url连接
            return home_url($category_path . '/' . $post->ID . '.html');
        }
        // 如果没有分类，则使用默认的 download 目录
        return home_url($post->post_type . '/' . $post->ID . '.html');
    }
    return $post_link;
}, 10, 2);

/**
 * 发布文章后自动推送到百度
 */
add_action('publish_post', function ($post_id) {
    // 检查文章类型
    $post_type = get_post_type($post_id);
    if (!in_array($post_type, ['post', 'product', 'download'])) {
        return;
    }

    // 获取文章分类
    $categories = get_the_category($post_id);
    if (empty($categories)) {
        return; // 如果没有分类，不推送
    }

    // 获取文章链接
    $post_link = get_permalink($post_id);

    if (strpos($post_link, '/application/') !== false) {
        return; // 跳过错误的URL格式
    }

    // 实例化推送类
    $url_push = new URL_Push($post_id, $post_link);

    if (get_option("url_push_bing_key")) {
        // 推送至必应
        $url_push->push_url_to_bing();
    }

    if (get_option("url_push_baidu_token")) {
        // 推送至百度
        $url_push->push_url_to_baidu();
    }
}, 10, 1);

/**
 * 修改图片和图库区块的HTML输出
 */
add_filter('render_block', function ($block_content, $block) {
    // 只处理图片和图库区块
    if (!in_array($block['blockName'], ['core/image', 'core/gallery'])) {
        return $block_content;
    }

    // 解析现有的图片HTML
    preg_match_all('/<img[^>]+>/i', $block_content, $matches);

    // 提取figcaption文本
    preg_match_all('/<figcaption[^>]*>(.*?)<\/figcaption>/i', $block_content, $figcaptions);

    if (!empty($matches[0])) {
        $new_content = '';
        foreach ($matches[0] as $key => $img) {
            // 提取需要的属性
            preg_match('/src=["\']([^"\']+)["\']/i', $img, $src);
            preg_match('/alt=["\']([^"\']+)["\']/i', $img, $alt);
            preg_match('/title=["\']([^"\']+)["\']/i', $img, $title);

            // 获取figcaption文本(如果存在)
            $caption_text = !empty($figcaptions[1][$key]) ? trim($figcaptions[1][$key]) : '';

            // 获取原图地址
            $src_url = $src[1] ?? '';
            if (preg_match('/-\d+x\d+\./i', $src_url)) {
                $src_url = preg_replace('/-\d+x\d+\./i', '.', $src_url);
            }

            // 使用figcaption文本
            $alt_text = isset($alt[1]) ? $alt[1] : ($caption_text ?: '');
            $title_text = isset($title[1]) ? $title[1] : ($caption_text ?: $alt_text);

            // 检查当前文章类型
            $is_product = get_post_type() === 'product';

            // 根据文章类型输出不同的HTML结构
            if ($is_product || empty($caption_text)) {
                $img_attributes = '';
                if (!empty($alt_text)) {
                    $img_attributes .= sprintf(' alt="%s"', $alt_text);
                }
                if (!empty($title_text)) {
                    $img_attributes .= sprintf(' title="%s"', $title_text);
                }
                $new_content .= sprintf(
                    '<img%s src="%s">',
                    $img_attributes,
                    $src_url
                );
            } else {
                $new_content .= sprintf(
                    '<figure class="size-full wp-block-image"><img alt="%s" title="%s" src="%s"><figcaption class="uk-text-center uk-text-muted wp-element-caption">%s</figcaption></figure>',
                    $alt_text,
                    $title_text,
                    $src_url,
                    $caption_text
                );
            }
        }
        return $new_content;
    }

    return $block_content;
}, 10, 2);

/**
 * 调整工具菜单的显示顺序
 */
add_action('admin_menu', function () {
    global $submenu;

    // 确保工具菜单存在
    if (isset($submenu['tools.php'])) {
        // 找到"可用工具"菜单项的位置
        $available_tools_position = null;
        foreach ($submenu['tools.php'] as $position => $item) {
            if ($item[2] === 'tools.php') {
                $available_tools_position = $position;
                break;
            }
        }

        // 如果找到了"可用工具"菜单项，调整其位置
        if ($available_tools_position !== null) {
            $tools_item = $submenu['tools.php'][$available_tools_position];
            unset($submenu['tools.php'][$available_tools_position]);
            // 将其移动到最后一个位置
            $submenu['tools.php'][4] = $tools_item;
            // 重新排序
            ksort($submenu['tools.php']);
        }
    }
}, 100);

/**
 * 重写产品分类的永久链接结构
 */
add_filter('rewrite_rules_array', function ($rules) {
    $new_rules = array(
        // 产品分页（带分类）
        '^product/([^/]+)/page/([0-9]+)$' => 'index.php?post_type=product&category_name=$matches[1]&paged=$matches[2]',
        // 产品详情（带分类）
        '^product/([^/]+)/([0-9]+)\.html$' => 'index.php?post_type=product&category_name=$matches[1]&p=$matches[2]',
        // 产品分页（不带分类）
        '^product/page/([0-9]+)$' => 'index.php?post_type=product&paged=$matches[1]&category_name=product',
        // 二级产品分类
        '^product/([^/]+)/?$' => 'index.php?post_type=product&category_name=$matches[1]',
        // 一级分类归档
        '^product/?$' => 'index.php?category_name=product',
    );
    return $new_rules + $rules;
});
