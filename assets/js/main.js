// ================= Bộ đếm số lượng (nút + / -) =================
document.querySelectorAll('.qty-box').forEach(function (box) {
    var input = box.querySelector('input');
    var minus = box.querySelector('.qty-minus');
    var plus = box.querySelector('.qty-plus');
    var max = parseInt(input.getAttribute('max') || '999', 10);

    if (minus) {
        minus.addEventListener('click', function () {
            var val = parseInt(input.value, 10) || 1;
            if (val > 1) input.value = val - 1;
        });
    }
    if (plus) {
        plus.addEventListener('click', function () {
            var val = parseInt(input.value, 10) || 1;
            if (val < max) input.value = val + 1;
        });
    }
});

// ================= Xác nhận trước khi xóa =================
document.querySelectorAll('.confirm-delete').forEach(function (el) {
    el.addEventListener('click', function (e) {
        if (!confirm('Bạn có chắc muốn xóa mục này không?')) {
            e.preventDefault();
        }
    });
});

// ================= Tự ẩn thông báo alert sau vài giây =================
document.querySelectorAll('.alert').forEach(function (el) {
    setTimeout(function () {
        el.style.transition = 'opacity .4s';
        el.style.opacity = '0';
        setTimeout(function () { el.remove(); }, 400);
    }, 4000);
});

// ================= Tự submit form khi đổi số lượng trong giỏ hàng =================
document.querySelectorAll('.cart-qty-input').forEach(function (input) {
    input.addEventListener('change', function () {
        input.form.submit();
    });
});
