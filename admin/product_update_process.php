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
    $pimg_nm = null;
    $target_path = null;
    if (!empty($_FILES['product_img']['name'])) {
        $allowed_ext = ['jpg', 'jpeg', 'png'];
        $max_size_mb = 10;
        $product_img = $_FILES['product_img'];
        $file_info = pathinfo($product_img['name']);
        $extension = strtolower($file_info['extension'] ?? '');
        $size_mb = round($product_img['size'] / 1024 / 1024, 2);

        if (!in_array($extension, $allowed_ext)) {
            $_SESSION['error']['product_img'] = "Only JPG or PNG images are allowed.";
        } elseif ($size_mb > $max_size_mb) {
            $_SESSION['error']['product_img'] = "Maximum image size is 10MB.";
        } elseif (!in_array(mime_content_type($product_img['tmp_name']), ['image/jpeg', 'image/png'])) {
            $_SESSION['error']['product_img'] = "Invalid image file type.";
        } else {
            $safe_name = preg_replace("/[^A-Za-z0-9_\-\.]/", '_', $file_info['filename']);
            $pimg_nm = time() . "_" . $safe_name . "." . $extension;
            $target_path = "../product-images/" . $pimg_nm;
        }
    }

    if (!empty($_SESSION['error'])) {
        header("Location: edit-product.php?id=" . intval($_POST['p_id']));
        exit;
    } else {
        // If a new image is uploaded, delete the old one
        if ($pimg_nm) {
            $select_query = "SELECT p_image FROM products WHERE p_id = ?";
            $stmt = mysqli_prepare($conn, $select_query);
            mysqli_stmt_bind_param($stmt, 'i', $_POST['p_id']);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $old_image);
            mysqli_stmt_fetch($stmt);
            mysqli_stmt_close($stmt);

            if ($old_image && file_exists("../product-images/" . $old_image)) {
                unlink("../product-images/" . $old_image);
            }
            // Move new image
            move_uploaded_file($product_img['tmp_name'], $target_path);
        }

        // Prepare update query
        if ($pimg_nm) {
            $query = "UPDATE products SET p_name = ?, p_category = ?, p_price = ?, p_short_description = ?, p_image = ? WHERE p_id = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, 'sidssi', $pro_name, $cat_name, $pro_price, $pro_desc, $pimg_nm, $_POST['p_id']);
        } else {
            $query = "UPDATE products SET p_name = ?, p_category = ?, p_price = ?, p_short_description = ? WHERE p_id = ?";
            $stmt = mysqli_prepare($conn, $query);
            mysqli_stmt_bind_param($stmt, 'sidsi', $pro_name, $cat_name, $pro_price, $pro_desc, $_POST['p_id']);
        }

        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_close($stmt);
            $_SESSION['success'] = "Product updated successfully!";
        } else {
            $_SESSION['error']['db'] = "Database update failed.";
        }
        header("Location: products-list.php");
        exit;
    }
} else {
    header("Location: products.php");
    exit;
}
?>