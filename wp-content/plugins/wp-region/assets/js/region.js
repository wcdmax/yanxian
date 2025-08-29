document.addEventListener('DOMContentLoaded', function() {
    const editModal = document.getElementById('edit-modal');
    const editLinks = document.querySelectorAll('.edit-link');

    // 父级自动补全（只显示名称，实际提交ID）
    const parentNameInput = document.getElementById('edit-parent_name');
    const parentIdInput = document.getElementById('edit-parent_id');
    const suggestList = document.getElementById('parent-suggest-list');
    const levelInput = document.getElementById('edit-level');
    const fullPathInput = document.getElementById('edit-full_path');
    const nameInput = document.getElementById('edit-name');
    let regionList = window.regionList || [];
    // 需要查父级的level和full_path
    let regionMap = {};
    if (window.regionList) {
        window.regionList.forEach(function(r) { regionMap[r.id] = r; });
    }

    // 获取完整父级信息（含level、full_path）
    function getRegionInfo(id) {
        if (!id) return null;
        // 需要扩展regionList为包含level和full_path
        if (regionMap[id] && regionMap[id].level !== undefined) return regionMap[id];
        // fallback: 只含id和name
        return regionList.find(r => r.id === id) || null;
    }

    // 父级选择后自动设置层级和全路径
    function updateLevelAndPath() {
        const parentId = parentIdInput.value;
        const parent = getRegionInfo(parentId);
        let level = 1;
        let full_path = '';
        if (parent && parent.level !== undefined) {
            level = parseInt(parent.level) + 1;
            full_path = parent.full_path ? (parent.full_path + '，' + nameInput.value) : nameInput.value;
        } else if (parent) {
            // 兼容只含id和name
            level = 2;
            full_path = parent.name + '，' + nameInput.value;
        } else {
            level = 1;
            full_path = nameInput.value;
        }
        if (levelInput) {
            levelInput.value = level;
            levelInput.defaultValue = level;
        }
        if (fullPathInput) {
            fullPathInput.value = full_path;
            fullPathInput.defaultValue = full_path;
        }
    }

    if (parentNameInput && suggestList && parentIdInput) {
        parentNameInput.addEventListener('input', function() {
            const val = this.value.trim();
            if (!val) {
                suggestList.style.display = 'none';
                parentIdInput.value = '';
                updateLevelAndPath();
                return;
            }
            // 匹配id或name
            const results = regionList.filter(r => r.id.indexOf(val) !== -1 || r.name.indexOf(val) !== -1).slice(0, 20);
            if (results.length === 0) {
                suggestList.style.display = 'none';
                return;
            }
            suggestList.innerHTML = results.map(r => `<li data-id="${r.id}" data-name="${r.name}">${r.id} - ${r.name}</li>`).join('');
            suggestList.style.display = 'block';
            // 绑定点击
            Array.from(suggestList.children).forEach(function(li) {
                li.onclick = function() {
                    parentNameInput.value = this.dataset.name;
                    parentIdInput.value = this.dataset.id;
                    parentNameInput.defaultValue = this.dataset.name;
                    parentIdInput.defaultValue = this.dataset.id;
                    suggestList.style.display = 'none';
                    updateLevelAndPath();
                };
            });
        });
        parentNameInput.addEventListener('blur', function() {
            setTimeout(() => { suggestList.style.display = 'none'; }, 200);
        });
        parentNameInput.addEventListener('change', function() {
            const found = regionList.find(r => r.id === this.value.trim());
            if (found) {
                parentNameInput.value = found.name;
                parentIdInput.value = found.id;
                parentNameInput.defaultValue = found.name;
                parentIdInput.defaultValue = found.id;
            }
            updateLevelAndPath();
        });
    }
    if (nameInput) {
        nameInput.addEventListener('input', updateLevelAndPath);
        nameInput.addEventListener('change', updateLevelAndPath);
    }

    function setInputValue(id, value) {
        const input = document.getElementById(id);
        if (input) {
            input.value = value;
            input.defaultValue = value;
        }
    }

    if (editLinks.length && editModal) {
        editLinks.forEach(function(link) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                setInputValue('edit-id', this.dataset.id);
                const idText = document.getElementById('edit-id-text');
                if (idText) idText.textContent = this.dataset.id;
                setInputValue('edit-name', this.dataset.name);
                // 父级：显示名称，隐藏input存ID
                if (parentNameInput && parentIdInput) {
                    const found = regionList.find(r => r.id === this.dataset.parent_id);
                    setInputValue('edit-parent_name', found ? found.name : '');
                    setInputValue('edit-parent_id', this.dataset.parent_id);
                }
                // 需要查父级level和full_path
                if (levelInput && fullPathInput) {
                    let parent = getRegionInfo(this.dataset.parent_id);
                    let level = 1;
                    let full_path = '';
                    if (parent && parent.level !== undefined) {
                        level = parseInt(parent.level) + 1;
                        full_path = parent.full_path ? (parent.full_path + '，' + this.dataset.name) : this.dataset.name;
                    } else if (parent) {
                        level = 2;
                        full_path = parent.name + '，' + this.dataset.name;
                    } else {
                        level = 1;
                        full_path = this.dataset.name;
                    }
                    setInputValue('edit-level', level);
                    setInputValue('edit-full_path', full_path);
                }
                // 设置action和标题
                document.getElementById('edit-action').value = 'edit';
                document.getElementById('modal-title').textContent = '编辑区域';
                editModal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
            });
        });
    }
    // 新增按钮逻辑
    const addBtn = document.getElementById('add-region-btn');
    if (addBtn && editModal) {
        addBtn.addEventListener('click', function(e) {
            e.preventDefault();
            // 清空所有字段
            setInputValue('edit-id', '');
            setInputValue('edit-name', '');
            setInputValue('edit-parent_name', '');
            setInputValue('edit-parent_id', '');
            setInputValue('edit-level', 1);
            setInputValue('edit-full_path', '');
            // 设置action和标题
            document.getElementById('edit-action').value = 'add';
            document.getElementById('modal-title').textContent = '新增区域';
            editModal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        });
    }
    if (editModal) {
        const closeBtn = editModal.querySelector('.edit-modal-close');
        if (closeBtn) {
            closeBtn.onclick = function(e) {
                e.preventDefault();
                editModal.style.display = 'none';
                document.body.style.overflow = '';
            };
        }
        editModal.onclick = function(e) {
            if (e.target === this) {
                this.style.display = 'none';
                document.body.style.overflow = '';
            }
        };
    } else {
        document.body.style.overflow = '';
    }

    // 让form外的保存按钮也能提交表单
    const modalFooter = document.querySelector('.edit-modal-footer');
    if (modalFooter && editModal) {
        const form = editModal.querySelector('form');
        const saveBtn = modalFooter.querySelector('input[type="submit"]');
        if (form && saveBtn) {
            saveBtn.addEventListener('click', function(e) {
                e.preventDefault();
                form.requestSubmit ? form.requestSubmit() : form.submit();
            });
        }
        const resetBtn = modalFooter.querySelector('input[type="reset"]');
        if (form && resetBtn) {
            resetBtn.addEventListener('click', function(e) {
                e.preventDefault();
                form.reset();
            });
        }
    }

    // 判断最后一级
    function isLastLevel(region) {
        return parseInt(region.level) === 3;
    }
    // 创建表格区域行
    function createRegionRow(region, level = 1, ancestorIds = []) {
        const tr = document.createElement('tr');
        tr.className = 'region-row';
        // 名称
        const nameTd = document.createElement('td');
        nameTd.textContent = '';
        nameTd.style.whiteSpace = 'nowrap';
        nameTd.style.paddingLeft = (10 + (parseInt(region.level, 10) - 1) * 32) + 'px';
        nameTd.style.fontWeight = parseInt(region.level, 10) === 1 ? 'bold' : 'normal';
        // 展开/收起符号
        let toggleSpan = null;
        const regionLevel = parseInt(region.level, 10);
        if (regionLevel === 1 || regionLevel === 2) {
            toggleSpan = document.createElement('span');
            toggleSpan.textContent = '+';
            toggleSpan.style.display = 'inline-block';
            toggleSpan.style.width = '18px';
            toggleSpan.style.cursor = 'pointer';
            toggleSpan.style.marginRight = '4px';
            nameTd.appendChild(toggleSpan);
        }
        // 名称文本
        const nameText = document.createElement('span');
        nameText.textContent = region.name;
        nameTd.appendChild(nameText);
        tr.appendChild(nameTd);
        // ID
        const idTd = document.createElement('td');
        idTd.textContent = region.id;
        tr.appendChild(idTd);
        // 父级
        const parentTd = document.createElement('td');
        parentTd.textContent = region.parent_id === null ? '' : region.parent_id;
        tr.appendChild(parentTd);
        // 层级
        const levelTd = document.createElement('td');
        levelTd.textContent = region.level;
        tr.appendChild(levelTd);
        // 全路径
        const pathTd = document.createElement('td');
        pathTd.textContent = region.full_path || '';
        tr.appendChild(pathTd);
        // 操作
        const opTd = document.createElement('td');
        // 编辑按钮
        const editBtn = document.createElement('button');
        editBtn.textContent = '编辑';
        editBtn.className = 'button button-small edit-link';
        editBtn.onclick = function(e) {
            e.stopPropagation();
            setInputValue('edit-id', region.id);
            // 自动填充父级名称
            let parentName = '';
            if (region.parent_id) {
                const allRows = document.querySelectorAll('tr.region-row');
                allRows.forEach(row => {
                    const idCell = row.children[1];
                    if (idCell && idCell.textContent === String(region.parent_id)) {
                        const nameSpan = row.children[0].querySelector('span:last-child');
                        if (nameSpan) {
                            parentName = nameSpan.textContent.trim();
                        }
                    }
                });
                // 如果表格中找不到父级，再从 regionList 查找
                if (!parentName && window.regionList) {
                    const parentRegion = window.regionList.find(r => r.id === region.parent_id);
                    if (parentRegion) {
                        parentName = parentRegion.name;
                    }
                }
            }
            setInputValue('edit-name', region.name);
            setInputValue('edit-level', region.level);
            setInputValue('edit-parent_name', parentName);
            setInputValue('edit-parent_id', region.parent_id);
            setInputValue('edit-full_path', region.full_path);
            document.getElementById('edit-action').value = 'edit';
            document.getElementById('modal-title').textContent = '编辑区域';
            editModal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        };
        opTd.appendChild(editBtn);
        // 删除按钮
        const delBtn = document.createElement('a');
        delBtn.textContent = '删除';
        delBtn.className = 'button button-small button-danger';
        delBtn.href = '?page=wp-region&delete=' + encodeURIComponent(region.id);
        delBtn.onclick = function(e) {
            if (!confirm('确定删除？')) e.preventDefault();
            e.stopPropagation();
        };
        opTd.appendChild(delBtn);
        tr.appendChild(opTd);
        // 标记所有祖先
        ancestorIds.forEach(id => {
            if (id) tr.classList.add('region-child-' + id);
        });
        if (region.parent_id) {
            tr.classList.add('region-child-' + region.parent_id);
        }
        // 整行点击加载下一级（最后一级不再加载）
        tr.onclick = function(e) {
            // 如果点击的是操作按钮，不处理
            if (e.target === editBtn || e.target === delBtn) return;
            if (isLastLevel(region)) return;
            if (tr.classList.contains('expanded')) {
                // 递归收起所有后代
                let next = tr.nextSibling;
                while (next) {
                    if (next.classList && next.className.indexOf('region-child-' + region.id) !== -1) {
                        next.style.display = 'none';
                        next.classList.remove('expanded');
                        // 同步符号为+
                        const toggle = next.querySelector('span');
                        if (toggle && toggle.textContent === '-') toggle.textContent = '+';
                        next = next.nextSibling;
                    } else {
                        break;
                    }
                }
                if (toggleSpan) toggleSpan.textContent = '+';
                tr.classList.remove('expanded');
                return;
            }
            fetchRegions(region.id, tr, parseInt(region.level, 10) + 1, [...(ancestorIds || []), region.id]);
            if (toggleSpan) toggleSpan.textContent = '-';
            tr.classList.add('expanded');
        };
        return tr;
    }
    function fetchRegions(parent_id, parentTr, level = 0, ancestorIds = [], search_kw = '') {
        const data = new FormData();
        data.append('parent_id', parent_id);
        data.append('action', 'wp_region_get_children');
        if (search_kw) data.append('search_kw', search_kw);
        fetch((window.wpRegionAjax && window.wpRegionAjax.ajaxurl) ? window.wpRegionAjax.ajaxurl : ajaxurl, {
            method: 'POST',
            credentials: 'same-origin',
            body: data
        }).then(res => res.json()).then(list => {
            if (!list.length) return;
            const table = document.querySelector('#region-tree-table tbody');
            let insertAfter = parentTr;
            list.forEach(region => {
                const tr = createRegionRow(region, level, ancestorIds);
                if (insertAfter && insertAfter.nextSibling) {
                    table.insertBefore(tr, insertAfter.nextSibling);
                } else {
                    table.appendChild(tr);
                }
                insertAfter = tr;
            });
            if (parentTr) parentTr.classList.add('expanded');
        });
    }
    // 监听搜索表单
    const searchForm = document.getElementById('search-form');
    if (searchForm) {
        searchForm.onsubmit = function(e) {
            e.preventDefault();
            const kw = this.search_kw.value.trim();
            // 清空树
            document.querySelector('#region-tree-table tbody').innerHTML = '';
            // AJAX加载
            fetchRegions('', null, 1, [], kw);
        };
    }
    // 页面加载时渲染表头和加载省份
    const regionTreeDiv = document.getElementById('region-tree');
    regionTreeDiv.innerHTML = '<table class="fixed widefat striped wp-list-table region-tree-table" id="region-tree-table"><thead><tr><th>名称</th><th>ID</th><th>父级</th><th>层级</th><th>全路径</th><th>操作</th></tr></thead><tbody></tbody></table>';
    // 初始化加载省份
    fetchRegions('', null, 0, []);
}); 