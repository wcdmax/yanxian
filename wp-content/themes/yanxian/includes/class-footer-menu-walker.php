<?php

/**
 * 自定义底部菜单 Walker，适配五列分组结构
 */
class Footer_Menu_Walker extends Walker_Nav_Menu
{
    // 记录当前分组（一级菜单）
    private $column_open = false;
    private $column_count = 0;

    // 不输出多余ul，二级菜单只输出li
    function start_lvl(&$output, $depth = 0, $args = null) {}
    function end_lvl(&$output, $depth = 0, $args = null) {}

    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
    {
        // 一级菜单作为分组标题
        if ($depth === 0) {
            if ($this->column_open) {
                $output .= '</ul></div>';
            }
            $output .= '<div>';
            $output .= '<ul class="uk-list yx-footer-list" uk-scrollspy="cls: uk-animation-slide-top-small; target: > li; delay: 100">';
            $output .= '<li class="yx-footer-item"><h4 class="uk-footer-title">' . esc_html($item->title) . '</h4></li>';
            $this->column_open = true;
        } else {
            // 二级菜单为普通链接
            $output .= '<li class="yx-footer-item">';
            $output .= '<a href="' . esc_url($item->url) . '" target="_blank" class="uk-text-decoration-none">' . esc_html($item->title) . '</a>';
            $output .= '</li>';
        }
    }

    function end_el(&$output, $item, $depth = 0, $args = null)
    {
        // 结束一级菜单分组
        // 二级菜单不做处理
    }
    // 析构时关闭最后一个分组
    function __destruct()
    {
        if ($this->column_open) {
            echo '</ul></div>';
            $this->column_open = false;
        }
    }
}
