document.addEventListener("DOMContentLoaded", function () {
  let currentIndex = 0;
  const next = document.getElementById('next');
  const prev = document.getElementById('prev');

  next.addEventListener('click', nextSlide);
  prev.addEventListener('click', prevSlide);

  function showSlide(index) {
    const imagenes = document.querySelectorAll('.carrusel-item');

    for (let i = 0; i < imagenes.length; i++) {
      imagenes[i].classList.remove('active');
    }

    currentIndex = index;

    imagenes[currentIndex].classList.add('active');
  }

  function prevSlide() {
    showSlide(
        (currentIndex > 0) ? currentIndex - 1 : document.querySelectorAll('.carrusel-item').length - 1
    );
  }

  function nextSlide() {
    const totalSlides = document.querySelectorAll('.carrusel-item').length;

    showSlide(
        (currentIndex < totalSlides - 1) ? currentIndex + 1 : 0
    );
  }

  showSlide(currentIndex);
});
