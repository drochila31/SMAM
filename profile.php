<?php
include('db.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Запрос данных пользователя
$query = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$role = $user['role'];

// Функция генерации случайного пароля
function generatePassword($length = 10) {
    return substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"), 0, $length);
}

// Отправка письма с паролем
function sendEmail($email, $password) {
    $subject = "Ваш временный пароль";
    $message = "Ваш временный пароль для входа: $password\nПожалуйста, смените его после входа.";
    $headers = "From: no-reply@yourcompany.com";
    mail($email, $subject, $message, $headers);
}

// Обработка обновления профиля
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получаем данные из формы
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $current_section = $_POST['current_section'];

    // Обновление данных пользователя
    $update_query = "UPDATE users SET name = ?, email = ? WHERE id = ?";
    $stmt = $conn->prepare($update_query);
    $stmt->bind_param("ssi", $name, $email, $user_id);
    
    if (!empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
        $update_query = "UPDATE users SET name = ?, email = ?, password = ? WHERE id = ?";
        $stmt = $conn->prepare($update_query);
        $stmt->bind_param("sssi", $name, $email, $hashed_password, $user_id);
    }
    
    $stmt->execute();

    // После обновления перенаправляем обратно в ту же секцию
    header("Location: profile.php#$current_section");
    exit();
}

// Добавление нового пользователя
if (isset($_POST['add_user'])) {
    $new_name = $_POST['name'];
    $new_email = $_POST['email'];
    $temp_password = generatePassword();
    $hashed_password = password_hash($temp_password, PASSWORD_BCRYPT);

    $insert_query = "INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')";
    $stmt = $conn->prepare($insert_query);
    $stmt->bind_param("sss", $new_name, $new_email, $hashed_password);
    $stmt->execute();

    sendEmail($new_email, $temp_password);

    // Устанавливаем сообщение об успешном добавлении пользователя
    $_SESSION['add_user_message'] = "Пользователь добавлен. Временный пароль отправлен на email.";

    // Не перенаправляем, а обновляем страницу для отображения результата
    header("Location: profile.php#admin");
    exit();
}

// Удаление пользователя
if (isset($_GET['delete_user'])) {
    $delete_id = $_GET['delete_user'];
    $delete_query = "DELETE FROM users WHERE id = ?";
    $stmt = $conn->prepare($delete_query);
    $stmt->bind_param("i", $delete_id);
    $stmt->execute();
    header("Location: profile.php");
    exit();
}

// Блокировка пользователя
if (isset($_GET['block_user'])) {
    $block_id = $_GET['block_user'];
    $block_query = "UPDATE users SET status = 'blocked' WHERE id = ?";
    $stmt = $conn->prepare($block_query);
    $stmt->bind_param("i", $block_id);
    $stmt->execute();
    header("Location: profile.php");
    exit();
}

// Разблокировка пользователя
if (isset($_GET['unblock_user'])) {
    $unblock_id = $_GET['unblock_user'];
    $unblock_query = "UPDATE users SET status = 'active' WHERE id = ?";
    $stmt = $conn->prepare($unblock_query);
    $stmt->bind_param("i", $unblock_id);
    $stmt->execute();
    header("Location: profile.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Личный кабинет - SMAM</title>
    <link rel="stylesheet" href="prof.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>

<header>
    <div class="logo-container">
        <a href="index.php">
            <img src="logo.png" alt="Логотип" class="logo">
        </a>
    </div>
    
    <nav>
        <ul>
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

<main class="profile-container">
    <h2>Личный кабинет</h2>
    <p>Добро пожаловать, <?php echo htmlspecialchars($user['name']); ?>!</p>

    <div class="profile-menu">
        <button onclick="showSection('orders')">История заказов</button>
        <button onclick="showSection('favorites')">Избранное</button>
        <button onclick="showSection('settings')">Настройки</button>

        <?php if ($role === 'admin'): ?>
            <button onclick="showSection('admin')">Панель администратора</button>
        <?php endif; ?>
    </div>

    <!-- Секции для обычных пользователей -->
    <div id="orders" class="profile-section">
        <h3>История заказов</h3>
        <ul>
            <?php
            $order_query = "SELECT * FROM orders WHERE user_id = ?";
            $stmt = $conn->prepare($order_query);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $orders = $stmt->get_result();

            if ($orders->num_rows > 0) {
                while ($order = $orders->fetch_assoc()) {
                    echo "<li>Заказ #" . $order['id'] . " - " . $order['status'] . " - " . $order['total_price'] . " руб.</li>";
                }
            } else {
                echo "<li>Заказов пока нет.</li>";
            }
            ?>
        </ul>
    </div>

    <div id="favorites" class="profile-section">
        <h3>Избранное</h3>
        <ul>
            <?php
            $fav_query = "SELECT * FROM favorites f JOIN products p ON f.product_id = p.id WHERE f.user_id = ?";
            $stmt = $conn->prepare($fav_query);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $favorites = $stmt->get_result();

            if ($favorites->num_rows > 0) {
                while ($fav = $favorites->fetch_assoc()) {
                    echo "<li>" . $fav['name'] . " - " . $fav['price'] . " руб.</li>";
                }
            } else {
                echo "<li>Избранных товаров пока нет.</li>";
            }
            ?>
        </ul>
    </div>

    <div id="settings" class="profile-section">
        <h3>Настройки</h3>
        <form action="update_profile.php" method="POST">
    <input type="hidden" name="current_section" id="current_section" value="settings">
    <label>Имя:</label>
    <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
    <label>Email:</label>
    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
    <label>Новый пароль:</label>
    <input type="password" name="password">
    <button type="submit">Сохранить изменения</button>
</form>

    </div>

    <!-- Панель администратора -->
    <?php if ($role === 'admin'): ?>
        <div id="admin" class="profile-section">
    <h3 class="admin-title">Панель администратора</h3>
    <p class="admin-text">Здесь вы можете управлять пользователями и их данными.</p>
    <table class="admin-table">
    <tr>
        <th>ID</th>
        <th>Имя</th>
        <th>Email</th>
        <th>Роль</th>
        <th>Действия</th>
    </tr>
    <?php
    $users_query = "SELECT * FROM users";
    $stmt = $conn->prepare($users_query);
    $stmt->execute();
    $users_result = $stmt->get_result();

    while ($user_data = $users_result->fetch_assoc()) {
        $status_button = $user_data['status'] == 'blocked' ? 
            "<a href='profile.php?unblock_user={$user_data['id']}' class='admin-link'>Разблокировать</a>" : 
            "<a href='profile.php?block_user={$user_data['id']}' class='admin-link'>Заблокировать</a>";

        echo "<tr>
            <td>{$user_data['id']}</td>
            <td>{$user_data['name']}</td>
            <td>{$user_data['email']}</td>
            <td>{$user_data['role']}</td>
            <td>
                <a href='profile.php?delete_user={$user_data['id']}' class='admin-link'>Удалить</a>
                $status_button
            </td>
        </tr>";
    }
    ?>
</table>

        <!-- Форма добавления пользователя -->
<div id="add-user-form" style="display:none;">
    <h3 class="admin-title">Добавить пользователя</h3>
    <form method="POST" class="admin-form">
        <label class="admin-label">Имя:</label>
        <input type="text" name="name" required class="admin-input">
        <label class="admin-label">Email:</label>
        <input type="email" name="email" required class="admin-input">
        <button type="submit" name="add_user" class="admin-button">Добавить</button>
        <?php if (isset($_SESSION['add_user_message'])): ?>
            <p class="success-message"><?php echo $_SESSION['add_user_message']; ?></p>
            <?php unset($_SESSION['add_user_message']); ?>
        <?php endif; ?>
        <button type="button" class="admin-button" onclick="toggleAddUserForm()">Отменить</button>
    </form>
</div>


        <!-- Кнопка для отображения формы добавления пользователя -->
        <button onclick="toggleAddUserForm()" class="admin-button">Добавить пользователя</button>
    </div>
    <?php endif; ?>

</main>
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
<script>
  function showSection(sectionId) {
    const sections = document.querySelectorAll('.profile-section');
    sections.forEach(section => {
        section.style.display = 'none';
    });

    const activeSection = document.getElementById(sectionId);
    if (activeSection) {
        activeSection.style.display = 'block';
    }
}

function toggleAddUserForm() {
    const form = document.getElementById('add-user-form');
    form.style.display = form.style.display === 'none' ? 'block' : 'none';
}

// По умолчанию показываем только настройки
<?php if ($role === 'admin'): ?>
    showSection('admin');
<?php else: ?>
    showSection('settings');
<?php endif; ?>

</script>

</body>
</html>
