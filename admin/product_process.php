<?php
session_start();

if (!empty($_POST)) {
    include('../db.php');
    $_SESSION['error'] = array();

    // Sanitize and validate inputs
    $pro_name = trim($_POST['pro_name'] ?? '');
    $cat_name = trim($_POST['cat_name'] ?? '');
    $pro_price = trim($_POST['pro_price'] ?? '');
    $pro_desc = trim($_POST['pro_desc'] ?? '');

    if (empty($pro_name)) {
        $_SESSION['error']['pro_name'] = "Please enter the product name.";
    }

    if (empty($cat_name)) {
        $_SESSION['error']['cat_name'] = "Please select a category.";
    }

    if (empty($pro_price)) {
        $_SESSION['error']['pro_price'] = "Please enter the product price.";
    } elseif (!is_numeric($pro_price)) {
        $_SESSION['error']['pro_price'] = "Please enter a valid price (digits only).";
    }

    if (empty($pro_desc)) {
        $_SESSION['error']['pro_desc'] = "Please provide short description";
    }

    // File upload validation
    $allowed_ext = ['jpg', 'jpeg', 'png'];
    $max_size_mb = 10;
    $product_img = $_FILES['product_img'] ?? null;
    $pimg_nm = null;

    if (!$product_img || $product_img['error'] !== UPLOAD_ERR_OK) {
        $_SESSION['error']['product_img'] = "Please select a product image.";
    } else {
        $file_info = pathinfo($product_img['name']);
        $extension = strtolower($file_info['extension'] ?? '');
        $size_mb = round($product_img['size'] / 1024 / 1024, 2);

        // Check extension
        if (!in_array($extension, $allowed_ext)) {
            $_SESSION['error']['product_img'] = "Only JPG or PNG images are allowed.";
        }
        // Check size
        elseif ($size_mb > $max_size_mb) {
            $_SESSION['error']['product_img'] = "Maximum image size is 10MB.";
        }
        // Check MIME type
        elseif (!in_array(mime_content_type($product_img['tmp_name']), ['image/jpeg', 'image/png'])) {
            $_SESSION['error']['product_img'] = "Invalid image file type.";
        }
        // Sanitize and check file name
        else {
            $safe_name = preg_replace("/[^A-Za-z0-9_\-\.]/", '_', $file_info['filename']);
            $pimg_nm = time() . "_" . $safe_name . "." . $extension;
            $target_path = "../product-images/" . $pimg_nm;
            if (file_exists($target_path)) {
                $_SESSION['error']['product_img'] = "Image already exists.";
            }
        }
    }

    if (!empty($_SESSION['error'])) {
        header("Location: products.php");
        exit;
    }

    // Move uploaded file
    if (!move_uploaded_file($product_img['tmp_name'], $target_path)) {
        $_SESSION['error']['product_img'] = "Failed to upload image.";
        header("Location: products.php");
        exit;
    }

    // Insert into database using prepared statements
    $time = time();
    $stmt = $conn->prepare("INSERT INTO products (p_category, p_name, p_price, p_short_description, p_time, p_image) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isdsss", $cat_name, $pro_name, $pro_price, $pro_desc, $time, $pimg_nm);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Product added successfully!";
    } else {
        $_SESSION['error']['db'] = "Database error: " . $stmt->error;
    }
    $stmt->close();

    header("Location: products.php");
    exit;
} else {
    header("Location: products.php");
    exit;
}
?>