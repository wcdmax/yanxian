<?php
/**
 * Plugin Name: Cache Validator (Must Use)
 * Description: 在WordPress环境中验证缓存状态
 * Version: 1.0
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}

// 添加管理员菜单
add_action('admin_menu', function() {
    add_management_page(
        'Memcached',
        'Memcached',
        'manage_options',
        'cache-validator',
        'cache_validator_page',
        4
    );
});

// 引入CSS样式表
add_action('admin_enqueue_scripts', function($hook) {
    if ($hook === 'tools_page_cache-validator') {
        wp_enqueue_style(
            'cache-validator-styles',
            plugin_dir_url(__FILE__) . 'cache-validator.css',
            array(),
            '1.0.0'
        );
    }
});

// 创建验证页面
function cache_validator_page() {
    ?>
    <div class="wrap cache-validator">
        <h1>🔍 WordPress Cache Validator</h1>
        <p>在真实的WordPress环境中验证缓存状态</p>
        <?php cache_validator_run_tests(); ?>
    </div>
    <?php
}

function cache_validator_run_tests() {
    ?>
    <div class="notice notice-info">
        <p><strong>检查时间:</strong> <?php echo wp_date('Y-m-d H:i:s'); ?></p>
    </div>

    <table class="wp-list-table widefat fixed striped cache-validator__table">
        <thead>
            <tr>
                <th>OPcache 检查项目</th>
                <th>状态</th>
                <th>详细信息</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // OPcache启用状态
            echo '<tr>';
            echo '<td><strong>OPcache状态</strong></td>';
            $opcache_enabled = function_exists('opcache_get_status');
            echo '<td>' . ($opcache_enabled ? '✅ 启用' : '❌ 未启用') . '</td>';
            echo '<td>' . ($opcache_enabled ? '已加载并启用' : '未安装或未启用') . '</td>';
            echo '</tr>';
            
            if (function_exists('opcache_get_status')) {
                $status = opcache_get_status();
                
                if ($status && is_array($status)) {
                    // 版本信息
                    echo '<tr>';
                    echo '<td><strong>OPcache版本</strong></td>';
                    $reflection = new ReflectionExtension('Zend OPcache');
                    echo '<td>ℹ️ ' . $reflection->getVersion() . '</td>';
                    echo '<td>扩展版本信息</td>';
                    echo '</tr>';
                    
                    // 内存使用信息
                    if (isset($status['memory_usage']['used_memory'], $status['memory_usage']['free_memory'])) {
                        $used = round($status['memory_usage']['used_memory'] / 1024 / 1024, 2);
                        $free = round($status['memory_usage']['free_memory'] / 1024 / 1024, 2);
                        $total = $used + $free;
                        $usage_percent = round(($used / $total) * 100, 1);
                        
                        echo '<tr>';
                        echo '<td><strong>内存使用率</strong></td>';
                        $memory_status = $usage_percent > 90 ? '⚠️' : ($usage_percent > 70 ? '😐' : '✅');
                        echo '<td>' . $memory_status . ' ' . $usage_percent . '%</td>';
                        echo '<td>' . $used . 'MB / ' . $total . 'MB';
                        
                        // 添加优化建议
                        if ($usage_percent > 95) {
                            echo '<br><small class="cache-validator__recommendation cache-validator__recommendation--critical">建议: 增加内存分配或清理缓存</small>';
                        } elseif ($usage_percent > 85 && $usage_percent <= 95) {
                            echo '<br><small class="cache-validator__recommendation cache-validator__recommendation--warning">良好: 使用率在理想范围内</small>';
                        } elseif ($usage_percent < 50) {
                            echo '<br><small class="cache-validator__recommendation cache-validator__recommendation--info">提示: 可考虑减少内存分配</small>';
                        } else {
                            echo '<br><small class="cache-validator__recommendation cache-validator__recommendation--success">最佳: 内存使用效率很好</small>';
                        }
                        
                        echo '</td>';
                        echo '</tr>';
                        
                        // 内存浪费
                        if (isset($status['memory_usage']['wasted_memory'])) {
                            $wasted = round($status['memory_usage']['wasted_memory'] / 1024 / 1024, 2);
                            $wasted_percent = round(($status['memory_usage']['wasted_memory'] / ($used * 1024 * 1024)) * 100, 1);
                            echo '<tr>';
                            echo '<td><strong>内存浪费</strong></td>';
                            $waste_status = $wasted_percent > 20 ? '⚠️' : ($wasted_percent > 10 ? '😐' : '✅');
                            echo '<td>' . $waste_status . ' ' . $wasted_percent . '%</td>';
                            echo '<td>' . $wasted . 'MB 碎片化内存</td>';
                            echo '</tr>';
                        }
                    }
                    
                    // 缓存统计
                    if (isset($status['opcache_statistics'])) {
                        $stats = $status['opcache_statistics'];
                        
                        // 命中率
                        if (isset($stats['hits'], $stats['misses'])) {
                            $total_requests = $stats['hits'] + $stats['misses'];
                            $hit_rate = $total_requests > 0 ? round(($stats['hits'] / $total_requests) * 100, 2) : 0;
                            echo '<tr>';
                            echo '<td><strong>缓存命中率</strong></td>';
                            $hit_status = $hit_rate > 95 ? '✅' : ($hit_rate > 85 ? '😐' : '⚠️');
                            echo '<td>' . $hit_status . ' ' . $hit_rate . '%</td>';
                            echo '<td>命中: ' . number_format($stats['hits']) . ', 未命中: ' . number_format($stats['misses']);
                            
                            // 添加命中率优化建议
                            if ($hit_rate < 70) {
                                echo '<br><small class="cache-validator__recommendation cache-validator__recommendation--critical"><strong>严重:</strong> 建议检查validate_timestamps和revalidate_freq配置</small>';
                            } elseif ($hit_rate < 85) {
                                echo '<br><small class="cache-validator__recommendation cache-validator__recommendation--warning"><strong>需要优化:</strong> 考虑在生产环境关闭时间戳验证</small>';
                            } elseif ($hit_rate < 95) {
                                echo '<br><small class="cache-validator__recommendation cache-validator__recommendation--info"><strong>良好:</strong> 可进一步优化文件部署策略</small>';
                            } else {
                                echo '<br><small class="cache-validator__recommendation cache-validator__recommendation--success"><strong>优秀:</strong> 命中率表现很好</small>';
                            }
                            
                            echo '</td>';
                            echo '</tr>';
                        }
                        
                        // 缓存的脚本数量
                        if (isset($stats['num_cached_scripts'], $stats['max_cached_keys'])) {
                            $script_percent = round(($stats['num_cached_scripts'] / $stats['max_cached_keys']) * 100, 1);
                            echo '<tr>';
                            echo '<td><strong>缓存脚本数</strong></td>';
                            $script_status = $script_percent > 90 ? '⚠️' : ($script_percent > 70 ? '😐' : '✅');
                            echo '<td>' . $script_status . ' ' . $script_percent . '%</td>';
                            echo '<td>' . number_format($stats['num_cached_scripts']) . ' / ' . number_format($stats['max_cached_keys']) . '</td>';
                            echo '</tr>';
                        }
                        
                        // 重启次数汇总
                        $total_restarts = 0;
                        if (isset($stats['oom_restarts'])) $total_restarts += $stats['oom_restarts'];
                        if (isset($stats['hash_restarts'])) $total_restarts += $stats['hash_restarts'];
                        if (isset($stats['manual_restarts'])) $total_restarts += $stats['manual_restarts'];
                        
                        echo '<tr>';
                        echo '<td><strong>重启次数</strong></td>';
                        $restart_status = $total_restarts > 10 ? '⚠️' : ($total_restarts > 0 ? '😐' : '✅');
                        echo '<td>' . $restart_status . ' ' . $total_restarts . ' 次</td>';
                        echo '<td>';
                        $restart_details = [];
                        if (isset($stats['oom_restarts']) && $stats['oom_restarts'] > 0) {
                            $restart_details[] = '内存不足: ' . $stats['oom_restarts'];
                        }
                        if (isset($stats['hash_restarts']) && $stats['hash_restarts'] > 0) {
                            $restart_details[] = '哈希冲突: ' . $stats['hash_restarts'];
                        }
                        if (isset($stats['manual_restarts']) && $stats['manual_restarts'] > 0) {
                            $restart_details[] = '手动重启: ' . $stats['manual_restarts'];
                        }
                        echo empty($restart_details) ? '无重启记录' : implode(', ', $restart_details);
                        echo '</td>';
                        echo '</tr>';
                        
                        // 运行时间
                        if (isset($stats['start_time'])) {
                            $uptime = time() - $stats['start_time'];
                            $uptime_hours = floor($uptime / 3600);
                            echo '<tr>';
                            echo '<td><strong>运行时间</strong></td>';
                            $uptime_status = $uptime_hours > 24 ? '✅' : ($uptime_hours > 1 ? '😐' : '⚠️');
                            echo '<td>' . $uptime_status . ' ' . $uptime_hours . ' 小时</td>';
                            echo '<td>启动于: ' . date('Y-m-d H:i:s', $stats['start_time']) . '</td>';
                            echo '</tr>';
                        }
                        
                        // 最后重启时间
                        if (isset($stats['last_restart_time']) && $stats['last_restart_time'] > 0) {
                            echo '<tr>';
                            echo '<td><strong>最后重启时间</strong></td>';
                            echo '<td>ℹ️ 记录</td>';
                            echo '<td>' . date('Y-m-d H:i:s', $stats['last_restart_time']) . '</td>';
                            echo '</tr>';
                        }
                    }
                    
                    // 字符串缓冲区
                    if (isset($status['interned_strings_usage'])) {
                        $str_usage = $status['interned_strings_usage'];
                        if (isset($str_usage['used_memory'], $str_usage['free_memory'])) {
                            $str_used = round($str_usage['used_memory'] / 1024 / 1024, 2);
                            $str_free = round($str_usage['free_memory'] / 1024 / 1024, 2);
                            $str_total = $str_used + $str_free;
                            $str_percent = $str_total > 0 ? round(($str_used / $str_total) * 100, 1) : 0;
                            
                            echo '<tr>';
                            echo '<td><strong>字符串缓冲区</strong></td>';
                            $str_status = $str_percent > 90 ? '⚠️' : ($str_percent > 70 ? '😐' : '✅');
                            echo '<td>' . $str_status . ' ' . $str_percent . '%</td>';
                            echo '<td>' . $str_used . 'MB / ' . $str_total . 'MB';
                            if (isset($str_usage['number_of_strings'])) {
                                echo ' (' . number_format($str_usage['number_of_strings']) . ' 个字符串)';
                            }
                            echo '</td>';
                            echo '</tr>';
                        }
                    }
                    
                    // 关键配置设置
                    if (isset($status['directives'])) {
                        $directives = $status['directives'];
                        
                        // 内存分配
                        if (isset($directives['opcache.memory_consumption'])) {
                            $memory_mb = round($directives['opcache.memory_consumption'] / 1024 / 1024);
                            echo '<tr>';
                            echo '<td><strong>内存分配配置</strong></td>';
                            $config_status = $memory_mb >= 128 ? '✅' : ($memory_mb >= 64 ? '😐' : '⚠️');
                            echo '<td>' . $config_status . ' ' . $memory_mb . 'MB</td>';
                            echo '<td>opcache.memory_consumption</td>';
                            echo '</tr>';
                        }
                        
                        // 最大文件数
                        if (isset($directives['opcache.max_accelerated_files'])) {
                            echo '<tr>';
                            echo '<td><strong>最大文件数配置</strong></td>';
                            $max_files = $directives['opcache.max_accelerated_files'];
                            $files_status = $max_files >= 10000 ? '✅' : ($max_files >= 4000 ? '😐' : '⚠️');
                            echo '<td>' . $files_status . ' ' . number_format($max_files) . '</td>';
                            echo '<td>opcache.max_accelerated_files</td>';
                            echo '</tr>';
                        }
                        
                        // 时间戳验证
                        if (isset($directives['opcache.validate_timestamps'])) {
                            echo '<tr>';
                            echo '<td><strong>时间戳验证</strong></td>';
                            $validate = $directives['opcache.validate_timestamps'];
                            $env_is_prod = (defined('WP_ENV') && constant('WP_ENV') === 'production') || 
                                          (defined('WP_DEBUG') && !constant('WP_DEBUG'));
                            
                            if ($validate) {
                                echo '<td>⚠️ 启用</td>';
                                echo '<td>opcache.validate_timestamps = 1';
                                if ($env_is_prod) {
                                    echo '<br><small class="cache-validator__recommendation cache-validator__recommendation--warning"><strong>优化建议:</strong> 生产环境建议设为0以提高命中率</small>';
                                } else {
                                    echo '<br><small class="cache-validator__recommendation cache-validator__recommendation--success">开发环境适合启用</small>';
                                }
                            } else {
                                echo '<td>✅ 禁用</td>';
                                echo '<td>opcache.validate_timestamps = 0';
                                echo '<br><small class="cache-validator__recommendation cache-validator__recommendation--success"><strong>最佳:</strong> 生产环境推荐配置，最大化命中率</small>';
                            }
                            echo '</td>';
                            echo '</tr>';
                        }
                        
                        // 重新验证频率
                        if (isset($directives['opcache.revalidate_freq'])) {
                            echo '<tr>';
                            echo '<td><strong>重新验证频率</strong></td>';
                            $freq = $directives['opcache.revalidate_freq'];
                            
                            if ($freq == 0) {
                                echo '<td>✅ ' . $freq . ' 秒</td>';
                                echo '<td>opcache.revalidate_freq = 0';
                                echo '<br><small class="cache-validator__recommendation cache-validator__recommendation--success"><strong>最佳:</strong> 不重新验证，最大化命中率</small>';
                            } elseif ($freq <= 60) {
                                echo '<td>😐 ' . $freq . ' 秒</td>';
                                echo '<td>opcache.revalidate_freq = ' . $freq;
                                echo '<br><small class="cache-validator__recommendation cache-validator__recommendation--warning"><strong>提醒:</strong> 生产环境建议设为0或更高值</small>';
                            } else {
                                echo '<td>ℹ️ ' . $freq . ' 秒</td>';
                                echo '<td>opcache.revalidate_freq = ' . $freq;
                                echo '<br><small class="cache-validator__recommendation cache-validator__recommendation--info">较大的值有助于提高命中率</small>';
                            }
                            echo '</td>';
                            echo '</tr>';
                        }
                    }
                    
                } else {
                    echo '<tr>';
                    echo '<td><strong>OPcache数据</strong></td>';
                    echo '<td>❌ 失败</td>';
                    echo '<td>无法获取OPcache状态信息</td>';
                    echo '</tr>';
                }
            }
            ?>
        </tbody>
    </table>
    
    <table class="wp-list-table widefat fixed striped cache-validator__table">
        <thead>
            <tr>
                <th>Memcached 检查项目</th>
                <th>状态</th>
                <th>详细信息</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // 1. 基本配置检查
            echo '<tr>';
            echo '<td><strong>WP_CACHE</strong></td>';
            $wp_cache_enabled = defined('WP_CACHE') && WP_CACHE;
            echo '<td>' . ($wp_cache_enabled ? '✅ 启用' : '❌ 未启用') . '</td>';
            echo '<td>' . (defined('WP_CACHE') ? 'true' : 'false') . '</td>';
            echo '</tr>';
            
            echo '<tr>';
            echo '<td><strong>WP_CACHE_KEY_SALT</strong></td>';
            $has_salt = defined('WP_CACHE_KEY_SALT');
            echo '<td>' . ($has_salt ? '✅ 已设置' : '❌ 未设置') . '</td>';
            echo '<td>' . ($has_salt ? WP_CACHE_KEY_SALT : 'N/A') . '</td>';
            echo '</tr>';
            
            // 2. 全局缓存对象检查
            global $wp_object_cache;
            echo '<tr>';
            echo '<td><strong>全局缓存对象</strong></td>';
            if (isset($wp_object_cache) && is_object($wp_object_cache)) {
                $cache_class = get_class($wp_object_cache);
                echo '<td>✅ 存在</td>';
                echo '<td>' . $cache_class . '</td>';
            } else {
                echo '<td>❌ 不存在</td>';
                echo '<td>未创建</td>';
            }
            echo '</tr>';
            
            // 3. 连接状态检查
            if (isset($wp_object_cache) && method_exists($wp_object_cache, 'is_connected')) {
                echo '<tr>';
                echo '<td><strong>Memcached连接</strong></td>';
                $connected = $wp_object_cache->is_connected();
                echo '<td>' . ($connected ? '✅ 已连接' : '❌ 未连接') . '</td>';
                echo '<td>' . ($connected ? '正常' : '连接失败') . '</td>';
                echo '</tr>';
            }
            
            // 4. 缓存函数测试
            $functions = ['wp_cache_set', 'wp_cache_get', 'wp_cache_delete'];
            foreach ($functions as $func) {
                echo '<tr>';
                echo '<td><strong>' . $func . '</strong></td>';
                $exists = function_exists($func);
                echo '<td>' . ($exists ? '✅ 存在' : '❌ 不存在') . '</td>';
                echo '<td>' . ($exists ? '函数可用' : '函数缺失') . '</td>';
                echo '</tr>';
            }
            
            // 5. 实际缓存测试
            if (function_exists('wp_cache_set') && function_exists('wp_cache_get')) {
                $test_key = 'wp_admin_test_' . time();
                $test_value = 'WordPress Admin Test: ' . microtime(true);
                
                // 写入测试
                echo '<tr>';
                echo '<td><strong>缓存写入测试</strong></td>';
                $set_result = wp_cache_set($test_key, $test_value, 'default', 300);
                echo '<td>' . ($set_result ? '✅ 成功' : '❌ 失败') . '</td>';
                echo '<td>返回值: ' . var_export($set_result, true) . '</td>';
                echo '</tr>';
                
                // 读取测试
                echo '<tr>';
                echo '<td><strong>缓存读取测试</strong></td>';
                $found = false;
                $get_result = wp_cache_get($test_key, 'default', false, $found);
                $read_success = ($found && $get_result === $test_value);
                echo '<td>' . ($read_success ? '✅ 成功' : '❌ 失败') . '</td>';
                echo '<td>找到: ' . ($found ? 'true' : 'false') . ', 数据匹配: ' . ($get_result === $test_value ? 'true' : 'false') . '</td>';
                echo '</tr>';
                
                // 删除测试
                echo '<tr>';
                echo '<td><strong>缓存删除测试</strong></td>';
                $delete_result = wp_cache_delete($test_key, 'default');
                echo '<td>' . ($delete_result ? '✅ 成功' : '❌ 失败') . '</td>';
                echo '<td>返回值: ' . var_export($delete_result, true) . '</td>';
                echo '</tr>';
            }
            
            // 6. 详细统计信息
            if (isset($wp_object_cache) && method_exists($wp_object_cache, 'stats')) {
                $stats = $wp_object_cache->stats();
                
                // === 新增：Memcached 服务器关键指标 ===
                if (isset($stats['memcached']) && !empty($stats['memcached'])) {
                    echo '<tr class="cache-validator__section-header">';
                    echo '<td colspan="3"><strong class="cache-validator__section-title">📊 Memcached 服务器关键指标</strong></td>';
                    echo '</tr>';
                    
                    foreach ($stats['memcached'] as $server => $server_stats) {
                        // 缓存命中率
                        echo '<tr>';
                        echo '<td><strong>缓存命中率</strong></td>';
                        echo '<td>' . $stats['ratio'] . '%</td>';
                        echo '<td>服务器: ' . $server . '</td>';
                        echo '</tr>';
                        
                        // GET请求数
                        echo '<tr>';
                        echo '<td><strong>GET请求数</strong></td>';
                        if (isset($server_stats['cmd_get'])) {
                            echo '<td>' . number_format($server_stats['get_hits']) . '</td>';
                        } else {
                            echo '<td>❌ N/A</td>';
                        }
                        echo '<td>服务器: ' . $server . '</td>';
                        echo '</tr>';

                        // GET命中次数
                        echo '<tr>';
                        echo '<td><strong>GET命中次数</strong></td>';
                        if (isset($server_stats['get_hits'])) {
                            echo '<td>' . number_format($server_stats['get_hits']) . '</td>';
                        } else {
                            echo '<td>❌ N/A</td>';
                        }
                        echo '<td>服务器: ' . $server . '</td>';
                        echo '</tr>';

                        // GET失败次数
                        echo '<tr>';
                        echo '<td><strong>GET失败次数</strong></td>';
                        if (isset($server_stats['get_misses'])) {
                            echo '<td>' . number_format($server_stats['get_misses']) . '</td>';
                        } else {
                            echo '<td>❌ N/A</td>';
                        }
                        echo '<td>服务器: ' . $server . '</td>';
                        echo '</tr>';
                        
                        // 当前内存使用 (bytes)
                        echo '<tr>';
                        echo '<td><strong>当前内存使用 (bytes)</strong></td>';
                        $bytes_raw = 0;
                        if (isset($server_stats['bytes'])) {
                            // 如果已经格式化，尝试解析原始数值
                            if (is_string($server_stats['bytes']) && strpos($server_stats['bytes'], ' ') !== false) {
                                echo '<td>' . $server_stats['bytes'] . '</td>';
                            } else {
                                $bytes_raw = (int)$server_stats['bytes'];
                                echo '<td>' . number_format($bytes_raw) . ' 字节</td>';
                            }
                        } else {
                            echo '<td>N/A</td>';
                        }
                        echo '<td>服务器: ' . $server . '</td>';
                        echo '</tr>';
                        
                        // 当前缓存项数 (curr_items)
                        echo '<tr>';
                        echo '<td><strong>当前缓存项数 (curr_items)</strong></td>';
                        if (isset($server_stats['curr_items'])) {
                            $curr_items = (int)$server_stats['curr_items'];
                            echo '<td>' . number_format($curr_items) . ' 项</td>';
                        } else {
                            echo '<td>N/A</td>';
                        }
                        echo '<td>服务器: ' . $server . '</td>';
                        echo '</tr>';
                        
                        // 网络读取总量 (bytes_read)
                        echo '<tr>';
                        echo '<td><strong>网络读取总量 (bytes_read)</strong></td>';
                        if (isset($server_stats['bytes_read'])) {
                            // 如果已经格式化，尝试解析原始数值
                            if (is_string($server_stats['bytes_read']) && strpos($server_stats['bytes_read'], ' ') !== false) {
                                echo '<td>' . $server_stats['bytes_read'] . '</td>';
                            } else {
                                $bytes_read = (int)$server_stats['bytes_read'];
                                echo '<td>' . number_format($bytes_read) . ' 字节</td>';
                            }
                        } else {
                            echo '<td>❌ N/A</td>';
                        }
                        echo '<td>服务器: ' . $server . '</td>';
                        echo '</tr>';
                        
                        // 网络写入总量 (bytes_written)
                        echo '<tr>';
                        echo '<td><strong>网络写入总量 (bytes_written)</strong></td>';
                        if (isset($server_stats['bytes_written'])) {
                            // 如果已经格式化，尝试解析原始数值
                            if (is_string($server_stats['bytes_written']) && strpos($server_stats['bytes_written'], ' ') !== false) {
                                echo '<td>' . $server_stats['bytes_written'] . '</td>';
                            } else {
                                $bytes_written = (int)$server_stats['bytes_written'];
                                echo '<td>' . number_format($bytes_written) . ' 字节</td>';
                            }
                        } else {
                            echo '<td>N/A</td>';
                        }
                        echo '<td>服务器: ' . $server . '</td>';
                        echo '</tr>';
                        
                        // 只显示第一个服务器的详细信息
                        break;
                    }
                }
                
                // 基础统计
                echo '<tr class="cache-validator__section-header">';
                echo '<td colspan="3"><strong class="cache-validator__section-title">📊 Memcached 服务器基础信息</strong></td>';
                echo '</tr>';
                echo '<tr>';
                echo '<td><strong>缓存统计</strong></td>';
                echo '<td>ℹ️ 信息</td>';
                echo '<td>';
                echo '命中: ' . $stats['hits'];
                echo ', 未命中: ' . $stats['misses'];
                echo ', 操作数: ' . $stats['operations'];
                echo '</td>';
                echo '</tr>';
                
                // 缓存配置
                echo '<tr>';
                echo '<td><strong>缓存配置</strong></td>';
                echo '<td>ℹ️ 配置</td>';
                echo '<td>';
                echo '键前缀: ' . $stats['cache_key_salt'];
                echo ', 博客前缀: ' . $stats['blog_prefix'];
                echo '</td>';
                echo '</tr>';
                
                // 内存使用
                if (isset($stats['memory_usage'])) {
                    echo '<tr>';
                    echo '<td><strong>内存使用</strong></td>';
                    echo '<td>ℹ️ 内存</td>';
                    echo '<td>';
                    echo 'PHP内存: ' . round($stats['memory_usage']['php_memory_usage'] / 1024 / 1024, 2) . 'MB';
                    echo ', 本地缓存: ' . $stats['local_cache_size'] . ' 项';
                    echo '</td>';
                    echo '</tr>';
                }
                
                // 缓存效率评级
                if (isset($stats['cache_efficiency'])) {
                    echo '<tr>';
                    echo '<td><strong>缓存效率</strong></td>';
                    $grade = $stats['cache_efficiency']['grade'];
                    
                    // 根据评级添加对应的emoji表情
                    $grade_emoji = '';
                    switch ($grade) {
                        case 'A+':
                            $grade_emoji = '🏆';
                            $grade_class = 'success';
                            break;
                        case 'A':
                            $grade_emoji = '🥇';
                            $grade_class = 'success';
                            break;
                        case 'B':
                            $grade_emoji = '🥈';
                            $grade_class = 'warning';
                            break;
                        case 'C':
                            $grade_emoji = '🥉';
                            $grade_class = 'warning';
                            break;
                        case 'D':
                            $grade_emoji = '⚠️';
                            $grade_class = 'error';
                            break;
                        case 'F':
                            $grade_emoji = '❌';
                            $grade_class = 'error';
                            break;
                        default:
                            $grade_emoji = 'ℹ️';
                            $grade_class = 'info';
                    }
                    
                    echo '<td class="' . $grade_class . '">' . $grade_emoji . ' ' . $grade . '</td>';
                    echo '<td>评分: ' . $stats['cache_efficiency']['score'] . '% (总请求: ' . $stats['cache_efficiency']['total_requests'] . ')</td>';
                    echo '</tr>';
                }
            }
            
            // 7. 系统信息
            echo '<tr>';
            echo '<td><strong>PHP版本</strong></td>';
            echo '<td>ℹ️ ' . phpversion() . '</td>';
            echo '<td>内存: ' . ini_get('memory_limit') . '</td>';
            echo '</tr>';
            
            echo '<tr>';
            echo '<td><strong>Memcached扩展</strong></td>';
            $ext_loaded = extension_loaded('memcached');
            echo '<td>' . ($ext_loaded ? '✅ 已加载' : '❌ 未加载') . '</td>';
            echo '<td>' . ($ext_loaded ? phpversion('memcached') : 'N/A') . '</td>';
            echo '</tr>';

            // 连接状态
            echo '<tr>';
            echo '<td><strong>Memcached连接状态</strong></td>';
            $connected = $stats['connection_status'];
            echo '<td>' . ($connected ? '✅ 已连接' : '❌ 未连接') . '</td>';
            echo '<td>';
            if (isset($stats['memcached']) && !empty($stats['memcached'])) {
                foreach ($stats['memcached'] as $server => $server_stats) {
                    echo '服务器版本: ' . $server_stats['version'] . '，命中率: ' . $server_stats['hit_ratio'] . '%';
                    break;
                }
            } else {
                echo $connected ? '连接正常' : '连接失败';
            }
            echo '</td>';
            echo '</tr>';
            ?>
        </tbody>
    </table>
    
    <?php
    // 总体评估 - 包含Memcached和OPcache
    $memcached_score = 0;
    $memcached_max = 6;
    $opcache_score = 0;
    $opcache_max = 6;
    
    // Memcached评分
    if ($wp_cache_enabled) $memcached_score++;
    if (isset($wp_object_cache) && is_object($wp_object_cache)) $memcached_score++;
    if (isset($wp_object_cache) && method_exists($wp_object_cache, 'is_connected') && $wp_object_cache->is_connected()) $memcached_score++;
    if (function_exists('wp_cache_set')) $memcached_score++;
    if (isset($set_result) && $set_result) $memcached_score++;
    if (isset($read_success) && $read_success) $memcached_score++;
    
    // OPcache评分
    if (function_exists('opcache_get_status')) {
        $opcache_score++; // OPcache已启用
        
        $status = opcache_get_status();
        if ($status && is_array($status)) {
            $opcache_score++; // 能获取状态信息
            
            // 内存使用率评分
            if (isset($status['memory_usage']['used_memory'], $status['memory_usage']['free_memory'])) {
                $used = $status['memory_usage']['used_memory'];
                $free = $status['memory_usage']['free_memory'];
                $usage_percent = ($used / ($used + $free)) * 100;
                if ($usage_percent < 90) $opcache_score++; // 内存使用率良好
            }
            
            // 缓存命中率评分
            if (isset($status['opcache_statistics']['hits'], $status['opcache_statistics']['misses'])) {
                $hits = $status['opcache_statistics']['hits'];
                $misses = $status['opcache_statistics']['misses'];
                $total_requests = $hits + $misses;
                if ($total_requests > 0) {
                    $hit_rate = ($hits / $total_requests) * 100;
                    if ($hit_rate > 85) $opcache_score++; // 命中率良好
                }
            }
            
            // 重启次数评分
            if (isset($status['opcache_statistics'])) {
                $stats = $status['opcache_statistics'];
                $total_restarts = 0;
                if (isset($stats['oom_restarts'])) $total_restarts += $stats['oom_restarts'];
                if (isset($stats['hash_restarts'])) $total_restarts += $stats['hash_restarts'];
                if (isset($stats['manual_restarts'])) $total_restarts += $stats['manual_restarts'];
                if ($total_restarts <= 5) $opcache_score++; // 重启次数较少
            }
            
            // 配置合理性评分
            if (isset($status['directives']['opcache.memory_consumption'])) {
                $memory_mb = $status['directives']['opcache.memory_consumption'] / 1024 / 1024;
                if ($memory_mb >= 64) $opcache_score++; // 内存分配充足
            }
        }
    }
    
    // 综合评分（各占50%权重）
    $total_score = $memcached_score + $opcache_score;
    $total_max = $memcached_max + $opcache_max;
    $percentage = round(($total_score / $total_max) * 100);
    
    // 分别计算Memcached和OPcache的百分比
    $memcached_percentage = round(($memcached_score / $memcached_max) * 100);
    $opcache_percentage = round(($opcache_score / $opcache_max) * 100);
    
    if ($percentage >= 85) {
        $class = 'notice-success';
        $message = '🎉 优秀！缓存系统运行完美';
    } elseif ($percentage >= 70) {
        $class = 'notice-warning';
        $message = '⚠️ 良好！缓存系统基本正常';
    } elseif ($percentage >= 50) {
        $class = 'notice-warning';
        $message = '😐 一般！缓存系统需要优化';
    } else {
        $class = 'notice-error';
        $message = '❌ 需要关注！缓存系统存在问题';
    }
    ?>
    
    <div class="notice <?php echo $class; ?>">
        <p><strong>总体评估: <?php echo $total_score; ?>/<?php echo $total_max; ?> (<?php echo $percentage; ?>%)</strong></p>
        <p><?php echo $message; ?></p>
        <div class="cache-validator__notice-details">
            <p><strong>📊 详细得分:</strong></p>
            <ul class="cache-validator__list">
                <li><strong>OPcache:</strong> <?php echo $opcache_score; ?>/<?php echo $opcache_max; ?> (<?php echo $opcache_percentage; ?>%)</li>
                <li><strong>Memcached:</strong> <?php echo $memcached_score; ?>/<?php echo $memcached_max; ?> (<?php echo $memcached_percentage; ?>%)</li>
            </ul>
        </div>
    </div>
    
    <div class="notice notice-info">
        <p><strong>💡 说明:</strong> 这个验证工具运行在真实的WordPress环境中，结果比独立脚本更准确。</p>
        <p><strong>🔄 刷新:</strong> <a href="<?php echo admin_url('tools.php?page=cache-validator'); ?>">重新检查</a></p>
        <details class="cache-validator__details">
            <summary class="cache-validator__details-summary">🚀 OPcache命中率优化指南</summary>
            <div class="cache-validator__details-content">
                <h4>📈 提高命中率的关键配置：</h4>
                <ul>
                    <li><strong>生产环境设置：</strong>
                        <ul>
                            <li><code>opcache.validate_timestamps=0</code> - 关闭时间戳验证</li>
                            <li><code>opcache.revalidate_freq=0</code> - 不重新验证</li>
                            <li><code>opcache.enable_file_override=1</code> - 启用文件覆盖</li>
                        </ul>
                    </li>
                    <li><strong>部署策略：</strong>
                        <ul>
                            <li>使用原子部署，避免文件逐个更新</li>
                            <li>部署后执行 <code>opcache_reset()</code></li>
                            <li>避免在高峰期部署</li>
                        </ul>
                    </li>
                    <li><strong>代码优化：</strong>
                        <ul>
                            <li>减少不必要的文件包含</li>
                            <li>使用 Composer autoloader</li>
                            <li>清理未使用的插件和主题</li>
                        </ul>
                    </li>
                </ul>
                
                <h4>⚠️ 注意事项：</h4>
                <ul>
                    <li><strong>开发环境：</strong> 保持 <code>validate_timestamps=1</code> 以便调试</li>
                    <li><strong>命中率目标：</strong> 生产环境应达到 95% 以上</li>
                    <li><strong>监控重启：</strong> 频繁重启可能表示内存不足</li>
                </ul>
                
                <p><strong>🔧 快速命令：</strong></p>
                <pre class="cache-validator__code-block">
# 检查当前配置
php -r "print_r(opcache_get_configuration());"

# 重置OPcache
php -r "opcache_reset();"

# 查看详细状态
php -r "print_r(opcache_get_status());"</pre>
            </div>
        </details>
        <details class="cache-validator__details">
            <summary class="cache-validator__details-summary">📊 Memcached 关键指标说明</summary>
            <div class="cache-validator__details-content">
                <h4>🔍 关键指标含义：</h4>
                <ul>
                    <li><strong>curr_items:</strong> 当前存储在缓存中的数据项数量</li>
                    <li><strong>bytes:</strong> Memcached 服务器当前使用的内存字节数</li>
                    <li><strong>bytes_read:</strong> 从网络读取的总字节数（请求总大小）</li>
                    <li><strong>bytes_written:</strong> 向网络写入的总字节数（响应总大小）</li>
                </ul>
                
                <h4>📈 性能分析建议：</h4>
                <ul>
                    <li><strong>内存使用率 (bytes):</strong> 监控是否接近服务器内存限制</li>
                    <li><strong>缓存项数 (curr_items):</strong> 评估缓存数据的规模和分布</li>
                    <li><strong>网络I/O (bytes_read/written):</strong> 分析缓存服务器的网络负载</li>
                </ul>
            </div>
        </details>
    </div>
    <?php
}
?>