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

    // ── Slider Logique (Détails Propriété) ──
    const slider = document.getElementById('slider');
    const nextButton = document.getElementById('next');
    const prevButton = document.getElementById('prev');

    if (slider && nextButton && prevButton) {
        let currentSlide = 0;
        const totalSlides = slider.children.length;

        function goToSlide(index) {
            const slideWidth = slider.children[0].clientWidth;
            slider.style.transform = `translateX(-${index * slideWidth}px)`;
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % totalSlides;
            goToSlide(currentSlide);
        }

        function prevSlide() {
            currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
            goToSlide(currentSlide);
        }

        let slideInterval = setInterval(nextSlide, 3000);

        function resetAutoSlide() {
            clearInterval(slideInterval);
            slideInterval = setInterval(nextSlide, 3000);
        }

        nextButton.addEventListener('click', () => {
            nextSlide();
            resetAutoSlide();
        });

        prevButton.addEventListener('click', () => {
            prevSlide();
            resetAutoSlide();
        });

        window.addEventListener('resize', () => {
            goToSlide(currentSlide);
        });

        // Initialisation
        goToSlide(currentSlide);
    }
});
