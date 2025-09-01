<?php

/**
 * 添加刷新伪静态规则工具
 */
add_action('admin_menu', function () {
    add_management_page(
        '系统工具',
        '系统工具',
        'manage_options',
        'system-tools',
        function () {
?>
        <div class="wrap">
            <h1>系统工具</h1>
            <style>
                .card {
                    border-radius: 10px;
                }

                .card-actions {
                    margin-top: 20px;
                }

                .cache-info {
                    font-size: 12px;
                }

                .card-list {
                    list-style-type: disc;
                    margin-left: 20px;
                }

                .system-tools-grid {
                    display: grid;
                    grid-template-columns: repeat(3, 1fr);
                    gap: 20px;
                }

                .button-danger {
                    background-color: #dc3545 !important;
                    border-color: #dc3545 !important;
                    color: white !important;
                }

                .button-danger:hover {
                    background-color: #c82333 !important;
                    border-color: #bd2130 !important;
                }

                .notice {
                    position: relative;
                    transition: opacity 0.3s ease-out;
                }

                .notice.fade-out {
                    opacity: 0;
                }
            </style>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    // 自动隐藏所有的 notice 提示框
                    const notices = document.querySelectorAll('.notice');
                    notices.forEach(function(notice) {
                        // 5秒后开始淡出效果
                        setTimeout(function() {
                            notice.classList.add('fade-out');
                            // 淡出动画完成后移除元素
                            setTimeout(function() {
                                notice.style.display = 'none';
                            }, 300); // 等待淡出动画完成（0.3s）
                        }, 5000); // 5秒后开始淡出
                    });
                });
            </script>
            <?php
            // 处理伪静态规则刷新
            if (isset($_POST['flush_rules']) && check_admin_referer('flush_rewrite_rules')) {
                flush_rewrite_rules();
            ?>
                <div class="notice notice-success">
                    <p>伪静态规则已成功刷新！</p>
                </div>
            <?php
            }

            // 处理缓存刷新
            if (isset($_POST['flush_cache']) && check_admin_referer('flush_cache')) {
                wp_cache_flush();
            ?>
                <div class="notice notice-success">
                    <p>系统缓存已成功清理！</p>
                </div>
            <?php
            }

            // 处理模板缓存清理
            if (isset($_POST['flush_template_cache']) && check_admin_referer('flush_template_cache')) {
                wp_clean_themes_cache();
                wp_clean_plugins_cache();
                flush_rewrite_rules();
            ?>
                <div class="notice notice-success">
                    <p>模板缓存已成功清理！</p>
                </div>
            <?php
            }

            // 处理区块样板缓存清理
            if (isset($_POST['flush_pattern_cache']) && check_admin_referer('flush_pattern_cache')) {
                wp_cache_delete('blocks', 'core');
                wp_cache_delete('last_changed', 'terms');
                wp_cache_delete('block_patterns', 'theme');
            ?>
                <div class="notice notice-success">
                    <p>区块样板缓存已成功清理！</p>
                </div>
            <?php
            }

            // 处理全部缓存清理
            if (isset($_POST['flush_all_cache']) && check_admin_referer('flush_all_cache')) {
                // 清除所有类型的缓存
                wp_cache_flush();
                flush_rewrite_rules();
                wp_clean_themes_cache();
                wp_clean_plugins_cache();
                wp_cache_delete('blocks', 'core');
                wp_cache_delete('last_changed', 'terms');
                wp_cache_delete('alloptions', 'options');
                wp_cache_delete('notoptions', 'options');
                wp_cache_delete('block_patterns', 'theme');

                // 清除 OPcache（如果可用）
                if (function_exists('opcache_reset')) {
                    opcache_reset();
                }

                // 清除 Memcached 缓存（只清除当前网站的缓存）
                if (class_exists('Memcached') && defined('WP_CACHE_KEY_SALT')) {
                    try {
                        $memcached = new Memcached();
                        $memcached->addServer('127.0.0.1', 11211);
                        
                        $key_salt = WP_CACHE_KEY_SALT;
                        $keys = $memcached->getAllKeys();
                        if ($keys) {
                            foreach ($keys as $key) {
                                if (strpos($key, $key_salt) === 0) {
                                    $memcached->delete($key);
                                }
                            }
                        }
                    } catch (Exception $e) {
                        // Memcached 连接失败，忽略错误
                    }
                } elseif (class_exists('Memcache') && defined('WP_CACHE_KEY_SALT')) {
                    try {
                        $memcache = new Memcache();
                        if ($memcache->connect('127.0.0.1', 11211)) {
                            $key_salt = WP_CACHE_KEY_SALT;
                            $common_keys = ['options', 'posts', 'terms', 'users', 'comments'];
                            
                            foreach ($common_keys as $key_type) {
                                $patterns = [
                                    $key_salt . $key_type,
                                    $key_salt . 'wp_' . $key_type,
                                    $key_salt . get_current_blog_id() . ':' . $key_type
                                ];
                                foreach ($patterns as $pattern) {
                                    $memcache->delete($pattern);
                                }
                            }
                            $memcache->close();
                        }
                    } catch (Exception $e) {
                        // Memcache 连接失败，忽略错误
                    }
                }

                // 清除 Redis 缓存（只清除当前网站的缓存）
                if (class_exists('Redis')) {
                    try {
                        $redis = new Redis();
                        if ($redis->connect('127.0.0.1', 6379)) {
                            $prefix = defined('WP_REDIS_PREFIX') ? WP_REDIS_PREFIX : (defined('WP_CACHE_KEY_SALT') ? WP_CACHE_KEY_SALT : 'wp_');
                            $keys = $redis->keys($prefix . '*');
                            if ($keys) {
                                foreach ($keys as $key) {
                                    $redis->del($key);
                                }
                            }
                            $redis->close();
                        }
                    } catch (Exception $e) {
                        // Redis 连接失败，忽略错误
                    }
                }
            ?>
                <div class="notice notice-success">
                    <p>所有缓存已成功清理！</p>
                </div>
            <?php
            }

            // 处理Memcached缓存清理
            if (isset($_POST['flush_external_cache']) && check_admin_referer('flush_external_cache')) {
                $cleared_caches = [];
                
                // 清除 Memcached 缓存（只清除当前网站的缓存）
                if (class_exists('Memcached') && defined('WP_CACHE_KEY_SALT')) {
                    try {
                        $memcached = new Memcached();
                        $memcached->addServer('127.0.0.1', 11211);
                        
                        // 获取当前网站的缓存键前缀
                        $key_salt = WP_CACHE_KEY_SALT;
                        
                        // 获取所有键并只删除以当前网站前缀开头的键
                        $keys = $memcached->getAllKeys();
                        if ($keys) {
                            $deleted_count = 0;
                            foreach ($keys as $key) {
                                if (strpos($key, $key_salt) === 0) {
                                    if ($memcached->delete($key)) {
                                        $deleted_count++;
                                    }
                                }
                            }
                            if ($deleted_count > 0) {
                                $cleared_caches[] = "Memcached (清理了 {$deleted_count} 个缓存项)";
                            }
                        }
                    } catch (Exception $e) {
                        // Memcached 连接失败，忽略错误
                    }
                } elseif (class_exists('Memcache') && defined('WP_CACHE_KEY_SALT')) {
                    try {
                        $memcache = new Memcache();
                        if ($memcache->connect('127.0.0.1', 11211)) {
                            // 注意：Memcache 扩展没有 getAllKeys 方法
                            // 这里我们只能尝试删除一些常见的 WordPress 缓存键
                            $key_salt = WP_CACHE_KEY_SALT;
                            $common_keys = [
                                'options', 'posts', 'terms', 'users', 'comments',
                                'site-options', 'blog-details', 'blog-lookup',
                                'userlogins', 'useremail', 'usernicename'
                            ];
                            
                            $deleted_count = 0;
                            foreach ($common_keys as $key_type) {
                                // 尝试删除各种可能的缓存键格式
                                $patterns = [
                                    $key_salt . $key_type,
                                    $key_salt . 'wp_' . $key_type,
                                    $key_salt . get_current_blog_id() . ':' . $key_type
                                ];
                                
                                foreach ($patterns as $pattern) {
                                    if ($memcache->delete($pattern)) {
                                        $deleted_count++;
                                    }
                                }
                            }
                            
                            $memcache->close();
                            if ($deleted_count > 0) {
                                $cleared_caches[] = "Memcache (清理了部分缓存项)";
                            }
                        }
                    } catch (Exception $e) {
                        // Memcache 连接失败，忽略错误
                    }
                }

                // Redis 缓存处理（如果有特定前缀配置）
                if (class_exists('Redis')) {
                    try {
                        $redis = new Redis();
                        if ($redis->connect('127.0.0.1', 6379)) {
                            // 检查是否有 Redis 前缀配置
                            $prefix = defined('WP_REDIS_PREFIX') ? WP_REDIS_PREFIX : (defined('WP_CACHE_KEY_SALT') ? WP_CACHE_KEY_SALT : 'wp_');
                            
                            // 获取所有以前缀开头的键
                            $keys = $redis->keys($prefix . '*');
                            if ($keys) {
                                $deleted_count = 0;
                                foreach ($keys as $key) {
                                    if ($redis->del($key)) {
                                        $deleted_count++;
                                    }
                                }
                                if ($deleted_count > 0) {
                                    $cleared_caches[] = "Redis (清理了 {$deleted_count} 个缓存项)";
                                }
                            }
                            $redis->close();
                        }
                    } catch (Exception $e) {
                        // Redis 连接失败，忽略错误
                    }
                }

                if (!empty($cleared_caches)) {
                    ?>
                    <div class="notice notice-success">
                        <p>外部缓存已成功清理：<?php echo implode(', ', $cleared_caches); ?></p>
                    </div>
                    <?php
                } else {
                    ?>
                    <div class="notice notice-warning">
                        <p>未检测到可用的外部缓存服务（Memcached/Redis）</p>
                    </div>
                    <?php
                }
            }

            // 处理OPcache缓存清理
            if (isset($_POST['flush_opcache']) && check_admin_referer('flush_opcache')) {
                if (function_exists('opcache_reset')) {
                    if (opcache_reset()) {
                        ?>
                        <div class="notice notice-success">
                            <p>OPcache缓存已成功清理！</p>
                        </div>
                        <?php
                    } else {
                        ?>
                        <div class="notice notice-error">
                            <p>OPcache缓存清理失败！</p>
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <div class="notice notice-warning">
                        <p>OPcache未启用或不可用</p>
                    </div>
                    <?php
                }
            }
            ?>
            <div class="system-tools-grid">
                <!-- 全部缓存清理工具 -->
                <div class="card">
                    <h2 class="title">清理所有缓存</h2>
                    <p>一键清理所有类型的缓存，包括WordPress内置缓存、外部缓存服务和OPcache：</p>
                    <ul class="card-list">
                        <li>进行重大更新后</li>
                        <li>网站全面清理时</li>
                        <li>网站出现严重问题时</li>
                        <li>多种缓存问题并发时</li>
                    </ul>
                    <div class="card-actions">
                        <form method="post" action="">
                            <?php wp_nonce_field('flush_all_cache'); ?>
                            <input type="submit" name="flush_all_cache" value="清理所有缓存" class="button button-danger" onclick="return confirm('确定要清理所有缓存吗？这可能需要一些时间。')" />
                        </form>
                    </div>
                </div>

                <!-- 对象缓存清理工具 -->
                <div class="card">
                    <h2 class="title">对象缓存清理</h2>
                    <p>清理WordPress的基础对象缓存。在以下情况下建议使用此功能：</p>
                    <ul class="card-list">
                        <li>网站显示异常时</li>
                        <li>数据更新不及时时</li>
                        <li>插件冲突导致的问题</li>
                        <li>需要强制刷新数据时</li>
                    </ul>
                    <div class="card-actions">
                        <form method="post" action="">
                            <?php wp_nonce_field('flush_cache'); ?>
                            <input type="submit" name="flush_cache" value="清理对象缓存" class="button button-primary" onclick="return confirm('确定要清理对象缓存吗？')" />
                        </form>
                    </div>
                </div>

                <!-- 模板缓存清理工具 -->
                <div class="card">
                    <h2 class="title">模板缓存清理</h2>
                    <p>清理主题和插件的缓存文件。在以下情况下建议使用此功能：</p>
                    <ul class="card-list">
                        <li>样式或布局异常时</li>
                        <li>更新主题或插件后</li>
                        <li>修改了主题文件后</li>
                        <li>添加新功能不显示时</li>
                    </ul>
                    <div class="card-actions">
                        <form method="post" action="">
                            <?php wp_nonce_field('flush_template_cache'); ?>
                            <input type="submit" name="flush_template_cache" value="清理模板缓存" class="button button-primary" onclick="return confirm('确定要清理模板缓存吗？')" />
                        </form>
                    </div>
                </div>

                <!-- 区块样板缓存清理工具 -->
                <div class="card">
                    <h2 class="title">区块样板缓存</h2>
                    <p>清理Gutenberg区块和样板的缓存。在以下情况下建议使用此功能：</p>
                    <ul class="card-list">
                        <li>区块样板不显示时</li>
                        <li>区块编辑器异常时</li>
                        <li>样板内容不更新时</li>
                        <li>修改pattern文件后</li>
                    </ul>
                    <div class="card-actions">
                        <form method="post" action="">
                            <?php wp_nonce_field('flush_pattern_cache'); ?>
                            <input type="submit" name="flush_pattern_cache" value="清理区块样板缓存" class="button button-primary" onclick="return confirm('确定要清理区块样板缓存吗？')" />
                        </form>
                    </div>
                </div>

                <!-- 伪静态规则工具 -->
                <div class="card">
                    <h2 class="title">刷新伪静态规则</h2>
                    <p>点击下方按钮可以刷新WordPress的伪静态规则。在以下情况下建议使用此功能：</p>
                    <ul class="card-list">
                        <li>修改了重写规则后</li>
                        <li>修改了固定链接设置后</li>
                        <li>添加了新的自定义文章类型后</li>
                        <li>遇到404错误但确认内容存在时</li>
                    </ul>
                    <div class="card-actions">
                        <form method="post" action="">
                            <?php wp_nonce_field('flush_rewrite_rules'); ?>
                            <input type="submit" name="flush_rules" value="刷新伪静态规则" class="button button-primary" onclick="return confirm('确定要刷新伪静态规则吗？')" />
                        </form>
                    </div>
                </div>

                <!-- Memcached缓存清理工具 -->
                <div class="card">
                    <h2 class="title">Memcached缓存清理</h2>
                    <p>
                        清理当前网站（WP_CACHE_KEY_SALT:
                        <code>
                            <?php echo defined('WP_CACHE_KEY_SALT') ? esc_html(WP_CACHE_KEY_SALT) : '<span style="color:red;">未定义</span>'; ?>
                        </code>
                        ）的Memcached缓存：
                    </p>
                    <ul class="card-list">
                        <li>缓存键前缀冲突时</li>
                        <li>网站数据更新不及时时</li>
                        <li>Memcached数据损坏时</li>
                        <li>当前网站Memcached数据异常时</li>
                    </ul>
                    <div class="card-actions">
                        <form method="post" action="">
                            <?php wp_nonce_field('flush_external_cache'); ?>
                            <input type="submit" name="flush_external_cache" value="清理Memcached缓存" class="button button-primary" onclick="return confirm('确定要清理当前网站的Memcached缓存吗？')" />
                        </form>
                    </div>
                </div>

                <!-- OPcache缓存清理工具 -->
                <div class="card">
                    <h2 class="title">OPcache缓存清理</h2>
                    <p>清理PHP的OPcache操作码缓存。在以下情况下建议使用此功能：</p>
                    <ul class="card-list">
                        <li>PHP代码执行异常时</li>
                        <li>需要重载PHP代码时</li>
                        <li>修改主题或插件代码后</li>
                        <li>更新PHP文件后代码未生效时</li>
                    </ul>
                    <p class="cache-info">说明：OPcache是PHP的操作码缓存，清理后会重新编译PHP文件</p>
                    <div class="card-actions">
                        <form method="post" action="">
                            <?php wp_nonce_field('flush_opcache'); ?>
                            <input type="submit" name="flush_opcache" value="清理OPcache缓存" class="button button-primary" onclick="return confirm('确定要清理OPcache缓存吗？')" />
                        </form>
                    </div>
                </div>

                <!-- 图片尺寸计算器 -->
                <div class="card">
                    <h2 class="title">计算宽高/比例</h2>
                    <p>点击下方按钮可以计算图片的宽高。在以下情况下建议使用此功能：</p>
                    <div class="card-actions">
                        <form method="post" action="" id="image-size-form">
                            <?php wp_nonce_field('calculate_image_size'); ?>
                            <table class="form-table">
                                <tr>
                                    <th scope="row"><label for="aspect_ratio">宽高比例</label></th>
                                    <td>
                                        <select name="aspect_ratio" id="aspect_ratio">
                                            <option value="" <?php echo empty($_POST['aspect_ratio']) ? 'selected' : ''; ?>>--请选择比例--</option>
                                            <option value="3:2" <?php echo (isset($_POST['aspect_ratio']) && $_POST['aspect_ratio'] === '3:2') ? 'selected' : ''; ?>>3:2</option>
                                            <option value="4:3" <?php echo (isset($_POST['aspect_ratio']) && $_POST['aspect_ratio'] === '4:3') ? 'selected' : ''; ?>>4:3</option>
                                            <option value="16:9" <?php echo (isset($_POST['aspect_ratio']) && $_POST['aspect_ratio'] === '16:9') ? 'selected' : ''; ?>>16:9</option>
                                            <option value="16:10" <?php echo (isset($_POST['aspect_ratio']) && $_POST['aspect_ratio'] === '16:10') ? 'selected' : ''; ?>>16:10</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="image_width">宽度（px）</label></th>
                                    <td><input type="number" name="image_width" id="image_width" min="1" class="regular-number" value="<?php echo isset($_POST['image_width']) ? esc_attr($_POST['image_width']) : ''; ?>" /></td>
                                </tr>
                                <tr>
                                    <th scope="row"><label for="image_height">高度（px）</label></th>
                                    <td><input type="number" name="image_height" id="image_height" min="1" class="regular-number" value="<?php echo isset($_POST['image_height']) ? esc_attr($_POST['image_height']) : ''; ?>" /></td>
                                </tr>
                            </table>
                            <p class="submit">
                                <button type="button" id="reset-image-size-form" class="button">重置表单</button>
                                <input type="submit" name="calculate_image_size" value="计算图片" class="button button-primary" />
                            </p>
                        </form>
                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const form = document.getElementById('image-size-form');
                                if (!form) return;
                                const widthInput = document.getElementById('image_width');
                                const heightInput = document.getElementById('image_height');
                                const ratioSelect = document.getElementById('aspect_ratio');
                                const resetBtn = document.getElementById('reset-image-size-form');

                                // 常用比例列表
                                const commonRatios = [{
                                        label: '4:3',
                                        value: 4 / 3
                                    },
                                    {
                                        label: '3:2',
                                        value: 3 / 2
                                    },
                                    {
                                        label: '16:9',
                                        value: 16 / 9
                                    },
                                    {
                                        label: '16:10',
                                        value: 16 / 10
                                    }
                                ];

                                function getNearestCommonRatio(width, height) {
                                    let actual = width / height;
                                    for (let i = 0; i < commonRatios.length; i++) {
                                        let diff = Math.abs(actual - commonRatios[i].value) / commonRatios[i].value;
                                        if (diff < 0.01) { // 允许1%误差
                                            return commonRatios[i].label;
                                        }
                                    }
                                    return null;
                                }

                                form.addEventListener('submit', function(e) {
                                    let width = parseFloat(widthInput.value);
                                    let height = parseFloat(heightInput.value);
                                    let ratio = ratioSelect.value;

                                    // 新增校验：比例、宽、高都有值时，阻止提交并提示
                                    if (width && height && ratio) {
                                        alert('请删除其中一个值以继续计算');
                                        e.preventDefault();
                                        return false;
                                    }

                                    // 新增校验：宽和高都为空但比例有值
                                    if (!width && !height && ratio) {
                                        alert('请填写宽度或高度中的一个');
                                        e.preventDefault();
                                        return false;
                                    }

                                    // 1. 宽高都有，比例为空，自动计算比例并添加option
                                    if (width && height && !ratio) {
                                        // 优先判断是否接近常用比例
                                        let nearest = getNearestCommonRatio(width, height);
                                        let ratioStr;
                                        if (nearest) {
                                            ratioStr = nearest;
                                        } else if (Number.isInteger(width) && Number.isInteger(height)) {
                                            function gcd(a, b) {
                                                return b == 0 ? a : gcd(b, a % b);
                                            }
                                            let divisor = gcd(width, height);
                                            ratioStr = (width / divisor) + ':' + (height / divisor);
                                        } else {
                                            ratioStr = width + ':' + height;
                                        }
                                        // 检查option是否已存在
                                        let found = false;
                                        for (let i = 0; i < ratioSelect.options.length; i++) {
                                            if (ratioSelect.options[i].value === ratioStr) {
                                                found = true;
                                                ratioSelect.selectedIndex = i;
                                                break;
                                            }
                                        }
                                        if (!found) {
                                            let newOption = new Option(ratioStr, ratioStr, true, true);
                                            ratioSelect.add(newOption);
                                        }
                                        ratioSelect.focus();
                                        e.preventDefault();
                                        return false;
                                    }

                                    // 2. 宽和比例有值，高为空，自动计算高
                                    if ((width && !height && ratio) || (!width && height && ratio)) {
                                        let parts = ratio.split(':');
                                        if (parts.length === 2 && !isNaN(parts[0]) && !isNaN(parts[1]) && parseFloat(parts[0]) > 0 && parseFloat(parts[1]) > 0) {
                                            let w = parseFloat(parts[0]);
                                            let h = parseFloat(parts[1]);

                                            if (width && !height) {
                                                let calcHeight = Math.round(width * h / w);
                                                heightInput.value = calcHeight;
                                                heightInput.focus();
                                            } else {
                                                let calcWidth = Math.round(height * w / h);
                                                widthInput.value = calcWidth;
                                                widthInput.focus();
                                            }

                                            e.preventDefault();
                                            return false;
                                        }
                                    }
                                });

                                // 重置按钮逻辑
                                if (resetBtn) {
                                    resetBtn.addEventListener('click', function() {
                                        widthInput.value = '';
                                        heightInput.value = '';
                                        ratioSelect.selectedIndex = 0;
                                        // 可选：移除动态添加的option
                                        for (let i = ratioSelect.options.length - 1; i >= 0; i--) {
                                            if (ratioSelect.options[i].defaultSelected === false && ratioSelect.options[i].value && !['3:2', '4:3', '16:9', '16:10'].includes(ratioSelect.options[i].value)) {
                                                ratioSelect.remove(i);
                                            }
                                        }
                                    });
                                }
                            });
                        </script>
                    </div>
                </div>
            </div>
        </div>
<?php
        },
        3
    );
});
