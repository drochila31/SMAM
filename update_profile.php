<?php
include('db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    // Если пользователь не авторизован, перенаправляем его на страницу входа
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];  // Получаем ID пользователя из сессии

// Получаем данные из формы
$name = trim($_POST['name']);
$email = trim($_POST['email']);
$password = trim($_POST['password']);  // Новый пароль, если он введен

// Валидация введенных данных
if (empty($name) || empty($email)) {
    echo "Имя и email не могут быть пустыми!";
    exit();
}

// Проверяем, существует ли уже пользователь с таким email
$query = "SELECT * FROM users WHERE email = ? AND id != ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("si", $email, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo "Пользователь с таким email уже существует!";
    exit();
}

// Если пароль был введен, хэшируем его
if (!empty($password)) {
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
} else {
    // Если пароль не был введен, оставляем старый (не меняем его)
    $hashed_password = null;
}

// Обновляем данные пользователя в базе данных
if ($hashed_password) {
    // Если пароль был изменен, обновляем его
    $query = "UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sssi", $name, $email, $hashed_password, $user_id);
} else {
    // Если пароль не был изменен, обновляем только имя и email
    $query = "UPDATE users SET name = ?, email = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("ssi", $name, $email, $user_id);
}

if ($stmt->execute()) {
    // Если обновление прошло успешно
    echo "Профиль успешно обновлен!";
    header("Location: profile.php"); // Перенаправляем пользователя на страницу профиля
} else {
    // Если произошла ошибка
    echo "Ошибка при обновлении профиля!";
}
?>
