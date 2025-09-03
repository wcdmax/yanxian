document.addEventListener('DOMContentLoaded', function () {
    const thumbSwiper = new Swiper('.yx-prod-gallery-thumb .swiper', {
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
});