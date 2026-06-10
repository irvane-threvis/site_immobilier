document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.role-option input[type="radio"]').forEach(function (radio) {
        radio.addEventListener('change', function () {
            document.querySelectorAll('.role-option').forEach(function (opt) {
                opt.classList.remove('selected');
            });
            if (radio.checked && radio.closest('.role-option')) {
                radio.closest('.role-option').classList.add('selected');
            }
        });
    });

    document.querySelectorAll('.nav-drop-btn').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            var parent = btn.closest('.nav-dropdown');
            document.querySelectorAll('.nav-dropdown.open').forEach(function (d) {
                if (d !== parent) d.classList.remove('open');
            });
            parent.classList.toggle('open');
        });
    });

    document.addEventListener('click', function () {
        document.querySelectorAll('.nav-dropdown.open').forEach(function (d) {
            d.classList.remove('open');
        });
    });

    const toast = document.getElementById('flash-toast');
    if (toast) {
        setTimeout(function () {
            toast.remove();
        }, 4000);
    }

    const gallery = document.getElementById('property-gallery');
    const lightbox = document.getElementById('lightbox');
    const lightboxImg = document.getElementById('lightbox-img');
    const closeBtn = document.querySelector('.lightbox-close');

    if (gallery && lightbox && lightboxImg) {
        gallery.querySelectorAll('.gallery-thumb').forEach(function (thumb) {
            thumb.addEventListener('click', function () {
                lightboxImg.src = this.src;
                lightbox.classList.remove('hidden');
            });
        });

        if (closeBtn) {
            closeBtn.addEventListener('click', function () {
                lightbox.classList.add('hidden');
            });
        }

        lightbox.addEventListener('click', function (e) {
            if (e.target === lightbox) {
                lightbox.classList.add('hidden');
            }
        });
    }
});
