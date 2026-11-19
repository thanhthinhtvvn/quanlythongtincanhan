(function () {
    var addressFields = document.querySelector('.address-fields');
    if (!addressFields) {
        return;
    }

    var provinceSelect = document.getElementById('profile-province');
    var wardSelect = document.getElementById('profile-ward');
    var status = addressFields.querySelector('.address-status');
    var savedProvince = addressFields.getAttribute('data-saved-province');
    var savedWard = addressFields.getAttribute('data-saved-ward');

    function addOption(select, value, label) {
        var option = document.createElement('option');
        option.value = value;
        option.textContent = label;
        select.appendChild(option);
    }

    function populateWards(province, selectedWard) {
        wardSelect.innerHTML = '';
        addOption(wardSelect, '', 'Chọn phường/xã');
        if (!province) {
            wardSelect.disabled = true;
            return;
        }

        province.wards.forEach(function (ward) {
            addOption(wardSelect, ward, ward);
        });
        wardSelect.disabled = false;
        wardSelect.value = selectedWard || '';
        if (status) {
            status.textContent = province.wards.length + ' phường/xã trong ' + province.name + '.';
        }
    }

    fetch(addressFields.getAttribute('data-locations-url'))
        .then(function (response) {
            if (!response.ok) {
                throw new Error('HTTP ' + response.status);
            }
            return response.json();
        })
        .then(function (provinces) {
            if (!Array.isArray(provinces) || provinces.length !== 34) {
                throw new Error('Danh sách tỉnh/thành phố không hợp lệ.');
            }

            provinces.forEach(function (province) {
                if (!province.name || !Array.isArray(province.wards) || province.wards.length === 0) {
                    throw new Error('Danh sách phường/xã không hợp lệ.');
                }
                addOption(provinceSelect, province.name, province.name);
            });

            var selectedProvince = provinces.find(function (province) {
                return province.name === savedProvince;
            });
            provinceSelect.value = selectedProvince ? selectedProvince.name : '';
            populateWards(selectedProvince, savedWard);

            provinceSelect.addEventListener('change', function () {
                var province = provinces.find(function (item) {
                    return item.name === provinceSelect.value;
                });
                populateWards(province, '');
            });

            if (!selectedProvince) {
                if (status) {
                    status.textContent = 'Chọn tỉnh/thành phố để tải danh sách phường/xã.';
                }
            }
        })
        .catch(function (error) {
            console.error('Không thể tải danh sách địa giới hành chính:', error);
            provinceSelect.disabled = true;
            wardSelect.disabled = true;
            if (status) {
                status.textContent = 'Không tải được danh sách tỉnh/thành phố. Vui lòng tải lại trang.';
            }
        });
})();
