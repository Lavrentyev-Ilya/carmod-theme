document.addEventListener("DOMContentLoaded", function () {
    // Открытие модального окна
    document.getElementById('showButton').addEventListener('click', function(event) {
        event.preventDefault();
        document.getElementById('CmPopup').classList.add('CmShow');
        document.body.style.overflow = 'hidden';
    });

    // Закрытие модального окна по кнопке "Отмена"
    document.getElementById('CmPopupCancBnt').addEventListener('click', function() {
        document.getElementById('CmPopup').classList.remove('CmShow');
        document.body.style.overflow = '';
    });

    // Закрытие модального окна по клику вне его области
    window.addEventListener('click', function(event) {
        const CmPopup = document.getElementById('CmPopup');
        if (event.target === CmPopup) {
            CmPopup.classList.remove('CmShow');
            document.body.style.overflow = '';
        }
    });

    // Исправление логики для кнопки поиска
    document.querySelector(".CmPopupBtn").addEventListener("click", function () {
        const iconContainer = document.querySelector(".CmSrchIcConCm");
        const inputField = document.querySelector(".CmSrchInp");

        inputField.blur(); // Сбрасываем фокус

        //iconContainer.classList.remove("reset-icon"); 
        setTimeout(() => {
            //iconContainer.classList.add("reset-icon");
        }, 10);
    });
});