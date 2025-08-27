document.addEventListener('DOMContentLoaded', function () {
    const swiper = new Swiper(document.getElementById('main-slide'), {
        loop: true,
        speed: 2000,
        effect: 'fade',
        autoplay: false,
        autoplay: {
            delay: 5000,
            disableOnInteraction: false,
        },
        pagination: {
            clickable: true,
            el: '.swiper-pagination',
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    });

    // Scroll Down 功能
    const scrollDownElement = document.querySelector('.yx-swiper-scroll-down');
    if (scrollDownElement) {
        scrollDownElement.addEventListener('click', function () {
            const viewportHeight = window.innerHeight;
            window.scrollTo({
                top: viewportHeight,
                behavior: 'smooth'
            });
        });
    }

    // 解决方案列表鼠标悬停效果
    const solutionList = document.querySelector('.yx-solution-list');
    if (solutionList) {
        const solutionItems = solutionList.querySelectorAll('.yx-solution-item');

        solutionItems.forEach(item => {
            item.addEventListener('mouseenter', function () {
                // 移除所有其他项的 active 类
                solutionItems.forEach(otherItem => {
                    otherItem.classList.remove('active');
                });

                // 为当前悬停项添加 active 类
                this.classList.add('active');
            });
        });
    }
});
