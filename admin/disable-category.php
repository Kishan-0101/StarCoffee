<?php
session_start();

if (isset($_SESSION['admin']['status'])) {

    include('../db.php');

    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $category_id = intval($_GET['id']);
    } else {
        $_SESSION['error'] = "Invalid category ID.";
        header("Location: category-list.php");
        exit();
    }

    $status = $_GET['status'];
    $query = "UPDATE category SET category_status = " . $status . " WHERE category_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $category_id);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "Category disabled successfully.";
        header("Location: category-list.php");
        exit();
    } else {
        $_SESSION['error'] = "Error disabling category: " . mysqli_error($conn);
        header("Location: category-list.php");
        exit();
    }

} else {
    header("Location: login.php");
    exit();
}

?>