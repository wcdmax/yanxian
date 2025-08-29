<?php
/*
Author: Kevin
Version: 1.0.0
License: Apache2.0
Plugin Name: URL Push
Author URI: mailto:kevin@tigervs.com
Description: 将URL推送至百度和必应搜索引擎
*/

require_once 'url-push-class.php';
require_once 'url-push-admin.php';

// 激活插件时创建表
register_activation_hook(__FILE__, function() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'url_push';
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        post_id BIGINT(20) UNSIGNED NOT NULL,
        url TEXT NOT NULL,
        platform VARCHAR(20) NOT NULL,
        success TINYINT(1) NOT NULL DEFAULT 0,
        remain INT(10) UNSIGNED NOT NULL DEFAULT 0,
        create_time DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY  (id),
        KEY post_id (post_id)
    ) $charset_collate;";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
});

// 引入样式文件
add_action('admin_enqueue_scripts', function($hook) {
    if ($hook === 'settings_page_url-push') {
        wp_enqueue_style('url-push-index', plugins_url('assets/css/index.css', __FILE__));
    }
});

// 添加设置选项按钮
add_filter('plugin_action_links_' . plugin_basename(__FILE__), function($links) {
    $settings_link = '<a href="' . admin_url('options-general.php?page=site-info') . '">' . __('设置') . '</a>';
    array_unshift($links, $settings_link);
    return $links;
});
