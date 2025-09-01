<?php

/**
 * Title: 列表分页
 * Slug: yanxian/pagination
 * Categories: yanxian, pagination
 * Description: 显示一个列表分页，用户可以在不同的页面之间进行切换
 */

$query = isset($args['query']) ? $args['query'] : $wp_query;
$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

$args = array(
	'type' => 'array',
	'show_all' => false,
	'prev_text' => '上一页',
	'next_text' => '下一页',
	'format' => '?paged=%#%',
	'current' => max( 1, $paged ),
	'total' => $query->max_num_pages,
	'base' => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
);

$links = paginate_links( $args );

if( is_array( $links ) ) {
	echo '<nav aria-label="Pagination"><ul uk-margin class="uk-pagination yx-pagination" uk-scrollspy="cls: uk-animation-scale-up; target: > li; delay: 100;">';

	// 遍历分页链接
	foreach( $links as $link ) {
		echo '<li>' . $link . '</li>';
	}

	echo '</ul></nav>';
}