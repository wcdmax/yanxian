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
});
