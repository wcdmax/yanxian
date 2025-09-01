document.addEventListener('DOMContentLoaded', function () {
    // 主页轮播图
    new Swiper(document.getElementById('main-slide'), {
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

    // 新闻轮播图
    const newsSlideElement = document.getElementById('news-slide');
    if (newsSlideElement) {
        const newsSwiper = new Swiper(newsSlideElement, {
            loop: true,
            speed: 2000,
            effect: 'fade',
            autoplay: {
                delay: 5000,
                disableOnInteraction: false
            },
            pagination: {
                clickable: true,
                el: '.swiper-pagination',
            },
            on: {
                init: function () {
                    swiperAnimateCache(this);
                    swiperAnimate(this);
                },
                slideChangeTransitionStart: function (swiper) {
                    swiperAnimate(swiper);
                }
            }
        });

        // 新闻轮播图鼠标悬停暂停功能
        newsSlideElement.addEventListener('mouseenter', function() {
            newsSwiper.autoplay.stop();
        });
        
        newsSlideElement.addEventListener('mouseleave', function() {
            newsSwiper.autoplay.start();
        });
    }

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

    // 解决方案列表自动轮播和鼠标悬停效果
    const solutionList = document.querySelector('.yx-solution-list');
    if (solutionList) {
        const solutionItems = solutionList.querySelectorAll('.yx-solution-item');
        let intervalId = null;
        let currentIndex = 0;

        // 设置当前激活项
        function setActive(index) {
            solutionItems.forEach((item, i) => {
                item.classList.toggle('active', i === index);
            });
        }

        // 开始自动切换
        function startAutoSwitch() {
            intervalId = setInterval(() => {
                currentIndex = (currentIndex + 1) % solutionItems.length;
                setActive(currentIndex);
            }, 5000);
        }

        // 停止自动切换
        function stopAutoSwitch() {
            if (intervalId) {
                clearInterval(intervalId);
                intervalId = null;
            }
        }

        // 鼠标悬停时切换active并暂停自动切换，移出时恢复自动切换
        solutionItems.forEach((item, idx) => {
            item.addEventListener('mouseenter', function () {
                stopAutoSwitch();
                setActive(idx);
                currentIndex = idx;
            });
            item.addEventListener('mouseleave', function () {
                startAutoSwitch();
            });
        });

        // 初始化active项
        setActive(currentIndex);
        startAutoSwitch();
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