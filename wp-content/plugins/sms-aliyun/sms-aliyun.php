<?php
/*
Author: Kevin
Version: 1.0.0
License: Apache2.0
Plugin Name: SMS Aliyun
Author URI: mailto:kevin@tigervs.com
Description: 使用阿里云短信服务作为短信发送服务
*/

require_once 'sms-aliyun-class.php';

// 初始化选项
register_activation_hook(__FILE__, function () {
    add_option('sms_aliyun_options', [
        'accessKeyId' => '',
        'type' => 'access_key',
        'accessKeySecret' => '',
        'endpoint' => 'dysmsapi.aliyuncs.com'
    ]);
});



// 添加短信配置菜单
add_action('admin_menu', function () {
    add_submenu_page(
        'options-general.php',
        '短信配置',
        '短信配置',
        'manage_options',
        'sms-aliyun-config',
        'sms_aliyun_options_page',
        6
    );
});

// 短信配置页面回调函数
function sms_aliyun_options_page()
{
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }
?>
    <div class="wrap">
        <h1>短信配置</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('sms_aliyun_options_group');
            do_settings_sections('sms-aliyun-config');
            submit_button();
            ?>
        </form>
    </div>
<?php
}

// 添加钩子以注册设置项
add_action('admin_init', 'register_sms_aliyun_options_settings');

// 注册短信配置设置和字段
function register_sms_aliyun_options_settings()
{
    register_setting(
        'sms_aliyun_options_group',
        'sms_aliyun_options',
        [
            'sanitize_callback' => 'sanitize_sms_aliyun_options'
        ]
    );
    register_setting('sms_aliyun_options_group', 'sms_aliyun_signName');
    register_setting('sms_aliyun_options_group', 'sms_aliyun_phoneNumber');
    register_setting('sms_aliyun_options_group', 'sms_aliyun_templateCode');

    add_settings_section(
        'sms_aliyun_options_section',
        '',
        '',
        'sms-aliyun-config'
    );

    add_settings_field(
        'sms_aliyun_signName',
        '短信签名',
        'sms_aliyun_signName_callback',
        'sms-aliyun-config',
        'sms_aliyun_options_section'
    );

    add_settings_field(
        'sms_aliyun_templateCode',
        '短信模板',
        'sms_aliyun_templateCode_callback',
        'sms-aliyun-config',
        'sms_aliyun_options_section'
    );

    add_settings_field(
        'sms_aliyun_phoneNumber',
        '短信接收号码',
        'sms_aliyun_phoneNumber_callback',
        'sms-aliyun-config',
        'sms_aliyun_options_section'
    );

    add_settings_field(
        'sms_aliyun_endpoint',
        '短信服务地址',
        'sms_aliyun_endpoint_callback',
        'sms-aliyun-config',
        'sms_aliyun_options_section'
    );

    add_settings_field(
        'sms_aliyun_accessKeyId',
        'AccessKey ID',
        'sms_aliyun_accessKeyId_callback',
        'sms-aliyun-config',
        'sms_aliyun_options_section'
    );

    add_settings_field(
        'sms_aliyun_accessKeySecret',
        'AccessKey Secret',
        'sms_aliyun_accessKeySecret_callback',
        'sms-aliyun-config',
        'sms_aliyun_options_section'
    );
}

// 显示错误信息
add_action('admin_notices', function () {
    settings_errors('sms_aliyun_phoneNumber');
});

// 验证手机号码
function validate_phone_number($phone)
{
    if (empty($phone)) {
        add_settings_error(
            'sms_aliyun_phoneNumber',
            'invalid_phone',
            '手机号码不能为空'
        );
        return false;
    }

    if (!preg_match('/^1[3-9]\d{9}$/', $phone)) {
        add_settings_error(
            'sms_aliyun_phoneNumber',
            'invalid_phone',
            '请输入有效的11位手机号码'
        );
        return false;
    }

    return $phone;
}

// 注册设置验证
add_action('admin_init', function () {
    register_setting(
        'sms_aliyun_options_group',
        'sms_aliyun_phoneNumber',
        [
            'sanitize_callback' => 'validate_phone_number'
        ]
    );
});

// 更新选项回调函数
function sanitize_sms_aliyun_options()
{
    $current_options = get_option('sms_aliyun_options', []);

    // 更新选项
    $current_options['endpoint'] = sanitize_text_field($_POST['sms_aliyun_endpoint']);
    $current_options['accessKeyId'] = sanitize_text_field($_POST['sms_aliyun_accessKeyId']);
    $current_options['accessKeySecret'] = sanitize_text_field($_POST['sms_aliyun_accessKeySecret']);

    return $current_options;
}


// 添加设置选项按钮
add_filter('plugin_action_links_' . plugin_basename(__FILE__), function ($links) {
    $settings_link = '<a href="' . admin_url('options-general.php?page=sms-aliyun-config') . '">' . __('设置') . '</a>';
    array_unshift($links, $settings_link);
    return $links;
});

// 短信签名回调函数
function sms_aliyun_signName_callback()
{
    $sms_aliyun = new SMS_Aliyun();
    $signNameList = $sms_aliyun->getSmsSignList();
    $current_value = get_option('sms_aliyun_signName');
    echo '<select id="sms_aliyun_signName" name="sms_aliyun_signName">';
    echo '<option value="">请选择</option>';
    foreach ($signNameList as $item) {
        $selected = ($current_value === $item->signName) ? 'selected' : '';
        echo '<option value="' . esc_attr($item->signName) . '" ' . $selected . '>' . esc_html($item->signName) . '</option>';
    }
    echo '</select>';
}

// 短信模板回调函数
function sms_aliyun_templateCode_callback()
{
    $sms_aliyun = new SMS_Aliyun();
    $templateCodeList = $sms_aliyun->getSmsTemplateList();
    $current_value = get_option('sms_aliyun_templateCode');
    echo '<select id="sms_aliyun_templateCode" name="sms_aliyun_templateCode">';
    echo '<option value="">请选择</option>';
    foreach ($templateCodeList as $item) {
        $selected = ($current_value === $item->templateCode) ? 'selected' : '';
        echo '<option value="' . esc_attr($item->templateCode) . '" ' . $selected . '>' . esc_html($item->templateName) . '</option>';
    }
    echo '</select>';
}

// 发送号码回调函数
function sms_aliyun_phoneNumber_callback()
{
    $value = get_option('sms_aliyun_phoneNumber');
    echo '<input type="text" class="regular-text" id="sms_aliyun_phoneNumber" name="sms_aliyun_phoneNumber" value="' . esc_attr($value) . '" />';
}

// 短信服务地址回调函数
function sms_aliyun_endpoint_callback()
{
    $value = get_option('sms_aliyun_options');
    echo '<input type="text" class="regular-text" id="sms_aliyun_endpoint" name="sms_aliyun_endpoint" value="' . esc_attr($value['endpoint'] ?? '') . '" />';
}

// AccessKey ID回调函数
function sms_aliyun_accessKeyId_callback()
{
    $value = get_option('sms_aliyun_options');
    echo '<input type="text" class="regular-text" id="sms_aliyun_accessKeyId" name="sms_aliyun_accessKeyId" value="' . esc_attr($value['accessKeyId'] ?? '') . '" />';
}

// AccessKey Secret回调函数
function sms_aliyun_accessKeySecret_callback()
{
    $value = get_option('sms_aliyun_options');
    echo '<input type="password" class="regular-text" id="sms_aliyun_accessKeySecret" name="sms_aliyun_accessKeySecret" value="' . esc_attr($value['accessKeySecret'] ?? '') . '" />';
}
