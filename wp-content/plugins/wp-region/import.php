<?php
ini_set('memory_limit', '512M');
set_time_limit(300);

$region_json = file_get_contents(plugin_dir_path(__FILE__) . 'region.json');
$region = json_decode($region_json, true);

/**
 * 插入数据
 * @param array $data 数据
 * @param string $parent_id 父级ID
 * @param int $level 层级
 * @param string $path 路径（用中文逗号分隔）
 * @param bool $skip_city_level 是否跳过市辖区
 */
function wp_region_insert($data, $parent_id = null, $level = 1, $path = '', $skip_city_level = false) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'region';
    // 直辖市省级ID
    $direct_city_ids = ['710000', '810000', '820000', '110000', '120000', '310000', '500000'];
    foreach ($data as $id => $item) {
        if (is_string($item)) {
            $full_path = $path ? $path . $item : $item;
            $wpdb->insert($table_name, [
                'id' => $id,
                'name' => $item,
                'level' => $level,
                'parent_id' => $parent_id,
                'full_path' => $full_path
            ]);
        } elseif (is_array($item)) {
            // 判断是否为直辖市的"市辖区"节点
            $is_direct_city = in_array($parent_id, $direct_city_ids);
            $is_city_level = isset($item['name']) && ($item['name'] === '市辖区' || $item['name'] === '省辖区' || $item['name'] === '辖区');
            if ($is_direct_city && $is_city_level) {
                $this_path = $path;
            } else {
                $this_path = $path ? $path . $item['name'] : $item['name'];
            }
            $next_level = $level + 1;
            $wpdb->insert($table_name, [
                'id' => $id,
                'level' => $level,
                'name' => $item['name'],
                'parent_id' => $parent_id,
                'full_path' => $this_path
            ]);
            if (isset($item['child'])) {
                wp_region_insert($item['child'], $id, $next_level, $this_path, false);
            }
        }
    }
}