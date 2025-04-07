<?php include('db.php'); 
session_start();?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SMAM - Студия мебели</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<!-- CallShark Widget -->
<script id="AS_callshark" type="module" async src="https://dashboard.callshark.ru/resources/widgets/callshark.js?client=1099079"></script>
<!-- /CallShark Widget -->
<body>

<header>
    <div class="logo-container">
    <a href="index.php">
        <img src="logo.png" alt="Логотип" class="logo">
    </div>
    <nav>
        <ul>
            <li><a href="#new-collection">Новая коллекция</a></li>
            <li><a href="#about-us">О компании</a></li>
            <li><a href="#catalog">Каталог</a></li>
            <li><a href="#stores">Наши магазины</a></li>
            <li><a href="#testimonials">Отзывы</a></li>
            <?php if (isset($_SESSION['user_id'])): ?>
                <li><a href="profile.php">Личный кабинет</a></li>
                <li><a href="logout.php">Выход</a></li>
            <?php else: ?>
                <li><a href="register.php">Регистрация/Авторизация</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>

<main>

<section id="welcome" class="welcome-block">
    <h1>SMAM</h1>
    <p>Добро пожаловать на сайт студии мебели SMAM. Мы предлагаем эксклюзивные решения для вашего интерьера, которые идеально подойдут для вашего дома или офиса.</p>
</section>

<section id="new-collection" class="card">
    <h2>Новая коллекция</h2>
    <div class="collection-images">
        <div class="image-item">
            <img src="collection1.png" alt="Коллекция 1" class="image1">
            <h3>Кресло 1</h3>
            <p>5000 руб.</p>
        </div>
        <div class="image-item">
            <img src="collection2.png" alt="Коллекция 2" class="image2">
            <h3>Кресло 2</h3>
            <p>4500 руб.</p>
        </div>
        <div class="image-item">
            <img src="collection3.png" alt="Коллекция 3" class="image3">
            <h3>Кресло 3</h3>
            <p>6000 руб.</p>
        </div>
    </div>
    <p>Откройте для себя новые дизайны мебели, которые идеально впишутся в любой интерьер.</p>
</section>

<section id="about-us">
    <h2>О компании</h2>
    <p>Мы — студия мебели SMAM, которая предлагает уникальные и стильные решения для вашего интерьера. Наша цель — создавать функциональную и красивую мебель, которая будет служить вам долгие годы.</p>
    <div class="about-gallery">
        <h3>Наши работы</h3>
        <div class="gallery-images">
            <div><img src="about1.webp" alt="Изображение 1"></div>
            <div><img src="about2.webp" alt="Изображение 2"></div>
            <div><img src="about3.webp" alt="Изображение 3"></div>
            <div><img src="about4.webp" alt="Изображение 4"></div>
        </div>
    </div>
</section>


<section id="catalog">
    <h2>Каталог</h2>
    <div class="catalog-images">
        <div class="image-item">
            <img src="catalog1.png" alt="Продукция 1" class="image1">
            <h3>Кресло 1</h3>
            <p>7000 руб.</p>
        </div>
        <div class="image-item">
            <img src="catalog2.png" alt="Продукция 2" class="image2">
            <h3>Кресло 2</h3>
            <p>7500 руб.</p>
        </div>
        <div class="image-item">
            <img src="catalog3.png" alt="Продукция 3" class="image3">
            <h3>Кресло 3</h3>
            <p>8000 руб.</p>
        </div>
    </div>
    <p>Посмотрите наш каталог мебели, где вы найдете идеальные решения для вашего дома и офиса.</p>
</section>

<section id="stores">
    <h2>Наши магазины</h2>
    <p>Найдите ближайший магазин и получите консультацию по выбору мебели. Мы всегда рады помочь вам!</p>
    
    <div class="stores-container">
        <!-- Карта слева -->
        <div class="map-container">
            <script type="text/javascript" charset="utf-8" async 
                src="https://api-maps.yandex.ru/services/constructor/1.0/js/?um=constructor%3A1234567890abcdef&width=100%25&height=100%25&lang=ru_RU&scroll=true">
            </script>
        </div>
        
        <!-- Список адресов справа -->
        <div class="store-list">
            <h3>Адреса филиалов</h3>
            <ul>
                <li><i class="fas fa-map-marker-alt"></i> Москва, ул. Тверская, 12</li>
                <li><i class="fas fa-map-marker-alt"></i> Санкт-Петербург, Невский проспект, 45</li>
                <li><i class="fas fa-map-marker-alt"></i> Казань, ул. Баумана, 5</li>
                <li><i class="fas fa-map-marker-alt"></i> Новосибирск, Красный проспект, 40</li>
                <li><i class="fas fa-map-marker-alt"></i> Екатеринбург, пр. Ленина, 15</li>
            </ul>
        </div>
    </div>
</section>


<section id="testimonials">
    <div class="container">
        <h2>ОТЗЫВЫ КЛИЕНТОВ</h2>
        <div class="testimonials-container">
            <div class="testimonial-card">
                <h3>АНАСТАСИЯ В.</h3>
                <div class="rating">⭐⭐⭐⭐⭐</div>
                <p>Недавно приобрела стул этой компании и очень довольна качеством и комфортом. Доставка была быстрой, и обслуживание клиентов было превосходным.</p>
                <p class="testimonial-date">03.10.2023</p>
            </div>
            <div class="testimonial-card">
                <h3>ВАЛЕРИЯ З.</h3>
                <div class="rating">⭐⭐⭐⭐⭐</div>
                <p>Я являюсь клиентом уже много лет и продолжаю покупать снова и снова. Разнообразие вариантов мебели фантастическое, а цены конкурентоспособные.</p>
                <p class="testimonial-date">08.10.2023</p>
            </div>
            <div class="testimonial-card">
                <h3>АЛЕКСАНДР А.</h3>
                <div class="rating">⭐⭐⭐⭐☆</div>
                <p>Я купил комплект обеденного стола и стульев, и весь процесс покупки прошел гладко и эффективно. Мебель прибыла в идеальном состоянии и прекрасно смотрится в моей столовой.</p>
                <p class="testimonial-date">23.10.2023</p>
            </div>
        </div>
        <button class="review-btn">НАПИСАТЬ ОТЗЫВ ➜</button>
    </div>
</section>

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

<!-- Чат-бот -->
<div id="chatbot">
    <div class="close-chat" onclick="toggleChatbot()">×</div>
    <div class="chat-header">
        <img src="bot-avatar.jpg" alt="Аватар бота" class="bot-avatar">
        <p>Чат-бот</p>
    </div>
    <div class="chat-content">
        <p><strong>Бот:</strong> Привет! Чем могу помочь?</p>
    </div>
    <input type="text" id="userMessage" placeholder="Введите сообщение..." oninput="checkInput(this)">
    <div class="chat-btn">
        <button onclick="sendMessage()">Отправить</button>
    </div>
</div>

<script src="scripts.js"></script>

</body>
</html>
