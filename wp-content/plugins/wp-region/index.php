<?php
/*
Plugin Name: CN Region
Description: 中国行政区划数据
Author: Kevin
Version: 1.0
Author URI: mailto:kevin@tigervs.com
*/

require_once plugin_dir_path(__FILE__) . 'manage.php';
require_once plugin_dir_path(__FILE__) . 'import.php';

register_activation_hook(__FILE__, 'wp_region_activate');

function wp_region_activate() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'region';

    // 创建表
    $charset_collate = $wpdb->get_charset_collate();
    $sql = "CREATE TABLE $table_name (
        id VARCHAR(10) NOT NULL,
        name VARCHAR(50) NOT NULL,
        parent_id VARCHAR(10),
        level INT NOT NULL,
        full_path VARCHAR(255) NOT NULL,
        PRIMARY KEY (id),
        UNIQUE KEY (id),
        KEY parent_id (parent_id)
    ) $charset_collate;";
    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    // 导入数据
    $region_json = file_get_contents(plugin_dir_path(__FILE__) . 'region.json');
    $region = json_decode($region_json, true);
    if ($region && is_array($region)) {
        wp_region_insert($region);
    }
}
