<?php

session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user'] = ['id' => (int) $user['id'], 'email' => $user['email']];
        $_SESSION['user_id'] = (int) $user['id'];
        $_SESSION['show_auth_modal'] = false;
        if (function_exists('session_regenerate_id')) {
            session_regenerate_id(true);
        }
        $stmt->close();
        header("Location: index.php");
        exit();
    } else {
        $_SESSION['auth_error'] = 'Invalid email or password.';
        $_SESSION['show_auth_modal'] = true;
        $stmt->close();
        header("Location: index.php");
        exit();
    }
}

?>