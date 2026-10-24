<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: login.php');
    exit;
}

$email = trim($_POST['usernm'] ?? '');
$password = $_POST['pswd'] ?? '';

if ($email === '' || $password === '') {
    http_response_code(400);
    exit('Email and password are required.');
}

require_once __DIR__ . '/../db.php';

$stmt = $conn->prepare('SELECT a_id, a_email, a_pwd FROM admin WHERE a_email = ? AND a_status = 1 LIMIT 1');
$stmt->bind_param('s', $email);
$stmt->execute();
$admin = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$admin || !is_string($admin['a_pwd']) || !password_verify($password, $admin['a_pwd'])) {
    http_response_code(401);
    exit('Invalid username or password.');
}

session_regenerate_id(true);
$_SESSION['admin'] = [
    'email' => $admin['a_email'],
    'id' => (int) $admin['a_id'],
    'status' => true,
];

if (password_needs_rehash($admin['a_pwd'], PASSWORD_DEFAULT)) {
    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    $update = $conn->prepare('UPDATE admin SET a_pwd = ? WHERE a_id = ?');
    $update->bind_param('si', $passwordHash, $_SESSION['admin']['id']);
    $update->execute();
    $update->close();
}

header('Location: index.php');
exit;