document.addEventListener("DOMContentLoaded", function () {
    const slider = document.querySelector(".slider");
    const sliderDots = document.querySelectorAll(".slider-dot");
    let slideIndex = 0;

    function showSlide(index) {
        slider.style.transform = `translateX(-${index * 100}%)`;
    }

    function setActiveDot(index) {
        sliderDots.forEach((dot, i) => {
            if (i === index) {
                dot.classList.add("active");
            } else {
                dot.classList.remove("active");
            }
        });
    }

    function nextSlide() {
        slideIndex++;
        if (slideIndex >= sliderDots.length) {
            slideIndex = 0;
        }
        showSlide(slideIndex);
        setActiveDot(slideIndex);
    }

    sliderDots.forEach((dot, index) => {
        dot.addEventListener("click", () => {
            slideIndex = index;
            showSlide(slideIndex);
            setActiveDot(slideIndex);
        });
    });

    // Cambia automáticamente la imagen cada 3 segundos (3000 ms)
    setInterval(nextSlide, 9000);

    // Inicialmente, muestra la primera imagen y establece el primer punto como activo
    showSlide(slideIndex);
    setActiveDot(slideIndex);
});

