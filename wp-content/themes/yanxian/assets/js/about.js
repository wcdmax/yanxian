document.addEventListener('DOMContentLoaded', function () {
    new Swiper('#aboutSwiper', {
        loop: true,
        speed: 2000,
        slidesPerView: 3,
        spaceBetween: 10,
        centeredSlides: true,
        breakpoints: {
            768: {
                slidesPerView: 1,
            },
            960: {
                slidesPerView: 3,
            }
        },
        autoplay: {
            delay: 5000,
            disableOnInteraction: false
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
    });

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