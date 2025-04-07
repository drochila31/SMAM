<?php
// Соединение с основной базой данных
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "smam"; // Основная база данных, а не chatbot

$conn = new mysqli($servername, $username, $password, $dbname);

// Проверка соединения
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Получение сообщения от пользователя
$message = isset($_POST['message']) ? trim($_POST['message']) : '';

// Функция для проверки наличия фиксированного ответа в базе данных
function getAnswerFromDatabase($message, $conn) {
    $sql = "SELECT answer FROM chatbot_answers WHERE question LIKE ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $message);
    $stmt->execute();
    $stmt->bind_result($answer);
    $stmt->fetch();
    $stmt->close();

    return $answer;
}

// Если сообщение пустое
if (empty($message)) {
    echo json_encode(['response' => 'Пожалуйста, введите ваш вопрос.']);
    exit;
}

// Проверка на фиксированные вопросы
$response = getAnswerFromDatabase($message, $conn);

if ($response) {
    // Ответ найден
    echo json_encode(['response' => $response]);
} else {
    // Ответ не найден, предлагается обратиться по почте
    echo json_encode(['response' => 'Извините, я не могу ответить на этот вопрос. Напишите нам на почту smam@mail.ru']);
}

$conn->close();

?>
