<?php

/**
 * Title: 条件筛选
 * Slug: yanxian/prod-filter
 * Categories: yanxian, prod-filter
 * Description: 显示一个条件筛选组件，用户可以选择不同的筛选条件
 */
?>

<div class="yx-prod-filter yx-sider-box uk-padding-small uk-border-rounded uk-box-shadow-small">
    <h3><span uk-icon="icon: list"></span>条件筛选</h3>
    <hr>
    <ul uk-accordion="multiple: true" uk-scrollspy="cls: uk-animation-scale-up; target: > li > a; delay: 100">
        <li class="uk-open">
            <a href="#" class="uk-accordion-title"><img alt="平台架构" src="<?php echo get_theme_file_uri('/assets/icons/platform_architecture.png') ?>">平台架构</a>
            <div class="uk-accordion-content">
                <form>
                    <ul class="uk-list uk-list-bullet">
                        <li><label><input type="checkbox" name="platform-architecture[]" value="x86"> x86</label></li>
                        <li><label><input type="checkbox" name="platform-architecture[]" value="x64"> x64</label></li>
                        <li><label><input type="checkbox" name="platform-architecture[]" value="arm"> ARM</label></li>
                    </ul>
                </form>
            </div>
        </li>
        <li>
            <a href="#" class="uk-accordion-title"><img alt="操作系统" src="<?php echo get_theme_file_uri('/assets/icons/operating_system.png') ?>">操作系统</a>
            <div class="uk-accordion-content">
                <form>
                    <ul class="uk-list uk-list-bullet">
                        <li><label><input type="checkbox" name="operating-system[]" value="linux"> Linux</label></li>
                        <li><label><input type="checkbox" name="operating-system[]" value="macos"> Android</label></li>
                        <li><label><input type="checkbox" name="operating-system[]" value="windows"> Windows</label></li>
                        <li><label><input type="checkbox" name="operating-system[]" value="chinauos"> 统信系统</label></li>
                        <li><label><input type="checkbox" name="operating-system[]" value="harmonyos"> 鸿蒙系统</label></li>
                        <li><label><input type="checkbox" name="operating-system[]" value="kylinos"> 麒麟系统</label></li>
                    </ul>
                </form>
            </div>
        </li>
        <li>
            <a href="#" class="uk-accordion-title"><img alt="屏幕尺寸" src="<?php echo get_theme_file_uri('/assets/icons/screen_size.png') ?>">屏幕尺寸</a>
            <div class="uk-accordion-content">
                <form>
                    <ul class="uk-list uk-list-bullet">
                        <li><label><input type="checkbox" name="screen-size[]" value="7">7"(16:9)</label></li>
                        <li><label><input type="checkbox" name="screen-size[]" value="8">8"(4:3)</label></li>
                        <li><label><input type="checkbox" name="screen-size[]" value="10_1">10.1"(16:9)</label></li>
                        <li><label><input type="checkbox" name="screen-size[]" value="10_4">10.4"(4:3)</label></li>
                        <li><label><input type="checkbox" name="screen-size[]" value="12_1">12.1"(4:3)</label></li>
                        <li><label><input type="checkbox" name="screen-size[]" value="12_1_1">12.1"(16:10)</label></li>
                        <li><label><input type="checkbox" name="screen-size[]" value="12_5">12.5"(16:9)</label></li>
                        <li><label><input type="checkbox" name="screen-size[]" value="13_3">13.3"(16:9)</label></li>
                        <li><label><input type="checkbox" name="screen-size[]" value="15">15"(4:3)</label></li>
                        <li><label><input type="checkbox" name="screen-size[]" value="15_6">15.6"(16:9)</label></li>
                        <li><label><input type="checkbox" name="screen-size[]" value="17">17"(4:3)</label></li>
                        <li><label><input type="checkbox" name="screen-size[]" value="18_5">18.5"(16:9)</label></li>
                        <li><label><input type="checkbox" name="screen-size[]" value="19">19"(4:3)</label></li>
                        <li><label><input type="checkbox" name="screen-size[]" value="19_1">19"(16:9)</label></li>
                        <li><label><input type="checkbox" name="screen-size[]" value="21_5">21.5"(16:9)</label></li>
                        <li><label><input type="checkbox" name="screen-size[]" value="27">27"(16:9)</label></li>
                        <li><label><input type="checkbox" name="screen-size[]" value="32">32"(16:9)</label></li>
                    </ul>
                </form>
            </div>
        </li>
        <li>
            <a href="#" class="uk-accordion-title"><img alt="触屏类型" src="<?php echo get_theme_file_uri('/assets/icons/touch_type.png') ?>">触屏类型</a>
            <div class="uk-accordion-content">
                <form>
                    <ul class="uk-list uk-list-bullet">
                        <li><label><input type="checkbox" name="touch-type[]," value="touch_type_capacitive">电容触摸屏</label></li>
                        <li><label><input type="checkbox" name="touch-type[]," value="touch_type_resistive">电阻触摸屏</label></li>
                        <li><label><input type="checkbox" name="touch-type[]," value="touch_type_infrared">红外触摸屏</label></li>
                        <li><label><input type="checkbox" name="touch-type[]," value="touch_type_none">不带触摸屏</label></li>
                    </ul>
                </form>
            </div>
        </li>
        <li>
            <a href="#" class="uk-accordion-title"><img alt="中央处理器" src="<?php echo get_theme_file_uri('/assets/icons/processor.png') ?>">中央处理器</a>
            <div class="uk-accordion-content">
                <form>
                    <ul class="uk-list uk-list-bullet">
                        <li><label><input type="checkbox" name="processor[]" value="cpu_intel_core">Intel Core (酷睿)</label></li>
                        <li><label><input type="checkbox" name="processor[]" value="cpu_intel_pentium">Intel Pentium (奔腾)</label></li>
                        <li><label><input type="checkbox" name="processor[]" value="cpu_intel_celeron">Intel Celeron (赛扬)</label></li>
                        <li><label><input type="checkbox" name="processor[]" value="cpu_intel_atom">Intel Atom (凌动)</label></li>
                        <li><label><input type="checkbox" name="processor[]" value="cpu_rockchip">Rockchip (瑞芯微)</label></li>
                        <li><label><input type="checkbox" name="processor[]" value="cpu_phytium">Phytium (飞腾)</label></li>
                    </ul>
                </form>
            </div>
        </li>
    </ul>
</div>