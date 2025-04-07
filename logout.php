<?php
session_start();
session_unset(); // Очищаем все переменные сессии
session_destroy(); // Удаляем сессию

header("Location: index.php"); // Перенаправляем на страницу входа
exit();
?>
