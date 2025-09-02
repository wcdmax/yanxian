<?php

require_once 'class-send-email.php';

// 添加管理菜单
add_action('admin_menu', function () {
    add_menu_page(
        '表单管理',           // 页面标题
        '表单',               // 菜单标题
        'manage_options',     // 权限
        'form-management',    // 菜单slug
        'render_form_page',   // 回调函数
        'dashicons-feedback', // 图标
        30                    // 位置
    );
});

// 渲染表单管理页面
function render_form_page()
{
    global $wpdb;
    $per_page = 20;
    $table_name = $wpdb->prefix . 'contact_forms';

    // 获取当前页码
    $current_page = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;

    // 获取排序参数
    $orderby = isset($_GET['orderby']) ? $_GET['orderby'] : 'created_at';
    $order = isset($_GET['order']) ? $_GET['order'] : 'DESC';

    // 验证并限制排序字段
    $allowed_orderby = ['id', 'created_at'];
    if (!in_array($orderby, $allowed_orderby)) {
        $orderby = 'created_at';
    }

    // 验证排序方向
    $order = strtoupper($order) === 'ASC' ? 'ASC' : 'DESC';

    // 计算偏移量
    $offset = ($current_page - 1) * $per_page;

    // 获取总记录数
    $total_items = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
    $total_pages = ceil($total_items / $per_page);

    // 构建排序URL
    function build_sorting_url($column)
    {
        $current_orderby = isset($_GET['orderby']) ? $_GET['orderby'] : 'created_at';
        $current_order = isset($_GET['order']) ? $_GET['order'] : 'DESC';

        $new_order = ($current_orderby === $column && $current_order === 'DESC') ? 'ASC' : 'DESC';
        $base_url = admin_url('admin.php?page=form-management');

        return add_query_arg([
            'orderby' => $column,
            'order' => $new_order,
            'paged' => isset($_GET['paged']) ? $_GET['paged'] : 1
        ], $base_url);
    }

    // 获取排序后的数据
    $items = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM $table_name ORDER BY $orderby $order LIMIT %d OFFSET %d",
        $per_page,
        $offset
    ));

    // 添加排序指示器的CSS
    echo '<style>
        .sorted.asc:after { content: " ↑"; }
        .sorted.desc:after { content: " ↓"; }
    </style>';
    global $wpdb;
    $table_name = $wpdb->prefix . 'contact_forms';

    // 处理批量操作
    if (isset($_POST['action']) && isset($_POST['bulk-delete-nonce']) && wp_verify_nonce($_POST['bulk-delete-nonce'], 'bulk-delete')) {
        $ids = isset($_POST['delete_ids']) ? array_map('intval', $_POST['delete_ids']) : array();

        if (!empty($ids)) {
            switch ($_POST['action']) {
                case 'delete':
                    // 移到回收站
                    $wpdb->query(
                        $wpdb->prepare(
                            "UPDATE $table_name SET deleted_at = %s WHERE id IN (" . implode(',', $ids) . ")",
                            current_time('mysql')
                        )
                    );
                    echo '<div class="notice notice-success"><p>已将选中项移至回收站</p></div>';
                    break;

                case 'restore':
                    // 从回收站恢复
                    $wpdb->query(
                        "UPDATE $table_name SET deleted_at = NULL WHERE id IN (" . implode(',', $ids) . ")"
                    );
                    echo '<div class="notice notice-success"><p>已恢复选中项</p></div>';
                    break;

                case 'destroy':
                    // 永久删除
                    $wpdb->query(
                        "DELETE FROM $table_name WHERE id IN (" . implode(',', $ids) . ")"
                    );
                    echo '<div class="notice notice-success"><p>已永久删除选中项</p></div>';
                    break;
            }
        }
    }

    // 处理删除操作
    if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $wpdb->update(
            $table_name,
            ['deleted_at' => current_time('mysql')],
            ['id' => $id],
            ['%s'],
            ['%d']
        );
        echo '<div class="notice notice-success"><p>记录已删除</p></div>';
    }

    // 处理恢复操作
    if (isset($_GET['action']) && $_GET['action'] == 'restore' && isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $wpdb->update(
            $table_name,
            ['deleted_at' => null],
            ['id' => $id],
            ['%s'],
            ['%d']
        );
        echo '<div class="notice notice-success"><p>记录已恢复</p></div>';
    }

    // 处理销毁操作
    if (isset($_GET['action']) && $_GET['action'] == 'destroy' && isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $wpdb->delete(
            $table_name,
            ['id' => $id],
            ['%d']
        );
        echo '<div class="notice notice-success"><p>记录已永久删除</p></div>';
    }

    // 获取所有表单数据
    $items = $wpdb->get_results("SELECT * FROM $table_name ORDER BY created_at DESC");
    // 检查是否在回收站模式
    $is_trash = isset($_GET['view']) && $_GET['view'] === 'trash';

    // 构建基础查询
    $base_query = "SELECT * FROM $table_name WHERE ";
    $base_query .= $is_trash ? "deleted_at IS NOT NULL" : "deleted_at IS NULL";

    // 添加搜索条件
    if (isset($_GET['s']) && !empty($_GET['s'])) {
        $search = '%' . $wpdb->esc_like($_GET['s']) . '%';
        $base_query .= $wpdb->prepare(
            " AND (name LIKE %s OR phone LIKE %s)",
            $search,
            $search
        );
    }

    // 添加排序
    $base_query .= " ORDER BY $orderby $order";

    // 添加分页
    $base_query .= $wpdb->prepare(" LIMIT %d OFFSET %d", $per_page, $offset);

    // 获取数据
    $items = $wpdb->get_results($base_query);

    // 添加回收站/列表视图切换链接
    $list_url = add_query_arg(['page' => 'form-management'], admin_url('admin.php'));
    $trash_url = add_query_arg(['page' => 'form-management', 'view' => 'trash'], admin_url('admin.php'));

?>
    <div class="wrap">
        <h1 class="wp-heading-inline"><?php echo get_admin_page_title(); ?></h1>
        <hr class="wp-header-end">
        <ul class="subsubsub">
            <li class="all">
                <a href="<?php echo $list_url; ?>" class="<?php echo !$is_trash ? 'current' : ''; ?>">所有<span>(<?php echo $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE deleted_at IS NULL"); ?>)</span></a> |
            </li>
            <li class="trash">
                <a href="<?php echo $trash_url; ?>" class="<?php echo $is_trash ? 'current' : ''; ?>">回收站<span>(<?php echo $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE deleted_at IS NOT NULL"); ?>)</span></a>
            </li>
        </ul>
        <!-- 搜索表单 -->
        <form method="get" action="">
            <input type="hidden" name="page" value="form-management">
            <p class="search-box">
                <input type="search" name="s" value="<?php echo isset($_GET['s']) ? esc_attr($_GET['s']) : ''; ?>" placeholder="搜索姓名或手机号">
                <input type="submit" class="button" value="搜索">
            </p>
        </form>

        <form method="post">
            <?php wp_nonce_field('bulk-delete', 'bulk-delete-nonce'); ?>
            <div class="tablenav top">
                <div class="alignleft actions bulkactions">
                    <select name="action">
                        <option value="-1">批量操作</option>
                        <?php if ($is_trash): ?>
                            <option value="restore">恢复</option>
                            <option value="destroy">销毁</option>
                        <?php else: ?>
                            <option value="delete">删除</option>
                        <?php endif; ?>
                    </select>
                    <input type="submit" class="button action" value="应用">
                </div>
            </div>

            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <td class="manage-column column-cb check-column">
                            <input type="checkbox" id="cb-select-all-1">
                        </td>
                        <th>
                            <a href="<?php echo build_sorting_url('id'); ?>" class="<?php echo $orderby === 'id' ? 'sorted ' . strtolower($order) : 'sortable desc'; ?>">
                                <span>ID</span>
                            </a>
                        </th>
                        <th>姓名</th>
                        <th>电话</th>
                        <th>地址</th>
                        <th>留言内容</th>
                        <th>来源页面</th>
                        <th>IP地址</th>
                        <th>
                            <a href="<?php echo build_sorting_url('created_at'); ?>" class="<?php echo $orderby === 'created_at' ? 'sorted ' . strtolower($order) : 'sortable desc'; ?>">
                                <span>提交时间</span>
                            </a>
                        </th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $item): ?>
                        <tr>
                            <th scope="row" class="check-column">
                                <input type="checkbox" name="delete_ids[]" value="<?php echo $item->id; ?>">
                            </th>
                            <td><?php echo $item->id; ?></td>
                            <td><?php echo esc_html($item->name); ?></td>
                            <td><?php echo esc_html($item->phone); ?></td>
                            <td><?php echo esc_html($item->address); ?></td>
                            <td><?php echo esc_html($item->message); ?></td>
                            <td><?php echo esc_html($item->path); ?></td>
                            <td><?php echo esc_html($item->ip); ?></td>
                            <td><?php echo $item->created_at; ?></td>
                            <td>
                                <button type="button"
                                    onclick="showDetail(<?php echo htmlspecialchars(json_encode($item), ENT_QUOTES, 'UTF-8'); ?>)"
                                    class="button button-small">查看</button>
                                <?php if ($is_trash): ?>
                                    <a href="<?php echo admin_url('admin.php?page=form-management&action=destroy&id=' . $item->id); ?>"
                                        onclick="return confirm('确定要永久删除这条记录吗?')"
                                        class="button button-small button-danger">销毁</a>
                                    <a href="<?php echo admin_url('admin.php?page=form-management&action=restore&id=' . $item->id); ?>"
                                        class="button button-small">恢复</a>
                                <?php else: ?>
                                    <a href="<?php echo admin_url('admin.php?page=form-management&action=delete&id=' . $item->id); ?>"
                                        onclick="return confirm('确定要删除这条记录吗?')"
                                        class="button button-small button-danger">删除</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </form>
    </div>

    <!-- 在页面底部添加模态框 -->
    <div id="detail-modal" style="display:none;" class="modal">
        <div class="modal-content" style="background:#fff; padding:20px; max-width:600px; margin:35vh auto; position:relative;">
            <span class="dashicons dashicons-dismiss" style="position:absolute; right:10px; top:10px; cursor:pointer;"></span>
            <h2>记录详情</h2>
            <dl class="detail-list">
                <dt>姓名</dt>
                <dd id="modal-name"></dd>

                <dt>电话</dt>
                <dd id="modal-phone"></dd>

                <dt>地址</dt>
                <dd id="modal-address"></dd>

                <dt>IP地址</dt>
                <dd id="modal-ip"></dd>

                <dt>提交时间</dt>
                <dd id="modal-created-at"></dd>

                <dt>留言内容</dt>
                <dd id="modal-message"></dd>

                <dt>来源页面</dt>
                <dd id="modal-path"></dd>
            </dl>
        </div>
    </div>

    <style>
        .tablenav {
            margin-bottom: 10px;
        }

        .modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            z-index: 999;
            display: none;
            /* 使用 flex 布局实现居中 */
            display: none;
            align-items: center;
            justify-content: center;
        }

        .button-danger {
            color: #b32d2e !important;
            border-color: #b32d2e !important;
        }

        .modal-content {
            /* 移除 fixed 定位和 transform，改用 flex 布局居中 */
            position: relative;
            background: #fff;
            padding: 20px;
            max-width: 600px;
            width: 90%;
            border-radius: 4px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        .detail-list {
            margin: 0;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 4px;
        }

        .detail-list dt {
            float: left;
            width: 80px;
            font-weight: bold;
            color: #666;
        }

        .detail-list dd {
            margin-left: 100px;
            margin-bottom: 10px;
        }

        .detail-list dd:last-child {
            margin-bottom: 0;
        }
    </style>

    <script>
        function showDetail(item) {
            document.getElementById('modal-ip').textContent = item.ip;
            document.getElementById('modal-name').textContent = item.name;
            document.getElementById('modal-path').textContent = item.path;
            document.getElementById('modal-phone').textContent = item.phone;
            document.getElementById('modal-address').textContent = item.address;
            document.getElementById('modal-message').textContent = item.message;
            document.getElementById('modal-created-at').textContent = item.created_at;

            document.getElementById('detail-modal').style.display = 'block';
        }

        // 关闭模态框
        document.querySelector('.dashicons-dismiss').onclick = function() {
            document.getElementById('detail-modal').style.display = 'none';
        }

        // 点击模态框外部关闭
        window.onclick = function(event) {
            if (event.target == document.getElementById('detail-modal')) {
                document.getElementById('detail-modal').style.display = 'none';
            }
        }
    </script>
<?php
}

/**
 * 生成nonce
 */
add_action('wp_enqueue_scripts', function () {
    // 引入脚本
    wp_enqueue_script(
        'post-form',
        '//static.yxtouch.com/assets/js/form.js',
        array(),
        get_bloginfo('version'),
        true
    );

    // nonce传参
    wp_localize_script('post-form', 'yx', array(
        'site_name' => get_bloginfo('name'),
        'nonce' => wp_create_nonce('yx_nonce'),
        'phone' => get_option('company_phone'),
        'email' => get_option('company_email'),
        'wechat' => get_option('company_wechat'),
        'ajaxurl' => admin_url('admin-ajax.php'),
        'switchboard' => get_option('company_400'),
        'site_desc' => get_bloginfo('description'),
        'address' => get_option('company_address'),
    ));
});

add_action('wp_ajax_handle_contact_form', 'handle_contact_form');
add_action('wp_ajax_nopriv_handle_contact_form', 'handle_contact_form');

/**
 * 处理表单数据
 */
function handle_contact_form()
{
    global $wpdb;
    $ip = $_SERVER['REMOTE_ADDR'];
    $nowTime = wp_date('Y-m-d H:i:s');

    if (!is_ssl()) {
        wp_send_json_error(['code' => 400, 'message' => 'Rrequest is sent over HTTPS']);
    } elseif (!is_post_method()) {
        wp_send_json_error(['code' => 400, 'message' => 'Method validation error']);
    } elseif (!wp_verify_nonce($_POST['nonce'], 'yx_nonce')) {
        wp_send_json_error(['code' => 400, 'message' => 'Nonce validation error']);
    }

    // 获取POST数据
    $city = sanitize_text_field($_POST['city'] ?? '');           // 城市
    $province = sanitize_text_field($_POST['province'] ?? '');   // 省份
    $district = sanitize_text_field($_POST['district'] ?? '');   // 辖区

    $name = sanitize_text_field($_POST['name'] ?? '');           // 姓名
    $path = sanitize_text_field($_POST['path'] ?? '');           // 路径
    $phone = sanitize_text_field($_POST['phone'] ?? '');         // 手机
    $message = sanitize_text_field($_POST['message'] ?? '');     // 留言

    // 验证手机号码
    if (!preg_match('/^1[3-9]\d{9}$/', $phone)) {
        wp_send_json_error(['code' => 400, 'message' => '手机号码格式错误']);
    }

    // 验证非空字段
    $requiredFields = ['name', 'phone', 'province', 'city', 'district', 'message'];
    foreach ($requiredFields as $field) {
        if (empty(${$field})) {
            wp_send_json_error(['code' => 400, 'message' => $field . '不能为空']);
        }
    }

    // 从辖区查询地址
    $address = $wpdb->get_var($wpdb->prepare("SELECT full_path FROM {$wpdb->prefix}region WHERE id = %d", $district));

    // 保存表单数据
    $data = [
        'ip'            => $ip,
        'path'          => $path,
        'name'          => $name,
        'phone'         => $phone,
        'message'       => $message,
        'created_at'    => $nowTime,
        'address'       => $address ?: "{$province}{$city}{$district}",
    ];

    send_inquiry_sms($data); // 发送短信

    // 保存数据
    $result = $wpdb->insert($wpdb->prefix . 'contact_forms', $data);

    if ($result && send_inquiry_email($data, $nowTime)) {
        wp_send_json_success(['code' => 200, 'message' => '提交留言成功，我们将在5分钟与您联系']);
    } else {
        wp_send_json_error(['code' => 400, 'message' => '提交留言失败，请检查并重新填写表单项']);
    }
}

/**
 * 发送询盘短信通知
 * @param array $post_data
 * @return void
 */
function send_inquiry_sms(array $post_data): void
{
    $templateParams = array(
        'name' => $post_data['name'],
        'address' => $post_data['address']
    );

    $sms = new SMS_Aliyun($templateParams);
    $sms->sendSmsRequest(); // 发送短信
}

/**
 * 发送询盘邮件通知
 * @param array $post_data
 * @param string $nowTime
 * @return bool
 */
function send_inquiry_email(array $post_data, string $nowTime): bool
{
    // 构建邮件内容
    $content =
        /** @lang text */
        <<<TEMPLATE
    <p><b>姓名：</b>{$post_data['name']}</p>
    <p><b>电话：</b>{$post_data['phone']}</p>
    <p><b>I P：</b>{$post_data['ip']}</p>
    <p><b>时间：</b>{$nowTime}</p>
    <p><b>地区：</b>{$post_data['address']}</p>
    <p><b>来源：</b>{$post_data['path']}</p>
    <p><b>内容：</b>{$post_data['message']}</p>
    TEMPLATE;

    // 发送邮件
    $sendEmail = new SendEmail();
    return $sendEmail->send_inquiry_email('新询盘通知 - ' . $post_data['name'], $content);
}
