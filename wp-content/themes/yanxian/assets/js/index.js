document.addEventListener('DOMContentLoaded', function () {
    const swiper = new Swiper(document.getElementById('main-slide'), {
        loop: true,
        speed: 2000,
        effect: 'fade',
        autoplay: false,
        // autoplay: {
        //     delay: 5000,
        //     disableOnInteraction: false,
        // },
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
});
