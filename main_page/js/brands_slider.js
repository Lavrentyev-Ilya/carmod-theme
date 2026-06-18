document.addEventListener('DOMContentLoaded', () => {
  const track = document.querySelector('.CmBraTrack');
  const slider = document.querySelector('.CmBraSlider');
  
  // Получаем оригинальные бренды (первый набор, до дублирования)
  const originalItems = Array.from(track.querySelectorAll('.CmBraItem'));
  const brandCount = originalItems.length / 2; // Исходное количество уникальных брендов
  
  slider.setAttribute('data-count', brandCount);
  
  // Очищаем track и пересобираем с нужным количеством дубликатов
  track.innerHTML = '';
  
  // Определяем сколько раз нужно продублировать контент
  let duplicatesNeeded = 1;
  if (brandCount <= 8) {
    duplicatesNeeded = 4; // Для малого количества - много дубликатов
  } else if (brandCount <= 15) {
    duplicatesNeeded = 3; // Для среднего количества
  } else if (brandCount <= 25) {
    duplicatesNeeded = 2; // Для большого количества
  }
  // Для 26-30 оставляем 1 дубликат (итого 2 набора)
  
  // Заполняем track дубликатами
  for (let i = 0; i < duplicatesNeeded; i++) {
    originalItems.forEach(item => {
      track.appendChild(item.cloneNode(true));
    });
  }
  
  // Настройка скорости
  let baseSpeed = 0.5;
  if (brandCount <= 5) baseSpeed = 0.3;
  else if (brandCount <= 10) baseSpeed = 0.4;
  else if (brandCount <= 15) baseSpeed = 0.5;
  else if (brandCount <= 20) baseSpeed = 0.6;
  else baseSpeed = 0.7;
  
  let x = 0, paused = false;

  function animate() {
    if (!paused) {
      x -= baseSpeed;
      
      // Ширина одного набора оригинальных брендов
      const singleSetWidth = track.scrollWidth / duplicatesNeeded;
      
      // Плавный сброс, когда уехали на ширину одного набора
      if (Math.abs(x) >= singleSetWidth) {
        x += singleSetWidth;
      }
      
      track.style.transform = `translateX(${x}px)`;
    }
    requestAnimationFrame(animate);
  }

  animate();
});