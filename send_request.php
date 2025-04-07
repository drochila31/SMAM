<?php
// Подключение к базе данных
$servername = "localhost";
$username = "root"; // Замените на ваш логин
$password = ""; // Замените на ваш пароль
$dbname = "smam"; // Ваша база данных

// Создаем подключение
$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка соединения
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получаем email из формы
    $email = trim($_POST['email']);

    // Валидация email
    if (empty($email)) {
        echo 'error';
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo 'error';
        exit();
    }

    // Сохраняем email в таблице requests
    $query = "INSERT INTO requests (email) VALUES (?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);

    if ($stmt->execute()) {
        // Если email успешно сохранен, отправляем уведомление админу
        $to = "admin@example.com";  // Замените на ваш email администратора
        $subject = "Новый запрос на сайте";
        $message = "Пользователь оставил свой email: " . $email;
        $headers = "From: no-reply@example.com\r\n" .
                   "Content-Type: text/plain; charset=UTF-8\r\n";

        // Отправка уведомления администратору
        mail($to, $subject, $message, $headers);

        // Возвращаем ответ success для отображения pop-up
        echo 'success';
    } else {
        // Если произошла ошибка при сохранении
        echo 'error';
    }

    // Закрываем соединение
    $stmt->close();
    $conn->close();
}
?>
