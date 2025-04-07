<?php
$servername = "localhost"; // Или ваш сервер базы данных
$username = "root"; // Или ваш пользователь БД
$password = ""; // Ваш пароль
$database = "smam"; // Имя базы данных

$conn = new mysqli($servername, $username, $password, $database);

// Проверка подключения
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}
?>
