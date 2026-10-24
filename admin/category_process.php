<?php
session_start();
include('../db.php');

$cat_name = trim($_POST['cat_name'] ?? '');
$cat_desc = trim($_POST['cat_desc'] ?? '');
$category_id = isset($_POST['category_id']) ? intval($_POST['category_id']) : 0;
$image_name = null;

// Error array
$_SESSION['error'] = [];

// Validate category name
if (empty($cat_name)) {
    $_SESSION['error'][] = "Category name is required.";
}

// Validate category description
if (empty($cat_desc)) {
    $_SESSION['error'][] = "Category description is required.";
}

// Handle image upload if a new image is provided
if (isset($_FILES['category_image']) && $_FILES['category_image']['error'] == 0) {
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    $ext = strtolower(pathinfo($_FILES['category_image']['name'], PATHINFO_EXTENSION));
    if (in_array($ext, $allowed)) {
        $image_name = uniqid('cat_', true) . '.' . $ext;
        move_uploaded_file($_FILES['category_image']['tmp_name'], '../category-images/' . $image_name);
    } else {
        $_SESSION['error'][] = "Invalid image format. Allowed: jpg, jpeg, png, gif.";
    }
}

// If there are errors, redirect back
if (!empty($_SESSION['error'])) {
    header("Location: category.php");
    exit;
}

// If category_id is set, UPDATE; else, INSERT
if ($category_id > 0) {
    // If a new image is uploaded, update it; else, keep the old image
    if ($image_name) {
        $stmt = $conn->prepare("UPDATE category SET category_name = ?, category_description = ?, category_image = ? WHERE category_id = ?");
        $stmt->bind_param("sssi", $cat_name, $cat_desc, $image_name, $category_id);
    } else {
        $stmt = $conn->prepare("UPDATE category SET category_name = ?, category_description = ? WHERE category_id = ?");
        $stmt->bind_param("ssi", $cat_name, $cat_desc, $category_id);
    }
    $stmt->execute();
    $_SESSION['success'] = "Category updated successfully!";
} else {
    // Insert new category
    $stmt = $conn->prepare("INSERT INTO category (category_name, category_description, category_image) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $cat_name, $cat_desc, $image_name);
    $stmt->execute();
    $_SESSION['success'] = "Category added successfully!";
}
$stmt->close();

header("Location: category-list.php");
exit;
?>