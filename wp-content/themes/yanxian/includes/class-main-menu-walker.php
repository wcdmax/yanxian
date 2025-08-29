<?php

/**
 * 自定义主菜单 Walker，适配 UK-navbar 菜单结构
 */
class Main_Menu_Walker extends Walker_Nav_Menu
{
    // 开始子菜单（一级下拉）
    function start_lvl(&$output, $depth = 0, $args = null)
    {
        if ($depth === 0) {
            $output .= "\n<div class=\"uk-navbar-dropdown yx-navbar-dropdown\" uk-dropdown=\"animation: reveal-top; animate-out: true; duration: 500\"><ul class=\"uk-nav uk-navbar-dropdown-nav\">";
        } else {
            $output .= "\n<ul class=\"uk-nav-sub\">";
        }
    }

    // 结束子菜单
    function end_lvl(&$output, $depth = 0, $args = null)
    {
        if ($depth === 0) {
            $output .= '</ul></div>' . "\n";
        } else {
            $output .= '</ul>' . "\n";
        }
    }

    // 开始菜单项
    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
    {
        $classes = empty($item->classes) ? array() : (array) $item->classes;
        $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
        $output .= '<li class="' . esc_attr($class_names) . '">';
        $atts = '';
        $atts .= ' href="' . esc_attr($item->url) . '"';
        $atts .= ' title="' . esc_attr($item->attr_title ? $item->attr_title : $item->title) . '"';
        $output .= '<a' . $atts . '>' . apply_filters('the_title', $item->title, $item->ID);
        if ($depth === 0 && in_array('menu-item-has-children', $classes)) {
            $output .= '<span uk-navbar-parent-icon></span>';
        }
        $output .= '</a>';
    }

    // 结束菜单项
    function end_el(&$output, $item, $depth = 0, $args = null)
    {
        $output .= '</li>';
    }
}
