<div class="yx-sidebar">
    <ul class="uk-list" uk-scrollspy="cls: uk-animation-slide-right; target: > li; delay: 100;">
        <li>
            <div class="yx-sidebar-phone yx-sidebar-item">
                <img alt="电话咨询" src="//static.yxtouch.com/assets/icons/400.png">
                <h3 class="yx-sidebar-phone-number"><?php echo get_option('company_phone'); ?></h3>
            </div>
        </li>
        <li>
            <div class="yx-sidebar-wechat yx-sidebar-item">
                <img alt="微信联系" src="//static.yxtouch.com/assets/icons/wechat.png">
                <div class="yx-sidebar-qrcode">
                    <img alt="微信联系" src="<?php echo get_option('company_qrcode'); ?>">
                </div>
            </div>
        </li>
        <li>
            <div class="yx-sidebar-online yx-sidebar-item" uk-tooltip="pos: left; title: 在线咨询; delay: 500;">
                <img alt="在线咨询" src="//static.yxtouch.com/assets/icons/online.png">
            </div>
        </li>
        <li>
            <div class="yx-sidebar-top yx-sidebar-item" uk-tooltip="pos: left; title: 返回顶部; delay: 500;">
                <img alt="返回顶部" src="//static.yxtouch.com/assets/icons/top.png">
            </div>
        </li>
    </ul>
</div>

<div uk-modal id="modal-form" class="uk-flex-top">
    <div class="uk-modal-dialog uk-modal-body uk-padding-small uk-border-rounded uk-margin-auto-vertical">
        <button uk-close type="button" class="uk-modal-close-default"></button>
        <?php
            set_query_var('is_modal', true);
            set_query_var('id', 'global-form');
            set_query_var('is_contact', false);
            set_query_var('form_title', '免费借测');
            echo do_blocks('<!-- wp:pattern {"slug":"yanxian/form"} /-->');
        ?>
    </div>
</div>