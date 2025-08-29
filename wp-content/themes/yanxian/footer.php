<footer class="uk-section uk-section-small uk-background-secondary">
    <div class="uk-container uk-container-large">
        <div uk-grid class="uk-grid-small uk-grid-match">
            <div class="uk-width-1-3">
                <ul class="uk-list yx-footer-list" uk-scrollspy="cls: uk-animation-slide-left; target: > li; delay: 100">
                    <li class="yx-footer-item">
                        <p>销售热线</p>
                        <h4>19925438691</h4>
                    </li>
                    <li class="yx-footer-item">
                        <p>总机号码</p>
                        <h4>400-8898-583</h4>
                    </li>
                    <li class="yx-footer-item">
                        <p>邮箱地址</p>
                        <h4>service@yxtouch.com</h4>
                    </li>
                    <li class="yx-footer-item">
                        <p>公司地址</p>
                        <p>深圳市宝安区沙井街道大王山社区大王山第三工业区A1栋六层</p>
                    </li>
                </ul>
            </div>
            <div class="uk-width-2-3">
                <div uk-grid class="uk-grid-small uk-grid-match uk-child-width-1-5">
                    <?php
                    wp_nav_menu([
                        'depth' => 2,
                        'container' => false,
                        'items_wrap' => '%3$s',
                        'theme_location' => 'footer_menu',
                        'walker' => new Footer_Menu_Walker(),
                    ]);
                    ?>
                </div>
            </div>
        </div>
        <hr class="yx-footer-hr uk-margin-top">
        <div class="uk-text-center yx-footer-copyright">
            <p class="uk-text-meta uk-margin-small-bottom" uk-scrollspy="cls: uk-animation-scale-up">Copyright © 2013~<?php echo date('Y'); ?> 深圳市研显触控科技有限公司 All Rights Reserved. <a class="uk-text-decoration-none" href="https://beian.miit.gov.cn/" target="_blank">粤ICP备2025399431号</a></p>
        </div>
    </div>
</footer>
<?php wp_footer(); ?>
</body>

</html>