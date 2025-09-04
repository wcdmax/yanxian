<?php

// 添加SMTP配置子菜单
function add_smtp_config_submenu() {
	add_submenu_page(
		'options-general.php', // 父菜单slug
		'SMTP配置', // 子菜单标题
		'SMTP配置', // 子菜单标签
		'manage_options', // 权限等级
		'smtp-config', // 子菜单slug
		'smtp_config_page', // 回调函数
        10
	);
}
add_action('admin_menu', 'add_smtp_config_submenu');

// SMTP配置页面回调函数
function smtp_config_page(): void {
	if (!current_user_can('manage_options')) {
		wp_die(__('You do not have sufficient permissions to access this page.'));
	}
	?>
	<div class="wrap">
		<h1>SMTP配置</h1>
		<form method="post" action="options.php">
			<?php
			settings_fields('smtp_config_group');
			do_settings_sections('smtp-config');
			submit_button();
			?>
		</form>
	</div>
	<?php
}

// 注册SMTP配置设置和字段
function register_smtp_config_settings(): void {
	register_setting('smtp_config_group', 'smtp_port');
	register_setting('smtp_config_group', 'smtp_host');
	register_setting('smtp_config_group', 'smtp_username');
	register_setting('smtp_config_group', 'smtp_password');
	register_setting('smtp_config_group', 'smtp_receivers');
	register_setting('smtp_config_group', 'smtp_encryption');

	add_settings_section(
		'smtp_config_section',
		'',
		'',
		'smtp-config',
	);

	add_settings_field(
		'smtp_encryption',
		'类型',
		'smtp_encryption_callback',
		'smtp-config',
		'smtp_config_section'
	);

	add_settings_field(
		'smtp_port',
		'端口',
		'smtp_port_callback',
		'smtp-config',
		'smtp_config_section'
	);


	add_settings_field(
		'smtp_host',
		'地址',
		'smtp_host_callback',
		'smtp-config',
		'smtp_config_section'
	);
	
	add_settings_field(
		'smtp_username',
		'用户名',
		'smtp_username_callback',
		'smtp-config',
		'smtp_config_section'
	);
	
	add_settings_field(
		'smtp_password',
		'用户密码',
		'smtp_password_callback',
		'smtp-config',
		'smtp_config_section'
	);

	add_settings_field(
		'smtp_receivers',
		'收件人组',
		'smtp_receivers_callback',
		'smtp-config',
		'smtp_config_section'
	);
}
add_action('admin_init', 'register_smtp_config_settings');

// 在合适的位置添加加载脚本和样式的函数
function enqueue_smtp_assets($hook) {
	if ($hook !== 'settings_page_smtp-config') {
		return;
	}

	wp_enqueue_style(
		'smtp-receivers',
		get_template_directory_uri() . '/assets/css/smtp-receivers.css',
		array(),
		filemtime(get_template_directory() . '/assets/css/smtp-receivers.css')
	);

	wp_enqueue_script(
		'smtp-receivers',
		get_template_directory_uri() . '/assets/js/smtp-receivers.js',
		array('jquery'),
		filemtime(get_template_directory() . '/assets/js/smtp-receivers.js'),
		true
	);
}
add_action('admin_enqueue_scripts', 'enqueue_smtp_assets');

// SMTP 服务器地址回调函数
function smtp_host_callback(): void {
	?>
	<input type="text" id="smtp_host" name="smtp_host" value="<?php echo esc_attr(get_option('smtp_host')); ?>" class="regular-text" />
	<?php
}

// SMTP 端口回调函数
function smtp_port_callback(): void {
	?>
	<input type="number" id="smtp_port" name="smtp_port" value="<?php echo esc_attr(get_option('smtp_port')); ?>" class="small-text" />
	<?php
}

// SMTP 用户名回调函数
function smtp_username_callback(): void {
	?>
	<input type="text" id="smtp_username" name="smtp_username" value="<?php echo esc_attr(get_option('smtp_username')); ?>" class="regular-text" />
	<?php
}

// SMTP 收件人回调函数
function smtp_receivers_callback(): void {
	$receivers = get_option('smtp_receivers', '');
	$receivers_array = $receivers ? explode(',', $receivers) : array();

	?>
	<div class="smtp-receivers-wrapper">
		<div class="smtp-receivers-input">
			<div class="smtp-receivers-list">
				<?php foreach ($receivers_array as $receiver): ?>
					<?php if (!empty($receiver)): ?>
						<span class="smtp-receiver-tag">
							<?php echo esc_html(trim($receiver)); ?>
							<button type="button" class="remove-receiver" aria-label="移除">×</button>
						</span>
					<?php endif; ?>
				<?php endforeach; ?>
				<input type="text" 
					id="smtp_receivers_input" 
					placeholder="输入邮箱地址后按回车添加"
					style="border: none; outline: none; background: none; margin: 0; padding: 4px 0;" />
			</div>
		</div>
		<input type="hidden" 
			id="smtp_receivers" 
			name="smtp_receivers" 
			value="<?php echo esc_attr($receivers); ?>" />
	</div>
	<?php
}

// SMTP 密码回调函数
function smtp_password_callback(): void {
	echo '<input type="password" id="smtp_password" name="smtp_password" value="' . esc_attr(get_option('smtp_password')) . '" class="regular-text" />';
}

// SMTP 加密类型回调函数
function smtp_encryption_callback(): void {
	$options = array('none' => '无', 'ssl' => 'SSL', 'tls' => 'TLS');
	?>
	<select id="smtp_encryption" name="smtp_encryption">
		<?php foreach ($options as $value => $label): ?>
			<option value="<?php echo esc_attr($value); ?>" <?php selected(get_option('smtp_encryption'), $value); ?>>
				<?php echo $label; ?>
			</option>
		<?php endforeach; ?>
	</select>
	<?php
}
