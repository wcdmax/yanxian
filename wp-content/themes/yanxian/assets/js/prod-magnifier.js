document.addEventListener('DOMContentLoaded', function () {
    const thumbSwiper = new Swiper('.thumb-swiper', {
        freeMode: true,
        initialSlide: 0,
        slidesPerView: 5,
        spaceBetween: 10,
        direction: 'vertical',
        watchSlidesProgress: true,
        on: {
            init: function () {
                // 初始化时设置第一个slide为激活状态
                this.slides[0].classList.add('yx-thumb-active');
            }
        }
    });

    // 获取slide元素
    const slides = thumbSwiper.slides;

    // 获取大图容器
    const bigImageContainer = document.querySelector('.yx-prod-gallery-bigimg');

    // 初始化时渲染第一个大图
    (function initBigImage() {
        // 直接获取第一个缩略图img
        const firstElem = document.querySelector('.yx-prod-gallery-thumb-item img');
        renderBigImageByThumb(firstElem);
    })();

    // 渲染大图函数，支持图片和视频，图片预加载后再切换，防止频闪
    function renderBigImageByThumb(elem) {
        if (!elem) return;
        const type = elem.dataset.type;
        const alt = elem.getAttribute('alt') || '';
        const title = elem.getAttribute('title') || '';
        if (type === 'video') {
            const videoUrl = elem.dataset.video;
            bigImageContainer.innerHTML = `
            <video muted controls autoplay src="${videoUrl}"></video>`;
        } else {
            const fullUrl = elem.dataset.full;
            // 先不清空容器，创建新img
            const tempImg = new window.Image();
            tempImg.alt = alt;
            tempImg.src = fullUrl;
            tempImg.title = title;
            tempImg.style.display = 'none';
            // 可选：显示loading动画
            tempImg.onload = function () {
                tempImg.style.display = '';
                bigImageContainer.innerHTML = '';
                bigImageContainer.appendChild(tempImg);
            };
        }
    }

    // 缩略图切换时渲染大图
    Array.from(slides).forEach((slide, index) => {
        slide.addEventListener('mouseenter', function () {
            const img = slide.querySelector('img');
            renderBigImageByThumb(img);
            thumbSwiper.slideTo(index);
            // 切换激活状态
            Array.from(slides).forEach(s => s.classList.remove('yx-thumb-active'));
            slide.classList.add('yx-thumb-active');

            // 自动滑动逻辑
            if (slides.length > 5) {
                if (index >= slides.length - 2) {
                    thumbSwiper.slideTo(slides.length - 5);
                } else if (index < 3) {
                    thumbSwiper.slideTo(0);
                }
            }
        });
        // 兼容移动端点击
        slide.addEventListener('click', function () {
            const img = slide.querySelector('img');
            renderBigImageByThumb(img);
            thumbSwiper.slideTo(index);
            Array.from(slides).forEach(s => s.classList.remove('yx-thumb-active'));
            slide.classList.add('yx-thumb-active');
            if (slides.length > 5) {
                if (index >= slides.length - 2) {
                    thumbSwiper.slideTo(slides.length - 5);
                } else if (index < 3) {
                    thumbSwiper.slideTo(0);
                }
            }
        });
    });

    // 放大镜功能
    bigImageContainer.addEventListener('mousemove', function (e) {
        const img = bigImageContainer.querySelector('img');
        if (!img) return;
        const rect = img.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;

        // 计算背景位置
        const bgX = (x / rect.width) * 100;
        const bgY = (y / rect.height) * 100;

        // 设置放大镜背景
        const magnifier = document.querySelector('.yx-prod-gallery-magnifier');
        magnifier.style.backgroundPosition = `${bgX}% ${bgY}%`;
        magnifier.style.backgroundImage = `url(${img.src})`;
        magnifier.style.backgroundSize = `${rect.width * 2}px ${rect.height * 2}px`;
    });

    // 显示/隐藏放大镜
    bigImageContainer.addEventListener('mouseenter', function () {
        const img = bigImageContainer.querySelector('img');
        if (!img) return;
        const magnifier = document.querySelector('.yx-prod-gallery-magnifier');
        magnifier.style.display = 'block';
    });

    // 鼠标离开时隐藏放大镜
    bigImageContainer.addEventListener('mouseleave', function () {
        const magnifier = document.querySelector('.yx-prod-gallery-magnifier');
        magnifier.style.display = 'none';
    });

    // 当鼠标移到上一张/下一张按钮上时隐藏放大镜
    const prenext = document.querySelector('.yx-prod-gallery-zoomimg');
    const prevButton = prenext ? prenext.querySelector('.yx-prod-gallery-prenext:first-child') : null;
    const nextButton = prenext ? prenext.querySelector('.yx-prod-gallery-prenext:last-child') : null;

    // 前后切换按钮逻辑
    if (prenext && prevButton && nextButton) {
        prevButton.addEventListener('click', function (e) {
            e.preventDefault();
            const activeSlide = document.querySelector('.thumb-swiper .yx-thumb-active');
            const currentIndex = Array.from(slides).indexOf(activeSlide);
            let newIndex = currentIndex === 0 ? slides.length - 1 : currentIndex - 1;
            const img = slides[newIndex].querySelector('img');
            renderBigImageByThumb(img);
            thumbSwiper.slideTo(newIndex);
            Array.from(slides).forEach(s => s.classList.remove('yx-thumb-active'));
            slides[newIndex].classList.add('yx-thumb-active');
        });
        nextButton.addEventListener('click', function (e) {
            e.preventDefault();
            const activeSlide = document.querySelector('.thumb-swiper .yx-thumb-active');
            const currentIndex = Array.from(slides).indexOf(activeSlide);
            let newIndex = currentIndex === slides.length - 1 ? 0 : currentIndex + 1;
            const img = slides[newIndex].querySelector('img');
            renderBigImageByThumb(img);
            thumbSwiper.slideTo(newIndex);
            Array.from(slides).forEach(s => s.classList.remove('yx-thumb-active'));
            slides[newIndex].classList.add('yx-thumb-active');
        });
    }

    // 产品详情tab切换
    const switcherEl = document.querySelector('.yx-prod-tab');
    if (switcherEl) {
        const tabs = UIkit.switcher(switcherEl);
        switcherEl.querySelectorAll('li a').forEach((link, index) => {
            // 阻止默认行为
            link.addEventListener('click', (e) => e.preventDefault());
            // 显示对应标签
            link.addEventListener('mouseenter', () => tabs.show(index));
        });
    }

    // 监听元素滚动
    let observer = null;

    // 右侧信息卡片
    const productInfo = document.querySelector('.yx-prod-info');
    // 操作按钮区域
    const productActions = document.querySelector('.yx-prod-actions');
    // 下方详情区块
    const detailsSection = document.getElementById('yx-prod-details');

    // 禁止滚动事件传递到页面
    productInfo.addEventListener('wheel', function (e) {
        if (productInfo.style.position === 'fixed') {
            e.stopPropagation();
            e.preventDefault();
        }
    }, { passive: false });

    // 监听元素滚动
    observer = new IntersectionObserver((mutationsList) => {
        mutationsList.forEach(mutation => {
            if (mutation.isIntersecting) {
                // 信息卡片进入视口，取消吸顶
                productInfo.removeAttribute('style');
                // 恢复按钮区域的原始样式
                if (productActions) {
                    productActions.removeAttribute('style');
                }
                window.removeEventListener('scroll', handleFixedLimit, true);
            } else {
                // 信息卡片离开视口顶部，吸顶
                productInfo.style.width = '420px';
                productInfo.style.padding = '20px';
                productInfo.style.position = 'fixed';
                productInfo.style.boxSizing = 'border-box';
                productInfo.style.boxShadow = '0 5px 15px rgba(0, 0, 0, 0.1)';
                // 设置按钮区域的margin为0
                if (productActions) {
                    productActions.style.marginTop = '0';
                    productActions.style.marginBottom = '0';
                }
                window.addEventListener('scroll', handleFixedLimit, true);
                handleFixedLimit();
            }
        });
    }, { root: null, rootMargin: '0px', threshold: 0 });

    observer.observe(document.getElementById('yx-prod-gallery'));

    // 限制信息卡片不超出详情区底部
    function handleFixedLimit() {
        if (productInfo.style.position === 'fixed') {
            const detailsRect = detailsSection.getBoundingClientRect();
            const infoHeight = productInfo.offsetHeight;
            const minTop = 100;
            let top = minTop;
            // 如果吸顶后超出详情区底部
            if (top + infoHeight > detailsRect.bottom) {
                productInfo.style.top = (detailsRect.bottom - infoHeight) + 'px';
            } else {
                productInfo.style.top = top + 'px';
            }
        }
    }
});