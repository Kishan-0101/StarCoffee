<?php
include("inc/header.php");

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "DELETE FROM users WHERE id = $id";
    $result = mysqli_query($conn, $query);
    if ($result) {
        header("Location: users.php");
    }
}

include("inc/footer.php");
