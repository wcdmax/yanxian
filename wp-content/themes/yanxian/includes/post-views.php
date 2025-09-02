<?php

/**
 * 处理阅读量列的排序
 */
add_action('pre_get_posts', function($query) {
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }

    $orderby = $query->get('orderby');
    if ($orderby === 'post_views') {
        $query->set('meta_key', 'post_views_count');
        $query->set('orderby', 'meta_value_num');
    }
});

/**
 * 在文章列表添加阅读量列
 */
add_filter('manage_posts_columns', function($columns) {
    // 创建新的列数组
    $new_columns = array();
    
    // 遍历现有列并重新排序
    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;
        // 在标题列后添加阅读量列
        if ($key === 'title') {
            $new_columns['post_views'] = '阅读量';
        }
    }
    
    return $new_columns;
});

/**
 * 添加阅读量列的可排序功能
 */
add_filter('manage_edit-post_sortable_columns', function($columns) {
    $columns['post_views'] = 'post_views';
    return $columns;
});

/**
 * 添加AJAX处理函数来更新阅读量
 */
add_action('wp_ajax_update_post_views', function() {
    if (!current_user_can('edit_posts')) {
        wp_send_json_error(['message' => '权限不足，请联系管理员']);
    }

    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
    $views = isset($_POST['views']) ? intval($_POST['views']) : 0;

    if ($post_id > 0 && $views >= 0) {
        update_post_meta($post_id, 'post_views_count', $views);
        wp_send_json_success(['message' => '阅读量更新成功']);
    } else {
        wp_send_json_error(['message' => '参数错误，请检查']);
    }
});

/**
 * 添加必要的JavaScript和CSS
 */
add_action('admin_enqueue_scripts', function($hook) {
    // 只在文章列表页面加载
    if ($hook !== 'edit.php') {
        return;
    }

    // 注册并加载CSS
    wp_register_style(
        'post-views-admin',
        get_template_directory_uri() . '/assets/css/post-views.css',
        array(),
        filemtime(get_template_directory() . '/assets/css/post-views.css')
    );
    wp_enqueue_style('post-views-admin');
    
    // 注册并加载JS
    wp_register_script(
        'post-views-admin',
        get_template_directory_uri() . '/assets/js/post-views-settings.js',
        array('jquery'),
        filemtime(get_template_directory() . '/assets/js/post-views-settings.js'),
        true
    );
    wp_enqueue_script('post-views-admin');
});

/**
 * 显示阅读量列的内容
 */
add_action('manage_posts_custom_column', function($column, $post_id) {
    if ($column === 'post_views') {
        $views = get_post_meta($post_id, 'post_views_count', true);
        echo '<div class="post-views-wrapper">';
        echo '<span class="post-views-text">' . esc_html($views) . '</span>';
        echo '<input type="number" class="post-views-input" style="display: none;" data-post-id="' . esc_attr($post_id) . '" value="' . esc_attr($views) . '" min="0">';
        echo '</div>';
    }
}, 10, 2);