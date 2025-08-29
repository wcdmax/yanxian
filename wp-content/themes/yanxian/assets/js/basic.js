document.addEventListener('DOMContentLoaded', function () {
    if (document.querySelector('.yx-navbar-phone-number')) {
        const phoneNumber = '19925438691';
        console.log('Phone Number:', phoneNumber);
        // 遍历电话号码的每个字符，使用span元素包裹
        for (let i = 0; i < phoneNumber.length; i++) {
            const span = document.createElement('span');
            span.textContent = phoneNumber[i];
            document.querySelector('.yx-navbar-phone-number').appendChild(span);
        }
    }

    // 侧边栏返回顶部按钮
    const topButton = document.querySelector('.yx-sidebar-top');
    const scrollThreshold = 500;

    // 检查初始滚动位置
    function checkScrollPosition() {
        if (window.scrollY > scrollThreshold) {
            topButton.classList.add('show');
        } else {
            topButton.classList.remove('show');
        }
    }

    // 页面加载时检查一次
    checkScrollPosition();

    // 监听滚动事件
    window.addEventListener('scroll', checkScrollPosition);

    // 点击返回顶部
    topButton.addEventListener('click', function (e) {
        e.preventDefault();
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
});