<?php

// 注意推送记录菜单
add_action('admin_menu', function() {
    add_options_page(
        '推送记录',
        '推送记录',
        'manage_options',
        'url-push',
        'url_push_admin_page',
        5
    );
});

// 辅助函数：过滤空值参数
function filter_empty_params($params) {
    return array_filter($params, function($value) {
        return $value !== '' && $value !== null;
    });
}

// 显示推送记录页面
function url_push_admin_page() {
    global $wpdb;
    $per_page = 15;
    $table_name = $wpdb->prefix . 'url_push';
    $paged = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
    $offset = ($paged - 1) * $per_page;

    // 状态筛选
    $status = isset($_GET['status']) ? sanitize_text_field($_GET['status']) : '';

    // 平台筛选
    $platforms = $wpdb->get_col("SELECT DISTINCT platform FROM $table_name");
    $platform = isset($_GET['platform']) ? sanitize_text_field($_GET['platform']) : '';

    // 日期筛选
    $where = '1=1';
    $where_date = '1=1';
    $start_date = isset($_GET['start_date']) ? sanitize_text_field($_GET['start_date']) : '';
    $end_date = isset($_GET['end_date']) ? sanitize_text_field($_GET['end_date']) : '';
    if ($start_date) {
        $where .= $wpdb->prepare(" AND create_time >= %s", $start_date . ' 00:00:00');
        $where_date .= $wpdb->prepare(" AND create_time >= %s", $start_date . ' 00:00:00');
    }
    if ($end_date) {
        $where .= $wpdb->prepare(" AND create_time <= %s", $end_date . ' 23:59:59');
        $where_date .= $wpdb->prepare(" AND create_time <= %s", $end_date . ' 23:59:59');
    }
    if ($platform) {
        $where .= $wpdb->prepare(" AND platform = %s", $platform);
        $where_date .= $wpdb->prepare(" AND platform = %s", $platform);
    }
    if ($status === 'success') {
        $where .= " AND success = 1";
    } elseif ($status === 'fail') {
        $where .= " AND success = 0";
    }

    // 只受日期影响的where_date_only
    $where_date_only = '1=1';
    if ($start_date) {
        $where_date_only .= $wpdb->prepare(" AND create_time >= %s", $start_date . ' 00:00:00');
    }
    if ($end_date) {
        $where_date_only .= $wpdb->prepare(" AND create_time <= %s", $end_date . ' 23:59:59');
    }

    // 全部总数（不受任何筛选影响）
    $total_all = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
    // 用where_date_only计算下拉全部计数
    $total_all_date = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE $where_date_only");
    $total_fail = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE $where_date AND success=0");
    $total_success = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE $where_date AND success=1");

    $total = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE $where");
    $results = $wpdb->get_results($wpdb->prepare("SELECT * FROM $table_name WHERE $where ORDER BY id DESC LIMIT %d OFFSET %d", $per_page, $offset));
    $total_pages = ceil($total / $per_page);

    $base_url = admin_url('options-general.php?page=url-push');
    
    // 构建基础查询参数，过滤空值
    $base_query_args = filter_empty_params([
        'start_date' => $start_date,
        'end_date' => $end_date,
        'platform' => $platform
    ]);

    // 各平台统计
    $platform_counts = [];
    foreach ($platforms as $p) {
        $count = $wpdb->get_var(
            $wpdb->prepare(
                "SELECT COUNT(*) FROM $table_name WHERE platform = %s" .
                ($start_date ? $wpdb->prepare(" AND create_time >= %s", $start_date . ' 00:00:00') : '') .
                ($end_date ? $wpdb->prepare(" AND create_time <= %s", $end_date . ' 23:59:59') : ''),
                $p
            )
        );
        $platform_counts[$p] = $count;
    }

    ob_start();
    ?>
    <div class="wrap">
        <h1>推送记录</h1>
        <p class="description">
            发布文档时自动向搜索引擎推送URL的记录，目前只支持百度、必应搜索引擎。
        </p>
        <ul class="subsubsub yk-subsubsub">
            <?php
            // 构建状态筛选URL，过滤空值
            $all_url = add_query_arg($base_query_args, $base_url);
            $fail_url = add_query_arg(filter_empty_params(array_merge($base_query_args, ['status' => 'fail'])), $base_url);
            $success_url = add_query_arg(filter_empty_params(array_merge($base_query_args, ['status' => 'success'])), $base_url);
            ?>
            <li><a href="<?php echo esc_url($all_url); ?>"<?php if ($status === '') echo ' class="current"'; ?>>
                全部 <span>(<?php echo intval($total_all); ?>)</span>
            </a> |</li>
            <li><a href="<?php echo esc_url($success_url); ?>"<?php if ($status === 'success') echo ' class="current"'; ?>>
                推送成功 <span class="push_success">(<?php echo intval($total_success); ?>)</span>
            </a> |</li>
            <li><a href="<?php echo esc_url($fail_url); ?>"<?php if ($status === 'fail') echo ' class="current"'; ?>>
                推送失败 <span class="push_fail">(<?php echo intval($total_fail); ?>)</span>
            </a></li>
        </ul>
        <form method="get" class="yk-form" onsubmit="return removeEmptyFields(this);">
            <input type="hidden" name="page" value="url-push">
            <?php if ($status): ?>
                <input type="hidden" name="status" value="<?php echo esc_attr($status); ?>">
            <?php endif; ?>
            <label>
                <select name="platform" onchange="removeEmptyFieldsAndSubmit(this.form);">
                    <option value="">全部（<?php echo intval($total_all_date); ?>）</option>
                    <?php foreach ($platforms as $p): ?>
                        <option value="<?php echo esc_attr($p); ?>"<?php selected($platform, $p); ?>>
                            <?php echo esc_html($p); ?>（<?php echo intval($platform_counts[$p]); ?>）
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label>开始日期：<input type="date" name="start_date" value="<?php echo esc_attr($start_date); ?>"></label>
            <label>结束日期：<input type="date" name="end_date" value="<?php echo esc_attr($end_date); ?>"></label>
            <input type="submit" class="button" value="筛选">
            <a href="<?php echo esc_url($base_url); ?>" class="button">重置</a>
        </form>

        <script>
        function removeEmptyFields(form) {
            // 获取所有表单元素
            const elements = form.elements;
            
            // 遍历所有元素，移除空值字段
            for (let i = elements.length - 1; i >= 0; i--) {
                const element = elements[i];
                if ((element.type === 'text' || element.type === 'date' || element.type === 'select-one') && 
                    element.value === '') {
                    element.removeAttribute('name');
                }
            }
            return true;
        }
        
        function removeEmptyFieldsAndSubmit(form) {
            removeEmptyFields(form);
            form.submit();
        }
        </script>

        <?php if ($results): ?>
            <table class="widefat fixed striped">
                <thead>
                    <tr>
                        <th class="yk-table-id">ID</th>
                        <th class="yk-table-url">网址</th>
                        <th>推送平台</th>
                        <th>推送结果</th>
                        <th>当天剩余可推送</th>
                        <th>推送时间</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($results as $row): ?>
                    <tr>
                        <td class="yk-table-id"><?php echo esc_html($row->id); ?></td>
                        <td class="yk-table-url"><?php echo esc_html($row->url); ?></td>
                        <td>
                            <?php
                            $icon_url = match ($row->platform) {
                                'baidu' => plugins_url('assets/icon/baidu.png', __FILE__),
                                'bing' => plugins_url('assets/icon/bing.png', __FILE__),
                                default => plugins_url('assets/icon/unknown.png', __FILE__)
                            };
                            ?>
                            <img alt="<?php echo esc_attr($row->platform); ?>" title="<?php echo esc_attr($row->platform); ?>" class="yk-platform-icon" src="<?php echo esc_url($icon_url); ?>">
                        </td>
                        <td><?php echo $row->success ? '成功' : '失败'; ?></td>
                        <td><?php echo esc_html($row->remain); ?></td>
                        <td><?php echo esc_html($row->create_time); ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <?php if ($total_pages > 1): ?>
                <div class="tablenav">
                    <div class="tablenav-pages">
                        <span class="displaying-num"><?php echo intval($total); ?> 项</span>
                        <span class="pagination-links">
                            <?php
                            // 首页、上一页
                            if ($paged > 1) {
                                $first_params = filter_empty_params(array_merge(['paged' => 1, 'status' => $status], $base_query_args));
                                $prev_params = filter_empty_params(array_merge(['paged' => $paged - 1, 'status' => $status], $base_query_args));
                                $first_url = add_query_arg($first_params, $base_url);
                                $prev_url = add_query_arg($prev_params, $base_url);
                                ?>
                                <a class="first-page button" href="<?php echo esc_url($first_url); ?>"><span class="screen-reader-text">首页</span><span aria-hidden="true">«</span></a>
                                <a class="prev-page button" href="<?php echo esc_url($prev_url); ?>"><span class="screen-reader-text">上一页</span><span aria-hidden="true">‹</span></a>
                                <?php
                            } else {
                                ?>
                                <span class="tablenav-pages-navspan button disabled" aria-hidden="true">«</span>
                                <span class="tablenav-pages-navspan button disabled" aria-hidden="true">‹</span>
                                <?php
                            }
                            ?>
                            <span class="screen-reader-text">当前页</span>
                            <span id="table-paging" class="paging-input">
                                <span class="tablenav-paging-text">
                                    第 <?php echo $paged; ?> 页，共 <span class="total-pages"><?php echo $total_pages; ?></span> 页
                                </span>
                            </span>
                            <?php
                            // 下一页、尾页
                            if ($paged < $total_pages) {
                                $next_params = filter_empty_params(array_merge(['paged' => $paged + 1, 'status' => $status], $base_query_args));
                                $last_params = filter_empty_params(array_merge(['paged' => $total_pages, 'status' => $status], $base_query_args));
                                $next_url = add_query_arg($next_params, $base_url);
                                $last_url = add_query_arg($last_params, $base_url);
                                ?>
                                <a class="next-page button" href="<?php echo esc_url($next_url); ?>"><span class="screen-reader-text">下一页</span><span aria-hidden="true">›</span></a>
                                <a class="last-page button" href="<?php echo esc_url($last_url); ?>"><span class="screen-reader-text">尾页</span><span aria-hidden="true">»</span></a>
                                <?php
                            } else {
                                ?>
                                <span class="tablenav-pages-navspan button disabled" aria-hidden="true">›</span>
                                <span class="tablenav-pages-navspan button disabled" aria-hidden="true">»</span>
                                <?php
                            }
                            ?>
                        </span>
                    </div>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <p>暂无推送记录。</p>
        <?php endif; ?>
    </div>
    <?php
    echo ob_get_clean();
}