document.addEventListener('DOMContentLoaded', function () {
    if (document.querySelector('.yx-navbar-phone')) {
        const phoneNumber = 19925438691;
        // 遍历电话号码的每个字符，使用span元素包裹
        for (let i = 0; i < phoneNumber.length; i++) {
            const span = document.createElement('span');
            span.textContent = phoneNumber[i];
            document.querySelector('.yx-navbar-phone').appendChild(span);
        }
    }
});
