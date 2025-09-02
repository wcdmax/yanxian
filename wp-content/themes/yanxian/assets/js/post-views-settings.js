jQuery(document).ready(function($) {
    // 点击文本显示输入框
    $('.post-views-text').on('click', function() {
        var $wrapper = $(this).parent();
        var $input = $wrapper.find('.post-views-input');
        
        $input.show().focus(); // 显示输入框并聚焦
    });

    // 输入框失去焦点时隐藏
    $('.post-views-input').on('blur', function() {
        var $input = $(this);
        
        $input.hide(); // 隐藏输入框
    });

    // 输入框值改变时更新
    $('.post-views-input').on('change', function() {
        var $input = $(this);
        var views = $input.val();
        var $wrapper = $input.parent();
        var postId = $input.data('post-id');
        var $text = $wrapper.find('.post-views-text');
        // 移除旧的提示
        $('.tablenav').siblings('.post-views-notice').remove();
        // 获取当前行的文章标题
        var postTitle = $input.closest('tr').find('.row-title').text();


        $.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                views: views,
                post_id: postId,
                action: 'update_post_views',
            },
            success: function(response) {
                if (response.success) {
                    // 更新显示的文本
                    $text.text(views);
                    // 添加成功提示
                    $('.tablenav.top').after('<div class="notice notice-success post-views-notice"><p>【' + postTitle + '】' + response.data.message + '</p></div>');
                } else {
                    // 恢复原值
                    $input.val($input.data('original-value'));
                    $text.text($input.data('original-value'));
                    // 添加失败提示
                    $('.tablenav.top').after('<div class="notice notice-error post-views-notice"><p>【' + postTitle + '】' + response.data.message + '</p></div>');
                }
                // 5秒后自动移除提示
                setTimeout(function() { $('.tablenav.top').siblings('.post-views-notice').fadeOut(300, function() { $(this).remove(); }); }, 5000);
            },
            error: function() {
                // 恢复原值
                $input.val($input.data('original-value'));
                $text.text($input.data('original-value'));
                // 添加错误提示
                $('.tablenav.top').after('<div class="notice notice-error post-views-notice"><p>【' + postTitle + '】' + response.data.message + '</p></div>');
                // 5秒后自动移除提示
                setTimeout(function() { $('.tablenav.top').siblings('.post-views-notice').fadeOut(300, function() { $(this).remove(); }); }, 5000);
            }
        });
    });

    // 保存原始值
    $('.post-views-input').each(function() {
        $(this).data('original-value', $(this).val());
    });
});