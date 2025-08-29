<div class="yx-sidebar">
    <ul class="uk-list" uk-scrollspy="cls: uk-animation-slide-right; target: > li; delay: 100;">
        <li>
            <div class="yx-sidebar-phone yx-sidebar-item">
                <img alt="电话咨询" src="<?php echo get_theme_file_uri('/assets/icons/400.png'); ?>">
                <h3 class="yx-sidebar-phone-number"><?php echo get_option('company_phone', '19925438691'); ?></h3>
            </div>
        </li>
        <li>
            <div class="yx-sidebar-wechat yx-sidebar-item">
                <img alt="微信联系" src="<?php echo get_theme_file_uri('/assets/icons/wechat.png'); ?>">
                <div class="yx-sidebar-qrcode">
                    <img alt="微信联系" src="<?php echo get_option('company_qrcode', get_theme_file_uri('/assets/images/qrcode.jpg')); ?>">
                </div>
            </div>
        </li>
        <li>
            <div class="yx-sidebar-online yx-sidebar-item" uk-tooltip="pos: left; title: 在线咨询; delay: 500;">
                <img alt="在线咨询" src="<?php echo get_theme_file_uri('/assets/icons/online.png'); ?>">
            </div>
        </li>
        <li>
            <div class="yx-sidebar-top yx-sidebar-item" uk-tooltip="pos: left; title: 返回顶部; delay: 500;">
                <img alt="返回顶部" src="<?php echo get_theme_file_uri('/assets/icons/top.png'); ?>">
            </div>
        </li>
    </ul>
</div>