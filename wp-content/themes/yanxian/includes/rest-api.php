<?php

/**
 * 省市区三级联动
 */
add_action('rest_api_init', function () {
    register_rest_route('wp/v2', '/province_city_district', array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => function ($request) {
            global $wpdb;
            $pid = $request->get_param('pid');
            $table_name = $wpdb->prefix . 'region';

            // 默认条件
            $params = [1];
            $where = 'level = %d';

            if ($pid) {
                $params = [intval($pid)];
                $where = 'parent_id = %d';
            }

            $result = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE $where", ...$params));

            return new WP_REST_Response(compact('result'));
        }
    ));
});