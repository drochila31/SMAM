<?php
include('db.php');
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Получаем данные из формы
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Проверяем, существует ли уже пользователь с таким email
    $check_sql = "SELECT id FROM users WHERE email = ?";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $error_message = "Ошибка: Пользователь с таким email уже существует. <a href='login.php'>Авторизуйтесь</a>";
    } else {
        // Хешируем пароль для безопасности
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Запрос на добавление пользователя в базу данных
        $insert_sql = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')";
        $stmt = $conn->prepare($insert_sql);
        $stmt->bind_param("sss", $name, $email, $hashed_password);
        
        if ($stmt->execute()) {
            // Перенаправляем пользователя на страницу профиля
            header("Location: profile.php");
            exit(); // Обязательно вызываем exit после header для завершения выполнения скрипта
        } else {
            echo "Ошибка при регистрации: " . $conn->error;
        }
    }
    
    $stmt->close();
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Регистрация - SMAM</title>
    <link rel="stylesheet" href="auth.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<header>
    <!-- Логотип как ссылка на главную страницу -->
    <div class="logo-container">
        <a href="index.php">
            <img src="logo.png" alt="Логотип" class="logo">
        </a>
    </div>
    
    <nav>
        <ul>
            <!-- Ссылки, которые перенаправляют на главную страницу с якорем -->
            <li><a href="index.php#new-collection">Новая коллекция</a></li>
            <li><a href="index.php#about-us">О компании</a></li>
            <li><a href="index.php#catalog">Каталог</a></li>
            <li><a href="index.php#stores">Наши магазины</a></li>
            <li><a href="index.php#testimonials">Отзывы</a></li>

            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="profile.php">Личный кабинет</a></li>
                <li><a href="logout.php">Выход</a></li>
            <?php else: ?>
                <li><a href="register.php">Регистрация/Авторизация</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>


    <div class="auth-container">
        <h2>Регистрация</h2>
        <form method="POST">
            <label for="name">Имя</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>

            <label for="password">Пароль</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Зарегистрироваться</button>
        </form>
        <?php if (!empty($error_message)): ?>
            <p class="error-message" style="color: red; margin-top: 10px;"> <?php echo $error_message; ?> </p>
        <?php endif; ?>

        <div class="social-login">
            <p>Или зарегистрируйтесь через соц. сети:</p>
            <div class="social-icon">
                <a href="https://t.me/yourcompany" target="_blank"><i class="fab fa-telegram"></i></a>
                <a href="https://www.pinterest.com/yourcompany" target="_blank"><i class="fab fa-pinterest"></i></a>
                <a href="https://vk.com/yourcompany" target="_blank"><i class="fab fa-vk"></i></a>
            </div>
        </div>

        <p>Уже есть аккаунт? <a href="login.php">Войти</a></p>
    </div>

    <footer>
    <div class="footer-container">
        <div class="footer-left">
            <h1 class="footer-logo">
                <img src="logo1.png" alt="SMAM">
            </h1>
            <nav>
                <ul class="footer-links">
                    <li><a href="#new-collection">Новая коллекция</a></li>
                    <li><a href="#about-us">О компании</a></li>
                    <li><a href="#catalog">Каталог</a></li>
                    <li><a href="#stores">Наши магазины</a></li>
                    <li><a href="#testimonials">Отзывы</a></li>
                </ul>
            </nav>
        </div>

        <div class="footer-center">
            <p class="subscribe-text">Подписывайтесь на нас в социальных сетях!</p>
            <div class="social-icons">
                <a href="https://t.me/yourcompany" target="_blank"><i class="fab fa-telegram"></i></a>
                <a href="https://www.pinterest.com/yourcompany" target="_blank"><i class="fab fa-pinterest"></i></a>
                <a href="https://vk.com/yourcompany" target="_blank"><i class="fab fa-vk"></i></a>
            </div>
        </div>

        <div class="footer-right">
    <h2 class="form-title">ОСТАВИТЬ ЗАЯВКУ</h2>
    <form id="requestForm" class="footer-form">
        <div class="input-container">
            <input type="email" id="email" name="email" placeholder="Ваш email" required>
            <button type="submit"><i class="fas fa-arrow-right"></i></button>
        </div>
        <p class="privacy-text">
            Нажимая кнопку отправки, вы соглашаетесь на обработку персональных данных.
            Менеджер свяжется с вами в ближайшее время.
        </p>
    </form>
    <p class="thank-you-text">СПАСИБО!</p>
</div>

<!-- Подключение библиотеки SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Подключение jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    </div>
</footer>
<script src="scripts.js"></script>
</body>
</html>
