document.addEventListener('DOMContentLoaded', function () {
    if (document.querySelector('.yx-navbar-phone-number')) {
        const phoneNumber = yx.phone;
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

    // 渲染省市区表单
    const modalForm = document.querySelector('#form');
    modalForm && render_province_city_district_selector('#form');

    // 在线咨询按钮
    UIkit.util.on('.yx-sidebar-online', 'click', function (e) {
        e.preventDefault();
        // 调起爱番番沟通窗口
        document.querySelector('.embed-icon-default').click()
    });
    
    // 联系我们按钮
    UIkit.util.on('.yx-message-container', 'click', function (e) {
        e.preventDefault();
        UIkit.modal('#modal-form').show();
        render_province_city_district_selector('#global-form')
    });
});

/**
 * 省市区三级联动
 * @param formId string 表单id
 */
const render_province_city_district_selector = (formId = '#form') => {
    const city = document.querySelector(`${formId} select[name="city"]`);
    const province = document.querySelector(`${formId} select[name="province"]`);
    const district = document.querySelector(`${formId} select[name="district"]`);

    // 检查必要元素是否存在
    if (!province || !city || !district) {
        console.warn(`Province, city, or district selector not found in ${formId}`);
        return;
    }

    // 获取区域数据
    function fetchRegion(pid = '') {
        let url = '/wp-json/wp/v2/province_city_district';
        if (pid) url += `?pid=${pid}`;
        return fetch(url)
            .then(res => res.json())
            .then(data => data.result || []);
    }

    // 渲染下拉选项
    function renderOptions(select, list, placeholder = '-- 请选择 --') {
        select.innerHTML = `<option value="">${placeholder}</option>`;
        list.forEach(item => {
            select.innerHTML += `<option value="${item.id}">${item.name}</option>`;
        });
    }


    // 初始化省份
    fetchRegion().then(list => {
        renderOptions(province, list, '-- 省份 --');
        city.innerHTML = '<option value="">-- 请选择 --</option>';
        district.innerHTML = '<option value="">-- 请选择 --</option>';
    });

    // 省份切换
    province.addEventListener('change', function () {
        const pid = this.value;
        if (!pid) {
            city.innerHTML = '<option value="">-- 请选择 --</option>';
            district.innerHTML = '<option value="">-- 请选择 --</option>';
            return;
        }
        fetchRegion(pid).then(list => {
            renderOptions(city, list, '-- 城市 --');
            district.innerHTML = '<option value="">-- 请选择 --</option>';
        });
    });

    // 城市切换
    city.addEventListener('change', function () {
        const pid = this.value;
        if (!pid) {
            district.innerHTML = '<option value="">-- 请选择 --</option>';
            return;
        }
        fetchRegion(pid).then(list => {
            renderOptions(district, list, '-- 区县 --');
        });
    });
};