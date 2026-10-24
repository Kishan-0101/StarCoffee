<?php

session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password_raw = $_POST['password'];

    if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&]).{8,}$/', $password_raw)) {
        echo "Password must be at least 8 characters, include uppercase, lowercase, number, and special character.";
        exit();
    }

    $password = password_hash($password_raw, PASSWORD_DEFAULT);

    // Check if user already exists
    $check = $conn->prepare("SELECT * FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {
        echo "This email is already registered.";
    } else {
        $stmt = $conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $email, $password);

        if ($stmt->execute()) {
            $newId = $conn->insert_id;
            $_SESSION['user'] = ['id' => (int) $newId, 'email' => $email];
            $_SESSION['user_id'] = (int) $newId;
            $_SESSION['show_auth_modal'] = false;
            if (function_exists('session_regenerate_id')) {
                session_regenerate_id(true);
            }
            header("Location: index.php"); // Redirect after signup
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
    }
}

?>