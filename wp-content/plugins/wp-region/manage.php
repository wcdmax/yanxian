<?php
add_action('admin_menu', 'wp_region_admin_menu');
add_action('admin_enqueue_scripts', 'wp_region_admin_style');
add_action('admin_enqueue_scripts', 'wp_region_admin_script');
add_action('wp_ajax_wp_region_get_children', 'wp_region_ajax_get_children');

function wp_region_admin_menu() {
    add_options_page(
        '区域管理', // 页面标题
        '区域管理', // 菜单标题
        'manage_options', // 权限
        'wp-region', // 菜单slug
        'wp_region_manage_page', // 回调函数
        5
    );
}

function wp_region_admin_style($hook) {
    if ($hook === 'settings_page_wp-region') {
        wp_enqueue_style('wp-region-admin', plugins_url('assets/css/style.css', __FILE__));
    }
}

function wp_region_admin_script($hook) {
    if ($hook === 'settings_page_wp-region') {
        wp_enqueue_script('wp-region-admin', plugins_url('assets/js/region.js', __FILE__), [], false, true);
        wp_localize_script('wp-region-admin', 'wpRegionAjax', [
            'ajaxurl' => admin_url('admin-ajax.php'),
        ]);
    }
}

function wp_region_ajax_get_children() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'region';
    $parent_id = isset($_POST['parent_id']) ? sanitize_text_field($_POST['parent_id']) : '';
    $search_kw = isset($_POST['search_kw']) ? sanitize_text_field($_POST['search_kw']) : '';
    if ($search_kw !== '') {
        $like = '%' . $wpdb->esc_like($search_kw) . '%';
        $regions = $wpdb->get_results($wpdb->prepare(
            "SELECT * FROM $table_name WHERE name LIKE %s OR id = %s OR parent_id = %s ORDER BY id",
            $like, $search_kw, $search_kw
        ));
    } else if ($parent_id === '' || $parent_id === '0') {
        $regions = $wpdb->get_results("SELECT * FROM $table_name WHERE level = 1 ORDER BY id");
    } else {
        $regions = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE parent_id = %s ORDER BY id", $parent_id));
    }
    wp_send_json($regions);
}

function wp_region_manage_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'region';

    // 处理删改/新增
    if (isset($_POST['action']) && $_POST['action'] === 'edit') {
        $wpdb->update($table_name, [
            'name' => sanitize_text_field($_POST['name']),
            'parent_id' => sanitize_text_field($_POST['parent_id']),
            'level' => intval($_POST['level']),
            'full_path' => sanitize_text_field($_POST['full_path'])
        ], ['id' => sanitize_text_field($_POST['id'])]);
        echo '<div class="updated"><p>修改成功</p></div>';
    }
    if (isset($_POST['action']) && $_POST['action'] === 'add') {
        $wpdb->insert($table_name, [
            'name' => sanitize_text_field($_POST['name']),
            'parent_id' => sanitize_text_field($_POST['parent_id']),
            'level' => intval($_POST['level']),
            'full_path' => sanitize_text_field($_POST['full_path'])
        ]);
        echo '<div class="updated"><p>新增成功</p></div>';
    }
    if (isset($_GET['delete'])) {
        $wpdb->delete($table_name, ['id' => sanitize_text_field($_GET['delete'])]);
        echo '<div class="updated"><p>删除成功</p></div>';
    }

    // 搜索参数
    $search_kw = isset($_GET['search_kw']) ? sanitize_text_field($_GET['search_kw']) : '';

    // 查询所有区域用于父级选择
    $all_regions = $wpdb->get_results("SELECT id, name, level, full_path FROM $table_name");

    ?>
    <div class="wrap">
        <h1>区域管理</h1>
        <div class="tablenav top">
            <div class="alignleft actions bulkactions">
                <form method="get" id="search-form">
                    <input type="hidden" name="page" value="wp-region">
                    <input type="text" name="search_kw" placeholder="输入名称或ID搜索" value="<?php echo esc_attr($search_kw); ?>">
                    <input type="submit" class="button" value="搜索">
                    <a href="?page=wp-region" class="button button-reset">重置</a>
                </form>
            </div>
            <div class="alignright">
                <a href="#" id="add-region-btn" class="button">新增</a>
            </div>
        </div>
        <div id="region-tree"></div>
        <div id="edit-modal" style="display:none;">
            <div class="edit-modal-inner">
                <span class="dashicons edit-modal-close dashicons-dismiss"></span>
                <h2 id="modal-title">编辑区域</h2>
                <form method="post" autocomplete="off" id="region-form">
                    <input type="hidden" name="action" id="edit-action" value="edit">
                    <table class="form-table">
                        <tr id="id-row">
                            <th scope="row">ID</th>
                            <td><input required id="edit-id" name="id" value="" class="regular-text"></td>
                        </tr>
                        <tr>
                            <th scope="row">名称</th>
                            <td><input required id="edit-name" name="name" value="" class="regular-text"></td>
                        </tr>
                        <tr>
                            <th scope="row">父级</th>
                            <td style="position:relative;">
                                <input id="edit-parent_name" type="text" class="regular-text" placeholder="输入父级名称或ID搜索" autocomplete="off">
                                <input required type="hidden" id="edit-parent_id" name="parent_id" value="">
                                <ul id="parent-suggest-list"></ul>
                                <p class="description" id="tagline-description">支持输入ID或名称搜索</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row">层级</th>
                            <td><input readonly required id="edit-level" name="level" type="number" min="1" value="" class="regular-text"></td>
                        </tr>
                        <tr>
                            <th scope="row">全路径</th>
                            <td><input readonly required id="edit-full_path" name="full_path" value="" class="regular-text"></td>
                        </tr>
                    </table>
                    <div class="edit-modal-footer">
                        <input type="submit" class="button button-medium" id="modal-save-btn" value="保存">
                        <input type="reset" class="button button-medium button-reset" value="重置">
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
    window.regionList = <?php echo json_encode(array_map(function($r){return ['id'=>$r->id,'name'=>$r->name,'level'=>$r->level,'full_path'=>$r->full_path];}, $all_regions), JSON_UNESCAPED_UNICODE); ?>;
    </script>
    <?php
}
