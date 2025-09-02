<?php get_header(); ?>
<div class="swiper" id="main-slide">
    <div class="swiper-wrapper">
        <div class="swiper-slide">
            <img alt="Slide 1" src="//static.yxtouch.com/assets/images/slide1.jpg">
        </div>
        <div class="swiper-slide">
            <img alt="Slide 2" src="//static.yxtouch.com/assets/images/slide2.jpg">
        </div>
        <div class="swiper-slide">
            <img alt="Slide 3" src="//static.yxtouch.com/assets/images/slide3.jpg">
        </div>
        <div class="swiper-slide">
            <img alt="Slide 4" src="//static.yxtouch.com/assets/images/slide4.jpg">
        </div>
    </div>
    <div class="uk-flex uk-flex-between uk-flex-middle uk-position-medium yx-main-slide-caption uk-position-bottom-center uk-position-z-index-high">
        <div class="swiper-pagination"></div>
        <div class="uk-flex uk-flex-column uk-flex-center uk-flex-middle yx-swiper-scroll-down uk-position-center-horizontal">
            <h4 class="uk-margin-remove">Scroll Down</h4>
            <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 16L20 24L28 16" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M12 9L20 17L28 9" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </div>
        <div class="uk-position-relative swiper-button-container">
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
        </div>
    </div>
</div>
<!-- 产品中心 Start -->
<section class="uk-section uk-background-top-left" style="background-image: url('//static.yxtouch.com/assets/images/bg-product.jpg');">
    <div class="uk-container uk-container-large">
        <h2 class="uk-section-title" uk-parallax="opacity: 0,1; x: -100,0; end: 50vh + 50%">产品中心</h2>
        <p class="uk-section-title-desc" uk-parallax="opacity: 0,1; x: 100,0; end: 50vh + 50%">源头厂家，提供全方位多行业定制方案，满足您的各种需求</p>
        <hr>
        <div uk-grid id="product" class="uk-grid-small uk-grid-match" uk-scrollspy="cls: uk-animation-scale-up; target: .yx-card-image>img; delay: 100;">
            <div class="uk-width-2-3">
                <div class="uk-card yx-card-body uk-card-hover uk-card-default uk-overflow-hidden uk-background-norepeat" style="background-image: url('//static.yxtouch.com/assets/images/provide_customized.png');">
                    <div class="uk-position-medium yx-card-customized uk-position-center-right" uk-scrollspy="cls: uk-animation-slide-bottom-small; target: > h3,p; delay: 100">
                        <h3>Provide customized<br>
                            services for customers</h3>
                        <h3 class="uk-margin-remove">为客户提供个性化定制服务</h3>
                        <p class="uk-text-meta">屏幕尺寸、接口模块、安装方式、硬件配置、OEM/ODM...</p>
                        <a href="#" class="uk-button uk-border-pill yx-button-primary" uk-scrollspy="cls: uk-animation-scale-up; delay: 400">联系我们</a>
                    </div>
                </div>
            </div>
            <div class="uk-width-1-3">
                <div class="uk-card uk-padding yx-card-body uk-card-default yx-card-category uk-overflow-hidden uk-background-cover" style="background-image: url('//static.yxtouch.com/assets/images/showcase.jpg');">
                    <div class="yx-card-title" uk-scrollspy="cls: uk-animation-slide-bottom-small; target: > *; delay: 100">
                        <h3>工控一体机</h3>
                        <p>Industrial All in One PC</p>
                    </div>
                    <div class="yx-card-image">
                        <img alt="工控一体机" src="//static.yxtouch.com/assets/images/g1a.png">
                    </div>
                </div>
            </div>
            <div class="uk-width-1-3">
                <div class="uk-card uk-padding yx-card-body uk-card-default yx-card-category uk-overflow-hidden uk-background-cover" style="background-image: url('//static.yxtouch.com/assets/images/showcase.jpg');">
                    <div class="yx-card-title" uk-scrollspy="cls: uk-animation-slide-bottom-small; target: > *; delay: 200">
                        <h3>工业触摸屏</h3>
                        <p>Industrial Display</p>
                    </div>
                    <div class="yx-card-image">
                        <img alt="工业触摸屏" src="//static.yxtouch.com/assets/images/g1a.png">
                    </div>
                </div>
            </div>
            <div class="uk-width-1-3">
                <div class="uk-card uk-padding yx-card-body uk-card-default yx-card-category uk-overflow-hidden uk-background-cover" style="background-image: url('//static.yxtouch.com/assets/images/showcase.jpg');">
                    <div class="yx-card-title" uk-scrollspy="cls: uk-animation-slide-bottom-small; target: > *; delay: 300">
                        <h3>商用广告机</h3>
                        <p>Commercial Advertising Machine</p>
                    </div>
                    <div class="yx-card-image">
                        <img alt="商用广告机" src="//static.yxtouch.com/assets/images/g1a.png">
                    </div>
                </div>
            </div>
            <div class="uk-width-1-3">
                <div class="uk-card uk-padding yx-card-body uk-card-default yx-card-category uk-overflow-hidden uk-background-cover" style="background-image: url('//static.yxtouch.com/assets/images/showcase.jpg');">
                    <div class="yx-card-title" uk-scrollspy="cls: uk-animation-slide-bottom-small; target: > *; delay: 400">
                        <h3>工业平板电脑</h3>
                        <p>Industrial Tablet PC</p>
                    </div>
                    <div class="yx-card-image">
                        <img alt="工业平板电脑" src="//static.yxtouch.com/assets/images/g1a.png">
                    </div>
                </div>
            </div>
            <div class="uk-width-expand">
                <div class="uk-padding yx-prod-system uk-background-default">
                    <div uk-grid class="uk-grid-small uk-grid-match uk-child-width-1-4">
                        <div>
                            <div class="uk-card uk-card-body uk-text-center">
                                <img alt="安卓系统" src="//static.yxtouch.com/assets/icons/android.png">
                                <h5 class="uk-margin-small-top uk-margin-remove-bottom">安卓系统</h5>
                                <small>Android 9/Android 10/ Android 11</small>
                            </div>
                        </div>
                        <div>
                            <div class="uk-card uk-card-body uk-text-center">
                                <img alt="Windows系统" src="//static.yxtouch.com/assets/icons/windows.png">
                                <h5 class="uk-margin-small-top uk-margin-remove-bottom">Windows</h5>
                                <small>Windows 7/Windows 10/Windows 11</small>
                            </div>
                        </div>
                        <div>
                            <div class="uk-card uk-card-body uk-text-center">
                                <img alt="安卓系统" src="//static.yxtouch.com/assets/icons/linux.png">
                                <h5 class="uk-margin-small-top uk-margin-remove-bottom">Linux系统</h5>
                                <small>统信OS/Ubuntu/麒麟系统/鸿蒙系统</small>
                            </div>
                        </div>
                        <div>
                            <div class="uk-card uk-card-body uk-text-center">
                                <img alt="其它系统" src="//static.yxtouch.com/assets/icons/other.png">
                                <h5 class="uk-margin-small-top uk-margin-remove-bottom">其它系统</h5>
                                <small>支持安装其它操作系统</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- 产品中心 End -->
<!-- 解决方案 Start -->
<section class="uk-section yx-solution-section">
    <div class="uk-container uk-container-large">
        <h2 class="uk-section-title" uk-parallax="opacity: 0,1; x: -100,0; end: 50vh + 50%">解决方案</h2>
        <p class="uk-section-title-desc" uk-parallax="opacity: 0,1; x: 100,0; end: 50vh + 50%">以技术为驱动，提供成熟的行业解决方案，500强信赖的工控品牌</p>
        <hr>
        <ul class="uk-flex yx-solution-list" uk-scrollspy="cls: uk-animation-scale-up; target: > li; delay: 200">
            <li class="active yx-solution-item uk-background-cover" style="background-image: url(//static.yxtouch.com/assets/images/5770c.jpg);">
                <div class="yx-solution-item-info">
                    <h3>智能制造</h3>
                    <p>基于大数据和人工智能的智能制造解决方案，助力工厂提高生产效率</p>
                </div>
            </li>
            <li class="yx-solution-item uk-background-cover" style="background-image: url(//static.yxtouch.com/assets/images/54878.jpg);">
                <div class="yx-solution-item-info">
                    <h3>医疗政务</h3>
                    <p>为医疗政务提供智能化设备和系统，提升医疗服务质量和效率</p>
                </div>
            </li>
            <li class="yx-solution-item uk-background-cover" style="background-image: url(//static.yxtouch.com/assets/images/74105.jpg);">
                <div class="yx-solution-item-info">
                    <h3>酒店餐饮</h3>
                    <p>为酒店餐饮行业提供智能化设备和系统，提升服务质量和运营效率</p>
                </div>
            </li>
            <li class="yx-solution-item uk-background-cover" style="background-image: url(//static.yxtouch.com/assets/images/f2b44.jpg);">
                <div class="yx-solution-item-info">
                    <h3>交通枢纽</h3>
                    <p>为交通枢纽提供智能化设备和系统，提升运输效率和安全性</p>
                </div>
            </li>
        </ul>
    </div>
</section>
<!-- 解决方案 End -->
<!-- 关于我们 Start -->
<section class="uk-section yx-about-section">
    <div class="uk-container uk-container-large">
        <div uk-grid class="uk-grid-small uk-grid-match">
            <div class="uk-width-3-5">
                <div class="yx-about-content" uk-scrollspy="cls: uk-animation-slide-bottom-small; target: > p; delay: 100">
                    <h2 uk-parallax="opacity: 0,1; x: -100,0; end: 50vh + 50%">关于研显触控</h2>
                    <h4 uk-parallax="opacity: 0,1; x: 100,0; end: 50vh + 50%">专注工业显控设备，提供全方位定制方案</h4>
                    <p>深圳市研显触控科技有限公司是一家专注于工业互联网领域的高新技术企业，集研发、生产、销售和服务于一体。公司主营工业一体机、工业触摸屏、工业平板电脑、商用广告机等智能终端设备，广泛应用于智能制造、医疗政务、酒店餐饮、交通枢纽、智慧零售、教育、金融等多个行业领域。我们拥有自主研发团队和现代化生产基地，具备强大的产品定制能力，能够根据客户需求提供屏幕尺寸、接口模块、安装方式、硬件配置等多维度的个性化定制服务，支持OEM/ODM合作模式，满足不同行业客户的多样化应用场景。</p>
                    <p>公司始终坚持以技术创新为核心驱动力，拥有一支高素质的软硬件研发团队。我们通过严格的质量管理体系，确保每一台设备的高可靠性和稳定性，产品已通过多项国内外权威认证（CE、CCC、FCC、ROHS）。凭借优质的产品和完善的服务体系，研显触控赢得了众多世界500强企业及行业客户的信赖与合作。</p>
                    <!-- <p>未来，研显触控将继续深耕工业显控领域，持续提升自主创新能力，致力于为客户提供更智能、更高效、更安全的工业互联网解决方案，助力中国制造业数字化转型升级，成为行业领先的智能终端设备及解决方案提供商。</p> -->
                    <a href="/about" class="uk-button uk-border-pill yx-button-primary" uk-scrollspy="cls: uk-animation-scale-up; delay: 300">了解更多</a>
                    <ul uk-grid class="uk-grid-small uk-grid-match yx-about-list uk-child-width-1-4" uk-scrollspy="cls: uk-animation-scale-up; target: > li; delay: 200">
                        <li>
                            <div class="yx-about-item">
                                <h2>200</h2>
                                <span>大型项目</span>
                            </div>
                        </li>
                        <li>
                            <div class="yx-about-item">
                                <h2>1000</h2>
                                <span>合作客户</span>
                            </div>
                        </li>
                        <li>
                            <div class="yx-about-item">
                                <h2>4000</h2>
                                <span>生产基地</span>
                            </div>
                        </li>
                        <li>
                            <div class="yx-about-item">
                                <h2>10000</h2>
                                <span>月供货量</span>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="uk-width-2-5">
                <div class="uk-text-right">
                    <img uk-scrollspy="cls: uk-animation-scale-up; delay: 200" src="//static.yxtouch.com/assets/images/about_us.png" alt="关于我们">
                </div>
            </div>
        </div>
    </div>
</section>
<!-- 关于我们 End -->
<!-- 新闻资讯 Start -->
<section class="uk-section uk-background-cover" style="background-image: url('//static.yxtouch.com/assets/images/bg-news.jpg');">
    <div class="uk-container uk-container-large">
        <h2 class="uk-section-title" uk-parallax="opacity: 0,1; x: -100,0; end: 50vh + 50%">新闻资讯</h2>
        <p class="uk-section-title-desc" uk-parallax="opacity: 0,1; x: 100,0; end: 50vh + 50%">关注研显触控，获取最新行业资讯</p>
        <hr>
        <div uk-grid class="uk-grid-small uk-grid-match">
            <div class="uk-width-2-5 yx-news-slider" uk-scrollspy="cls: uk-animation-slide-left; delay: 100">
                <div class="swiper" id="news-slide">
                    <div class="swiper-wrapper">
                        <div class="swiper-slide">
                            <img alt="研显深化国产芯片应用实践，强化工业一体机本土方案能力" src='//static.yxtouch.com/assets/images/news_1.jpg'>
                            <div class="swiper-slide-overlay uk-position-medium uk-position-bottom-center">
                                <p class="ani uk-text-meta" swiper-animate-effect="fadeInDown" swiper-animate-delay="0.3s">2025-08-28</p>
                                <h3 class="ani" swiper-animate-effect="fadeInUp" swiper-animate-delay="0.5s">研显深化国产芯片应用实践，强化工业一体机本土方案能力</h3>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <img alt="研显解读——工业平板电脑厂家芯片选型中AMD被边缘化的根本原因" src="//static.yxtouch.com/assets/images/news_2.jpg">
                            <div class="swiper-slide-overlay uk-position-medium uk-position-bottom-center">
                                <p class="ani uk-text-meta" swiper-animate-effect="fadeInDown" swiper-animate-delay="0.3s">2025-08-29</p>
                                <h3 class="ani" swiper-animate-effect="fadeInUp" swiper-animate-delay="0.5s">研显解读——工业平板电脑厂家芯片选型中AMD被边缘化的根本原因</h3>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <img alt="研显深化国产芯片应用实践，强化工业一体机本土方案能力" src="//static.yxtouch.com/assets/images/news_3.jpg">
                            <div class="swiper-slide-overlay uk-position-medium uk-position-bottom-center">
                                <p class="ani uk-text-meta" swiper-animate-effect="fadeInDown" swiper-animate-delay="0.3s">2025-08-30</p>
                                <h3 class="ani" swiper-animate-effect="fadeInUp" swiper-animate-delay="0.5s">研显深化国产芯片应用实践，强化工业一体机本土方案能力</h3>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <img alt="数字化浪潮下，工控电脑一体机成物流仓库“效率担当”！" src="//static.yxtouch.com/assets/images/news_4.jpg">
                            <div class="swiper-slide-overlay uk-position-medium uk-position-bottom-center">
                                <p class="ani uk-text-meta" swiper-animate-effect="fadeInDown" swiper-animate-delay="0.3s">2025-08-31</p>
                                <h3 class="ani" swiper-animate-effect="fadeInUp" swiper-animate-delay="0.5s">数字化浪潮下，工控电脑一体机成物流仓库“效率担当”！</h3>
                            </div>
                        </div>
                        <div class="swiper-slide">
                            <img alt="工业4.0时代，研显触控如何用定制工控一体机适配不同行业场景？" src="//static.yxtouch.com/assets/images/news_5.jpg">
                            <div class="swiper-slide-overlay uk-position-medium uk-position-bottom-center">
                                <p class="ani uk-text-meta" swiper-animate-effect="fadeInDown" swiper-animate-delay="0.3s">2025-09-01</p>
                                <h3 class="ani" swiper-animate-effect="fadeInUp" swiper-animate-delay="0.5s">工业4.0时代，研显触控如何用定制工控一体机适配不同行业场景？</h3>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-pagination"></div>
                </div>
            </div>
            <div class="uk-width-3-5 yx-news-list" uk-scrollspy="cls: uk-animation-slide-right; delay: 100">
                <div uk-grid class="uk-grid-collapse uk-child-width-1-2" uk-scrollspy="cls: uk-animation-fade; target: > div; delay: 200">
                    <div class="yx-news-item">
                        <div class="yx-news-item-info">
                            <small>2025-08-28</small>
                            <h3>研显深化国产芯片应用实践，强化工业一体机本土方案能力</h3>
                            <a href="#" class="uk-button uk-border-pill uk-button-small uk-button-default">阅读更多</a>
                        </div>
                    </div>
                    <div class="yx-news-item">
                        <div class="yx-news-item-info">
                            <small>2025-08-29</small>
                            <h3>研显解读——工业平板电脑厂家芯片选型中AMD被边缘化的根本原因</h3>
                            <a href="#" class="uk-button uk-border-pill uk-button-small uk-button-default">阅读更多</a>
                        </div>
                    </div>
                    <div class="yx-news-item">
                        <div class="yx-news-item-info">
                            <small>2025-08-30</small>
                            <h3>数字化浪潮下，工控电脑一体机成物流仓库“效率担当”！</h3>
                            <a href="#" class="uk-button uk-border-pill uk-button-small uk-button-default">阅读更多</a>
                        </div>
                    </div>
                    <div class="yx-news-item">
                        <div class="yx-news-item-info">
                            <small>2025-09-01</small>
                            <h3>工业4.0时代，研显触控如何用定制工控一体机适配不同行业场景？</h3>
                            <a href="#" class="uk-button uk-border-pill uk-button-small uk-button-default">阅读更多</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="uk-width-expand">
                <div class="uk-text-center">
                    <a href="#" class="uk-button uk-border-pill yx-button-primary" uk-scrollspy="cls: uk-animation-scale-up; delay: 300">查看更多</a>
                </div>
            </div>
</section>
<!-- 新闻资讯 End -->
<?php get_footer();
get_sidebar(); ?>