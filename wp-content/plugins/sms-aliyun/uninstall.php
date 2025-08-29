<?php

if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

delete_option('sms_aliyun_options');
