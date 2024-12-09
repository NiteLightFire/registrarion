<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация</title>
</head>
<body>
    <h1>Регистрация</h1>
    <form method="POST">
        <label>Логин: <input type="text" name="login" required></label><br>
        <label>Пароль: <input type="password" name="password" required></label><br>
        <label>Подтвердите пароль: <input type="password" name="confirm_password" required></label><br>
        <label>Email: <input type="email" name="email" required></label><br>
        <label>Дата рождения: <input type="date" name="date_of_birth" required></label><br>
        <button type="submit">Зарегистрироваться</button>
    </form>
</body>
</html>
<?php
session_start();
require_once 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm_password']);
    $email = trim($_POST['email']);
    $dateOfBirth = trim($_POST['date_of_birth']);
    if (empty($login) || empty($password) || empty($confirmPassword) || empty($email) || empty($dateOfBirth)) {
        echo "Все поля должны быть заполнены.";
        exit;
    }
    if ($password !== $confirmPassword) {
        echo "Пароли не совпадают.";
        exit;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Некорректный email.";
        exit;
    }
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $query = "SELECT * FROM users WHERE login = ? OR email = ?";
    $stmt = mysqli_prepare($link, $query);
    if ($stmt === false) {
        die("Ошибка подготовки запроса: " . mysqli_error($link));
    }
    mysqli_stmt_bind_param($stmt, 'ss', $login, $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) > 0) {
        echo "Логин или email уже заняты.";
        exit;
    }
    $query = "INSERT INTO users (login, password, email, date_of_birth) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($link, $query);
    if ($stmt === false) {
        die("Ошибка подготовки запроса: " . mysqli_error($link));
    }
    mysqli_stmt_bind_param($stmt, 'ssss', $login, $hashedPassword, $email, $dateOfBirth);
    if (mysqli_stmt_execute($stmt)) {
        echo "Регистрация успешна!";
        $_SESSION['auth'] = true;
        $_SESSION['id'] = mysqli_insert_id($link);
        header('Location: profile.php');
        exit;
    } else {
        echo "Ошибка регистрации: " . mysqli_error($link);
    }
}
?>