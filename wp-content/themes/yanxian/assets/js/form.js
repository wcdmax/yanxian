document.querySelectorAll('form:not(#searchform)').forEach(form => {
    // 移除验证样式和错误信息的函数
    const removeValidationStyle = (input) => {
        input.classList.remove('uk-form-danger');
        const errorSpan = input.parentElement.querySelector('.yx-form-error');
        if (errorSpan) {
            errorSpan.textContent = '';
            errorSpan.classList.remove('show');
        }
    };

    // 显示错误信息的函数
    const showError = (input, message) => {
        input.classList.add('uk-form-danger');
        const errorSpan = input.parentElement.querySelector('.yx-form-error');
        if (errorSpan) {
            errorSpan.textContent = message;
            errorSpan.classList.add('show');
        }
    };

    // 验证单个输入项
    const validateInput = (input) => {
        if (input.id === 'name' && !input.value.trim()) {
            showError(input, '请输入姓名');
            return false;
        }
        
        if (input.id === 'phone') {
            if (!input.value.trim()) {
                showError(input, '请输入手机号码');
                return false;
            } else if (!/^1[3-9]\d{9}$/.test(input.value.trim())) {
                showError(input, '请输入正确的手机号码');
                return false;
            }
        }
        
        if (input.name === 'province' && !input.value) {
            showError(input, '请选择所在省份');
            return false;
        }
        
        if (input.name === 'city' && !input.value) {
            showError(input, '请选择所在城市');
            return false;
        }
        
        if (input.name === 'district' && !input.value) {
            showError(input, '请选择所在辖区');
            return false;
        }
        
        if (input.id === 'message' && !input.value.trim()) {
            showError(input, '请输入留言信息');
            return false;
        }
        
        return true;
    };

    // 添加输入事件监听
    form.querySelectorAll('input, select, textarea').forEach(input => {
        // 输入时移除错误提示
        input.addEventListener('input', () => removeValidationStyle(input));
        input.addEventListener('change', () => removeValidationStyle(input));
        
        // 失去焦点时验证
        input.addEventListener('blur', () => validateInput(input));
    });

    // 清除所有验证样式和错误信息的函数
    const clearAllValidation = (form) => {
        form.querySelectorAll('.uk-form-danger, .yx-form-error.show').forEach(el => {
            if (el.classList.contains('uk-form-danger')) {
                el.classList.remove('uk-form-danger');
            }
            if (el.classList.contains('yx-form-error')) {
                el.textContent = '';
                el.classList.remove('show');
            }
        });
    };

    // 监听重置按钮点击事件
    form.addEventListener('reset', () => clearAllValidation(form));

    // 表单提交事件
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formId = form.getAttribute('id');

        // 获取表单元素
        const nameInput = document.querySelector(`#${formId} #name`);
        const phoneInput = document.querySelector(`#${formId} #phone`);
        const messageInput = document.querySelector(`#${formId} #message`);
        const provinceSelect = document.querySelector(`#${formId} select[name="province"]`);
        const citySelect = document.querySelector(`#${formId} select[name="city"]`);
        const districtSelect = document.querySelector(`#${formId} select[name="district"]`);

        // 清除所有验证样式
        form.querySelectorAll('.uk-form-danger').forEach(el => removeValidationStyle(el));

        // 批量验证所有输入项
        const inputs = [nameInput, phoneInput, provinceSelect, citySelect, districtSelect, messageInput];
        const validationResults = inputs.map(input => ({
            input,
            isValid: validateInput(input)
        }));

        // 如果有任何一项验证失败，则不提交表单
        if (validationResults.some(result => !result.isValid)) {
            return;
        }

        try {
            const response = await fetch(yx.ajaxurl, {
                method: 'POST',
                body: new URLSearchParams({
                    nonce: yx.nonce,
                    city: citySelect.value,
                    path: window.location.href,
                    name: nameInput.value.trim(),
                    action: 'handle_contact_form',
                    phone: phoneInput.value.trim(),
                    province: provinceSelect.value,
                    district: districtSelect.value,
                    message: messageInput.value.trim(),
                }),
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                }
            });

            const data = await response.json();

            if (data.success) {
                UIkit.notification({ message: data.data.message, status: 'success' });
                form.reset();
            } else {
                UIkit.notification({ message: data.data.message, status: 'danger' });
            }

        } catch (error) {
            console.error('Error:', error);
            UIkit.notification({ message: '提交失败，请稍后重试', status: 'danger' });
        }
    });
});
