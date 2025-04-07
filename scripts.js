// Функция для появления чат-бота через 3 секунды
window.onload = function() {
    setTimeout(function() {
        document.getElementById('chatbot').classList.add('show'); // Показать чат-бота
    }, 3000);  // 3000 миллисекунд = 3 секунды
};

// Функция для отправки сообщений
function sendMessage() {
    let message = document.getElementById('userMessage').value;
    if (message.trim() === "") return;

    let chatContent = document.querySelector('.chat-content');
    let userMessage = `<p><strong>Вы:</strong> ${message}</p>`;
    chatContent.innerHTML += userMessage;  // Показать сообщение пользователя
    document.getElementById('userMessage').value = "";  // Очистить поле ввода

    // Отправить сообщение на сервер
    fetch('chatbot.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'message=' + encodeURIComponent(message)
    })
    .then(response => response.json())
    .then(data => {
        let botResponse = `<p><strong>Бот:</strong> ${data.response}</p>`;
        chatContent.innerHTML += botResponse;  // Показать ответ бота
        chatContent.scrollTop = chatContent.scrollHeight;  // Прокрутка вниз
    })
    .catch(error => console.error('Ошибка:', error));
}

// Функция для переключения состояния чат-бота (открыть/закрыть)
function toggleChatbot() {
    const chatbot = document.getElementById('chatbot');
    const chatContent = chatbot.querySelector('.chat-content');
    
    // Переключаем класс для отображения/сокрытия содержимого чата
    if (chatContent.style.display === 'none') {
        chatContent.style.display = 'block'; // Показываем основное содержимое
    } else {
        chatContent.style.display = 'none'; // Скрываем основное содержимое
    }

    // Переключаем класс для отображения/скрытия чат-бота
    chatbot.classList.toggle('show');
}

document.addEventListener("DOMContentLoaded", function () {
    if (typeof ymaps !== "undefined") {
        ymaps.ready(initMap);
    }
});

function initMap() {
    var map = new ymaps.Map("map", {
        center: [55.751244, 37.618423], // Москва (по умолчанию)
        zoom: 5
    });

    var locations = [
        { coords: [55.756, 37.615], name: "Москва, ул. Тверская, 12" },
        { coords: [59.9343, 30.3351], name: "Санкт-Петербург, Невский проспект, 45" },
        { coords: [56.838, 60.597], name: "Екатеринбург, ул. Ленина, 103" },
        { coords: [55.0415, 82.9346], name: "Новосибирск, Красный проспект, 87" }
    ];

    locations.forEach(function (location) {
        var placemark = new ymaps.Placemark(location.coords, {
            balloonContent: location.name
        });
        map.geoObjects.add(placemark);
    });
}

// Обработка отправки формы через AJAX
$('#requestForm').on('submit', function(e) {
    e.preventDefault();  // Предотвращаем стандартное поведение формы

    var email = $('#email').val();  // Получаем значение из поля email

    // Проверка, что email не пустой
    if (!email) {
        Swal.fire(
            'Ошибка!',
            'Пожалуйста, введите email.',
            'error'
        );
        return;
    }

    // Отправка данных на сервер через AJAX
    $.ajax({
        url: 'send_request.php',  // Путь к вашему PHP скрипту
        type: 'POST',
        data: { email: email },  // Отправляем email
        success: function(response) {
            if (response.trim() === 'success') {
                // Показать сообщение об успешной отправке с помощью SweetAlert
                Swal.fire(
                    'Спасибо!',
                    'Мы свяжемся с вами в ближайшее время.',
                    'success'
                );
            } else {
                // Показать ошибку
                Swal.fire(
                    'Ошибка!',
                    'Ошибка при отправке заявки!',
                    'error'
                );
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX error: ' + status + ', ' + error);
            Swal.fire(
                'Ошибка!',
                'Произошла ошибка при отправке заявки!',
                'error'
            );
        }
    });
});
