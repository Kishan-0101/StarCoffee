<?php
session_start();
if (isset($_SESSION['admin']['status'])) {
    # code...
    include('../db.php');

    if (isset($_GET['id']) && is_numeric($_GET['id'])) {
        $category_id = intval($_GET['id']);
    } else {
        $_SESSION['error'] = "Invalid category ID.";
        header("Location: category-list.php");
        exit();
    }
    $select_query = "SELECT category_image FROM category WHERE category_id = ?";
    $stmt = mysqli_prepare($conn, $select_query);
    mysqli_stmt_bind_param($stmt, 'i', $category_id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $category_image);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    unlink("../category-images/" . $category_image);

    $query = "DELETE FROM category WHERE category_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $category_id);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['success'] = "Category deleted successfully.";
        header("Location: category-list.php");
        exit();
    } else {
        $_SESSION['error'] = "Error deleting category: " . mysqli_error($conn);
        header("Location: category-list.php");
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}

?>