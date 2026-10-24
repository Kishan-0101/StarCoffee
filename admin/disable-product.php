<?php
session_start();

if (isset($_SESSION['admin']['status'])) {

    include('../db.php');

    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $product_id = intval($_GET['id']);
    } else {
        $_SESSION['error'] = "Invalid product ID.";
        header("Location: products-list.php");
        exit();
    }

    $status = $_GET['status'];
    $query = "UPDATE products SET p_status = " . $status . " WHERE p_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $product_id);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "Product disabled successfully.";
        header("Location: products-list.php");
        exit();
    } else {
        $_SESSION['error'] = "Error disabling product: " . mysqli_error($conn);
        header("Location: products-list.php");
        exit();
    }

} else {
    header("Location: login.php");
    exit();
}

?>