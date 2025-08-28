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

    // 关于我们数字计数动画
    function animateNumbers() {
        const aboutItems = document.querySelectorAll('.yx-about-item h2');

        aboutItems.forEach(item => {
            const target = parseInt(item.textContent);
            let current = 0;
            const duration = 2000; // 动画持续时间
            const increment = target / (duration / 16); // 每帧增加的数值

            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    current = target;
                    clearInterval(timer);
                }
                item.textContent = Math.floor(current);
            }, 16);
        });
    }

    // 使用 Intersection Observer 检测元素进入视口
    const aboutSection = document.querySelector('.yx-about-section');
    if (aboutSection) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    // 延迟执行数字动画，配合CSS渐入动画
                    setTimeout(() => {
                        animateNumbers();
                    }, 500);
                    observer.unobserve(entry.target);
                }
            });
        }, {
            threshold: 0.3 // 当30%的区域进入视口时触发
        });

        observer.observe(aboutSection);
    }
});