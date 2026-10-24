<?php
session_start();
if (isset($_SESSION['admin']['status'])) {
    # code...
    include('../db.php');

    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $product_id = intval($_GET['id']);
    } else {
        $_SESSION['error'] = "Invalid product ID.";
        header("Location: products-list.php");
        exit();
    }
    
    $select_query = "SELECT p_image FROM products WHERE p_id = ?";
    $stmt = mysqli_prepare($conn, $select_query);
    mysqli_stmt_bind_param($stmt, 'i', $product_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $product_image);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    unlink("../product-images/" . $product_image);

    $query = "DELETE FROM products WHERE p_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $product_id);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "Product deleted successfully.";
        header("Location: products-list.php");
        exit();
    } else {
        $_SESSION['error'] = "Error deleting product: " . mysqli_error($conn);
        header("Location: products-list.php");
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}
?>