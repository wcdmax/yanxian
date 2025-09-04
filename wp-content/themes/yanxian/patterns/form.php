<?php

/**
 * Title: 留言反馈
 * Slug: yanxian/form
 * Categories: yanxian, form
 * Description: 显示一个留言反馈组件，用户可以填写反馈信息
 */

$id = get_query_var('form_id', 'form');
$is_modal = get_query_var('is_modal', false);
$title = get_query_var('form_title', '留言反馈');
$is_contact = get_query_var('is_contact', false);
$subtitle = get_query_var('subtitle', '5分钟极速响应');
?>
<?php if ($is_modal): ?>
    <div class="uk-modal-header uk-margin-bottom uk-padding-remove-horizontal" uk-scrollspy="cls: uk-animation-slide-left-small; target: > h4; delay: 200;">
        <h4><span uk-icon="commenting"></span> <?php echo $title; ?> <span class="uk-text-meta uk-text-danger"><?php echo $subtitle; ?></span></h4>
    </div>
<?php elseif (!$is_contact): ?>
    <div class="yx-sider-box uk-padding-small uk-border-rounded uk-box-shadow-small uk-background-default">
        <h4 uk-scrollspy="cls: uk-animation-slide-left-medium; delay: 200;"><span uk-icon="icon: commenting; ratio: 1.35" class="uk-margin-small-right"></span><?php echo $title; ?> <span class="uk-text-meta uk-text-danger"><?php echo $subtitle; ?></span></h4>
<?php endif; ?>
    <form id="<?php echo $id; ?>" class="uk-form-stacked" uk-scrollspy="cls: uk-animation-slide-left-small; target: > div; delay: 100;">
        <div class="uk-margin">
            <label class="uk-form-label" for="name"><span uk-icon="user"></span> 姓名</label>
            <div class="uk-form-controls">
                <input id="name" type="text" class="uk-input uk-border-rounded" placeholder="请输入您的姓名">
                <span class="yx-form-error"></span>
            </div>
        </div>
        <div class="uk-margin">
            <label class="uk-form-label" for="phone"><span uk-icon="receiver"></span> 电话</label>
            <div class="uk-form-controls">
                <input id="phone" type="tel" class="uk-input uk-border-rounded" placeholder="请输入您的电话">
                <span class="yx-form-error"></span>
            </div>
        </div>
        <div class="uk-margin">
            <label class="uk-form-label"><span uk-icon="location"></span> 地址</label>
            <div uk-grid class="uk-grid-small uk-child-width-1-3">
                <div>
                    <select name="province" aria-label="所在省份" class="uk-select uk-border-rounded"></select>
                    <span class="yx-form-error"></span>
                </div>
                <div>
                    <select name="city" aria-label="所在城市" class="uk-select uk-border-rounded">
                        <option value="">-- 城市 --</option>
                    </select>
                    <span class="yx-form-error"></span>
                </div>
                <div>
                    <select name="district" aria-label="所在辖区" class="uk-select uk-border-rounded">
                        <option value="">-- 辖区 --</option>
                    </select>
                    <span class="yx-form-error"></span>
                </div>
            </div>
        </div>
        <div class="uk-margin">
            <label class="uk-form-label"><span uk-icon="comment"></span> 留言信息</label>
            <div class="uk-form-controls">
                <textarea rows="4" id="message" name="message" class="uk-textarea uk-border-rounded" placeholder="请输入您的留言信息"></textarea>
                <span class="yx-form-error"></span>
            </div>
        </div>
        <div class="uk-margin-small-top" uk-scrollspy-class="uk-animation-fade">
            <button id="trace-message" type="submit" class="uk-button yx-button-primary uk-border-pill">提交留言</button>
            <button type="reset" class="uk-button uk-button-default uk-border-pill uk-margin-small-left">重置表单</button>
        </div>
    </form>
    <?php if (!$is_modal && !$is_contact): echo '</div>'; endif; ?>