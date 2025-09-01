<?php
/**
 * Title: 搜索框
 * Slug: yanxian/search
 * Categories: yanxian, navigation，search
 * Description: 显示一个搜索框，用户可以输入关键词进行搜索
 */

$search_placeholder = get_query_var('search_placeholder', '搜索文章');
?>

<form role="search" method="get" action="<?php echo home_url(); ?>" id="searchform" class="uk-search uk-width-expand uk-search-default yx-sidebar-search">
    <input name="s" type="search" aria-label="Search" placeholder="<?php echo $search_placeholder; ?> ..." class="uk-search-input uk-border-pill" onkeydown="return event.key != 'Enter' || this.value.trim() != ''">
    <button uk-search-icon class="uk-search-icon-flip" onclick="return this.previousElementSibling.value.trim() != ''"></button>
</form>