document.addEventListener("DOMContentLoaded", () => {
  const slider = document.querySelector(".CmSlider");
  const SlideCount = slider.querySelectorAll(".CmBanner").length;
  const dotsContainer = document.querySelector(".CmMSdDots");
  const prevBtn = document.querySelector(".prev-btn");
  const nextBtn = document.querySelector(".next-btn");
  
  var Freeze = slider.getAttribute('data-autoplay'); // Интервал в секундах
  Freeze = Freeze * 1000; // Переводим в миллисекунды

  let banners = document.querySelectorAll(".CmBanner");
  const actualSlides = banners.length;

  // Клонируем первый слайд и добавляем его в конец для бесшовного перехода
  const firstSlideClone = banners[0].cloneNode(true);
  slider.appendChild(firstSlideClone);
  const totalSlides = actualSlides + 1;

  banners = document.querySelectorAll(".CmBanner");

  let currentIndex = 0;
  let interval;

  // Добавляем точки для навигации
  for (let i = 0; i < actualSlides; i++) {
    const dot = document.createElement("div");
    dot.classList.add("dot");
    if (i === 0) dot.classList.add("active");
    dot.addEventListener("click", () => goToSlide(i));
    dotsContainer.appendChild(dot);
  }
  const dots = document.querySelectorAll(".dot");

  // Обновляем позицию слайдера
  function updateSliderPosition(transition = true) {
    slider.style.transition = transition ? "transform 0.3s ease-out" : "none";
    let sliderWidth = slider.getBoundingClientRect().width;
    slider.style.transform = `translateX(${-currentIndex * sliderWidth}px)`;
  }

  // Обновляем активную точку
  function updateDots() {
    dots.forEach((dot, index) => {
      dot.classList.toggle("active", index === (currentIndex === actualSlides ? 0 : currentIndex));
    });
  }

  // Переход к конкретному слайду
  function goToSlide(index) {
    currentIndex = index;
    updateSliderPosition();
    updateDots();
    resetAutoSlide();
  }

  // Следующий слайд
  function nextSlide() {
    if (currentIndex < totalSlides - 1) {
      currentIndex++;
      updateSliderPosition();
      updateDots();
    }
    resetAutoSlide();
  }

  // Предыдущий слайд
  function prevSlide() {
    if (currentIndex === 0) {
      currentIndex = actualSlides - 1;
      updateSliderPosition(false);
      setTimeout(() => {
        updateSliderPosition();
        updateDots();
      }, 20);
    } else {
      currentIndex--;
      updateSliderPosition();
      updateDots();
    }
    resetAutoSlide();
  }

  // Автоматическое перелистывание
  function autoSlide() {
	  if(SlideCount > 1){
		nextSlide();
	  }
  }

  // Сброс интервала автоматического перелистывания
  function resetAutoSlide() {
    clearInterval(interval);
    interval = setInterval(autoSlide, Freeze);
  }

  // Инициализация автоперелистывания
  resetAutoSlide();

  // Обработчики для кнопок "Назад" и "Вперед"
  if (prevBtn && nextBtn) {
    prevBtn.addEventListener("click", prevSlide);
    nextBtn.addEventListener("click", nextSlide);
  }

  // Обработчик изменения размера окна
  window.addEventListener("resize", updateSliderPosition);

  // Обработчик завершения анимации перехода
  slider.addEventListener("transitionend", () => {
    if (currentIndex === actualSlides) {
      currentIndex = 0;
      updateSliderPosition(false);
      updateDots();
    }
  });

  // Логика для перелистывания пальцем (тач-события)
  let isDragging = false;
  let startPosX = 0;
  let currentTranslate = 0;

  function dragStart(e) {
    isDragging = true;
    startPosX = e.type.includes("mouse") ? e.clientX : e.touches[0].clientX;
    slider.style.transition = "none";
  }

  function dragMove(e) {
    if (!isDragging) return;
    const currentPosX = e.type.includes("mouse") ? e.clientX : e.touches[0].clientX;
    const diff = currentPosX - startPosX;
    const sliderWidth = slider.offsetWidth;
    currentTranslate = -currentIndex * sliderWidth + diff;
    slider.style.transform = `translateX(${currentTranslate}px)`;
  }

  function dragEnd(e) {
    if (!isDragging) return;
    isDragging = false;
    const endPosX = e.type.includes("mouse")
      ? e.clientX
      : (e.changedTouches ? e.changedTouches[0].clientX : 0);
    const diff = endPosX - startPosX;
    const sliderWidth = slider.offsetWidth;

    if (Math.abs(diff) > sliderWidth / 4) { // Порог для перелистывания
      if (diff < 0 && currentIndex < totalSlides - 1) {
        nextSlide();
      } else if (diff > 0 && currentIndex > 0) {
        prevSlide();
      } else {
        updateSliderPosition();
      }
    } else {
      updateSliderPosition();
    }
  }

  // Добавляем обработчики для мыши
  slider.addEventListener("mousedown", dragStart);
  slider.addEventListener("mousemove", dragMove);
  slider.addEventListener("mouseup", dragEnd);
  slider.addEventListener("mouseleave", () => {
    if (isDragging) dragEnd({ type: "mouse", clientX: startPosX });
  });

  // Добавляем обработчики для тач-устройств
  slider.addEventListener("touchstart", dragStart, { passive: false });
  slider.addEventListener("touchmove", dragMove, { passive: false });
  slider.addEventListener("touchend", dragEnd);
  slider.addEventListener("touchcancel", dragEnd);
}); 