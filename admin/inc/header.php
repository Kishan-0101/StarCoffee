<?php
session_start();

if (empty($_SESSION['admin']['status'])) {
  header("Location: login.php");
  exit;
}

include("../db.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>StarCoffee | Admin</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet"
    href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Remix Icon -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/remixicon/4.6.0/remixicon.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>

<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-dark">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
        <li class="nav-item"><a class="nav-link" href="#" role="button">Hi,
            <?php echo ucfirst(explode('@', $_SESSION['admin']['email'])[0]); ?> </a></li>
      </ul>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">
        <!-- Navbar Search -->
        <li class="nav-item"><a class="nav-link" href="logout.php" role="button">Logout</a></li>
        <li class="nav-item">
          <a class="nav-link" data-widget="control-sidebar" data-controlsidebar-slide="true" href="#" role="button">
            <i class="fas fa-th-large"></i>
          </a>
        </li>
      </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="index.php" class="brand-link">
        <i class="fa-regular fa-circle-user"></i>
        <span class="brand-text font-weight-light"><strong>StarCoffee Admin</strong></span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
            <li class="nav-item">
              <a href="../index.php" target="_blank" class="nav-link">
                <i class="nav-icon fa-solid fa-s"></i>
                <p>
                  StarCoffee
                  <span class="right badge badge-success badge-pill">Visit</span>
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="index.php" class="nav-link">
                <i class="nav-icon fa-solid fa-house"></i>
                <p>
                  Dashboard
                </p>
              </a>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon fa-solid fa-list"></i>
                <p>
                  Menu
                  <i class="right fas fa-angle-left"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="category.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Add Category</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="category-list.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Category List</p>
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon fa-solid fa-mug-saucer"></i>
                <p>
                  Products
                  <i class="right fas fa-angle-left"></i>
                </p>

              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="products.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Add Products</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="products-list.php" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Product List</p>
                  </a>
                </li>
              </ul>
            </li>
            <li class="nav-item">
              <a href="users.php" class="nav-link">
                <i class="fa-solid fa-users"></i>
                <p>
                  Users
                </p>
                <?php
                $user_count = 0;
                $user_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users");
                if ($user_result) {
                  $row = mysqli_fetch_assoc($user_result);
                  $user_count = $row['total'];
                }
                ?>
                <span class=" badge badge-warning right"><?php echo $user_count; ?></span>
              </a>
            </li>
            <li class="nav-item">
              <a href="orders.php" class="nav-link">
                <i class="fa-solid fa-cart-shopping"></i>
                <p>
                  Orders
                </p>
                <?php
                $order_count = 0;
                $order_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders");
                if ($order_result) {
                  $row = mysqli_fetch_assoc($order_result);
                  $order_count = $row['total'];
                }
                ?>
                <span class=" badge badge-info right"><?php echo $order_count; ?></span>
              </a>
            </li>
            <li class="nav-item">
              <a href="reviews.php" class="nav-link">
                <i class="fa-solid fa-face-grin-stars"></i>
                <p>
                  Reviews
                </p>
                <?php
                $review_count = 0;
                $review_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM reviews");
                if ($review_result) {
                  $row = mysqli_fetch_assoc($review_result);
                  $review_count = $row['total'];
                }
                ?>
                <span class=" badge badge-success right"><?php echo $review_count; ?></span>
              </a>
            </li>
            <li class="nav-item">
              <a href="contact.php" class="nav-link">
                <i class="fa-regular fa-address-book"></i>
                <p>
                  Contacts
                </p>
                <?php
                $contact_count = 0;
                $contact_count = mysqli_query($conn, "SELECT COUNT(*) AS total FROM contacts");
                if ($contact_count) {
                  $row = mysqli_fetch_assoc($contact_count);
                  $contact_count = $row['total'];
                }
                ?>
                <span class="badge badge-danger right"><?php echo $contact_count; ?></span>
              </a>
            </li>
          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>