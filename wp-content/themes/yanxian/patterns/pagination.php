<?php

/**
 * Title: 列表分页
 * Slug: yanxian/pagination
 * Categories: yanxian, pagination
 * Description: 显示一个列表分页，用户可以在不同的页面之间进行切换
 */

global $wp_query;
$custom_query = get_query_var('pagination_query');
$query = $custom_query ? $custom_query : $wp_query;

// 确保 query 对象存在
if (!$query || !is_object($query)) {
    return;
}

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

$args = array(
	'type' => 'array',
	'show_all' => false,
	'prev_text' => '上一页',
	'next_text' => '下一页',
	'format' => '?paged=%#%',
	'current' => max( 1, $paged ),
	'total' => isset($query->max_num_pages) ? $query->max_num_pages : 1,
	'base' => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
);

$links = paginate_links( $args );

// 只有当存在多页内容时才显示分页
if( is_array( $links ) && isset($query->max_num_pages) && $query->max_num_pages > 1 ) {
	echo '<nav aria-label="Pagination"><ul uk-margin class="uk-pagination yx-pagination" uk-scrollspy="cls: uk-animation-scale-up; target: > li; delay: 100;">';

	// 遍历分页链接
	foreach( $links as $link ) {
		echo '<li>' . $link . '</li>';
	}

	echo '</ul></nav>';
}