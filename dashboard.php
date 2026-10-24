<?php
session_start();
if (!isset($_SESSION['user'])) {
  header("Location: index.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <link rel="stylesheet" href="assets/css/styles.css">
</head>

<body>
  <header class="header" id="header">
    <nav class="nav container">
      <a href="index.php" class="nav__logo">STARCOFFEE</a>
      <ul class="nav__list">
        <li><span class="nav__link">Welcome, <?php echo $_SESSION['user']; ?> </span></li>
        <li><a href="logout.php" class="nav__link">LOGOUT</a></li>
      </ul>
    </nav>
  </header>

  <main class="main section">
    <h2 class="section__title">Welcome to your Dashboard</h2>
    <p style="text-align: center;">This is a protected page only visible after login.</p>
  </main>
</body>

</html>