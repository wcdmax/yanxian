<?php

/**
 * 添加设置菜单
 */
add_action('admin_menu', function() {
    add_options_page(
        '网站信息',              // 页面标题
        '网站',                  // 菜单标题
        'manage_options',        // 权限
        'site-info',             // 菜单slug
        'render_site_info_page', // 回调函数
        2                        // 菜单位置
    );
});

/**
 * 注册设置
 */
add_action('admin_init', function() {
    // 注册设置组
    register_setting('site_info_options', 'company_icp');
    register_setting('site_info_options', 'company_400');
    register_setting('site_info_options', 'company_qrcode');
    register_setting('site_info_options', 'company_phone');
    register_setting('site_info_options', 'company_wechat');
    register_setting('site_info_options', 'company_email');
    register_setting('site_info_options', 'company_address');
    register_setting('site_info_options', 'global_keywords');
    register_setting('site_info_options', 'url_push_bing_key');
    register_setting('site_info_options', 'global_description');
    register_setting('site_info_options', 'url_push_baidu_token');


    // 添加设置区域
    add_settings_section(
        'site_info_section',
        null,
        null,
        'site-info'
    );

    // 添加设置字段
    add_settings_field(
        'company_qrcode',
        '二维码',
        'render_qrcode_field',
        'site-info',
        'site_info_section'
    );

    add_settings_field(
        'company_400',
        '400电话',
        'render_text_field',
        'site-info',
        'site_info_section',
        ['field' => 'company_400']
    );

    add_settings_field(
        'company_phone',
        '联系电话',
        'render_text_field',
        'site-info',
        'site_info_section',
        ['field' => 'company_phone']
    );

    add_settings_field(
        'company_icp',
        'ICP备案号',
        'render_text_field',
        'site-info',
        'site_info_section',
        ['field' => 'company_icp']
    );

    add_settings_field(
        'company_wechat',
        '微信号码',
        'render_text_field',
        'site-info',
        'site_info_section',
        ['field' => 'company_wechat']
    );

    add_settings_field(
        'company_email',
        '邮箱地址',
        'render_text_field',
        'site-info',
        'site_info_section',
        ['field' => 'company_email']
    );

    add_settings_field(
        'company_address',
        '公司地址',
        'render_textarea_field',
        'site-info',
        'site_info_section',
        ['field' => 'company_address']
    );

    add_settings_field(
        'global_keywords',
        '首页关键词',
        'render_text_field',
        'site-info',
        'site_info_section',
        ['field' => 'global_keywords']
    );

    add_settings_field(
        'global_description',
        '首页的描述',
        'render_textarea_field',
        'site-info',
        'site_info_section',
        ['field' => 'global_description']
    );

    add_settings_field(
        'url_push_bing_key',
        '必应推送Key',
        'render_text_field',
        'site-info',
        'site_info_section',
        ['field' => 'url_push_bing_key']
    );

    add_settings_field(
        'url_push_baidu_token',
        '百度推送Token',
        'render_text_field',
        'site-info',
        'site_info_section',
        ['field' => 'url_push_baidu_token']
    );
});

/**
 * 渲染设置页面
 */
function render_site_info_page() {
    ?>
    <div class="wrap">
        <h1>网站信息设置</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('site_info_options');
            do_settings_sections('site-info');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

/**
 * 渲染二维码字段
 */
function render_qrcode_field() {
    // 确保加载媒体库脚本
    wp_enqueue_media();
    
    $value = get_option('company_qrcode');
    ?>
    <div class="image-preview-wrapper">
        <img id="company-qrcode-preview" src="<?php echo esc_url($value); ?>" style="max-width:100px;<?php echo empty($value) ? 'display:none;' : ''; ?>">
    </div>
    <input type="hidden" name="company_qrcode" id="company_qrcode" value="<?php echo esc_attr($value); ?>">
    <input type="button" class="button" value="<?php echo empty($value) ? '选择图片' : '更换图片'; ?>" id="upload_qrcode_button">
    <input type="button" class="button" value="移除图片" id="remove_qrcode_button" style="<?php echo empty($value) ? 'display:none;' : ''; ?>">
    <?php
}

/**
 * 添加媒体上传脚本
 */
add_action('admin_enqueue_scripts', function($hook) {
    if ('settings_page_site-info' !== $hook) {
        return;
    }
    
    // 加载媒体库脚本
    wp_enqueue_media();
    
    // 添加自定义脚本
    wp_add_inline_script('media-editor', '
        jQuery(document).ready(function($) {
            $("#upload_qrcode_button").click(function(e) {
                e.preventDefault();
                var frame = wp.media({
                    title: "选择二维码图片",
                    library: {type: "image"},
                    multiple: false,
                    button: {text: "使用这张图片"}
                }).open()
                .on("select", function() {
                    var uploaded_image = frame.state().get("selection").first();
                    var image_url = uploaded_image.toJSON().url;
                    $("#company_qrcode").val(image_url);
                    $("#company-qrcode-preview").attr("src", image_url).show();
                    $("#remove_qrcode_button").show();
                });
            });

            $("#remove_qrcode_button").click(function(e) {
                e.preventDefault();
                $("#company_qrcode").val("");
                $("#company-qrcode-preview").attr("src", "").hide();
                $(this).hide();
            });
        });
    ');
});

/**
 * 渲染文本字段
 */
function render_text_field($args) {
    $value = get_option($args['field']);
    echo '<input type="text" name="' . esc_attr($args['field']) . '" value="' . esc_attr($value) . '" class="regular-text">';
}

/**
 * 渲染多行文本字段
 */
function render_textarea_field($args) {
    $value = get_option($args['field']);
    echo '<textarea name="' . esc_attr($args['field']) . '" class="regular-text" rows="3">' . esc_textarea($value) . '</textarea>';
}