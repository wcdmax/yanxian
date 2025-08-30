<?php

/**
 * 添加刷新伪静态规则工具
 */
add_action('admin_menu', function() {
    add_management_page(
        '系统工具', // 页面标题
        '系统工具', // 菜单标题
        'manage_options', // 所需权限
        'system-tools', // 页面标识
        function() { // 页面回调函数
            ?>
            <div class="wrap">
                <h1>系统工具</h1>
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
                    // 清理对象缓存
                    wp_cache_flush();
                    ?>
                    <div class="notice notice-success">
                        <p>系统缓存已成功清理！</p>
                    </div>
                    <?php
                }
                ?>
                <div style="display: grid; grid-template-columns: repeat(3, 1fr);">
                    <!-- 缓存清理工具 -->
                    <div class="card">
                        <h2 class="title">缓存清理</h2>
                        <p>点击下方按钮可以清理WordPress的系统缓存。在以下情况下建议使用此功能：</p>
                        <ul style="list-style-type: disc; margin-left: 20px;">
                            <li>网站显示异常时</li>
                            <li>更新主题或插件后</li>
                            <li>修改了重要设置后</li>
                            <li>需要强制刷新缓存数据时</li>
                        </ul>
                        <div style="margin-top: 20px;">
                            <form method="post" action="">
                                <?php wp_nonce_field('flush_cache'); ?>
                                <input type="submit" name="flush_cache" value="清理系统缓存" class="button button-primary" onclick="return confirm('确定要清理系统缓存吗？')" />
                            </form>
                        </div>
                    </div>
                    <!-- 伪静态规则工具 -->
                    <div class="card">
                        <h2 class="title">刷新伪静态</h2>
                        <p>点击下方按钮可以刷新WordPress的伪静态规则。在以下情况下建议使用此功能：</p>
                        <ul style="list-style-type: disc; margin-left: 20px;">
                            <li>修改了固定链接设置后</li>
                            <li>添加了新的自定义文章类型后</li>
                            <li>遇到404错误但确认内容存在时</li>
                            <li>修改了重写规则后</li>
                        </ul>
                        <div style="margin-top: 20px;">
                            <form method="post" action="">
                                <?php wp_nonce_field('flush_rewrite_rules'); ?>
                                <input type="submit" name="flush_rules" value="刷新伪静态规则" class="button button-primary" onclick="return confirm('确定要刷新伪静态规则吗？')" />
                            </form>
                        </div>
                    </div>
                    <div class="card">
                        <h2 class="title">计算宽高/比例</h2>
                        <p>点击下方按钮可以计算图片的宽高。在以下情况下建议使用此功能：</p>
                        <div style="margin-top: 20px;">
                            <form method="post" action="" id="image-size-form">
                                <?php wp_nonce_field('calculate_image_size'); ?>
                                <table class="form-table">
                                    <tr>
                                        <th scope="row"><label for="aspect_ratio">宽高比例</label></th>
                                        <td>
                                            <select name="aspect_ratio" id="aspect_ratio">
                                                <option value="" <?php echo empty($_POST['aspect_ratio']) ? 'selected' : ''; ?>>--请选择比例--</option>
                                                <option value="3:2" <?php echo (isset($_POST['aspect_ratio']) && $_POST['aspect_ratio']==='3:2') ? 'selected' : ''; ?>>3:2</option>
                                                <option value="4:3" <?php echo (isset($_POST['aspect_ratio']) && $_POST['aspect_ratio']==='4:3') ? 'selected' : ''; ?>>4:3</option>
                                                <option value="16:9" <?php echo (isset($_POST['aspect_ratio']) && $_POST['aspect_ratio']==='16:9') ? 'selected' : ''; ?>>16:9</option>
                                                <option value="16:10" <?php echo (isset($_POST['aspect_ratio']) && $_POST['aspect_ratio']==='16:10') ? 'selected' : ''; ?>>16:10</option>
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
                                const commonRatios = [
                                    { label: '4:3', value: 4/3 },
                                    { label: '3:2', value: 3/2 },
                                    { label: '16:9', value: 16/9 },
                                    { label: '16:10', value: 16/10 }
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
                                            function gcd(a, b) { return b == 0 ? a : gcd(b, a % b); }
                                            let divisor = gcd(width, height);
                                            ratioStr = (width/divisor) + ':' + (height/divisor);
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
                                            if (ratioSelect.options[i].defaultSelected === false && ratioSelect.options[i].value && !['3:2','4:3','16:9','16:10'].includes(ratioSelect.options[i].value)) {
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