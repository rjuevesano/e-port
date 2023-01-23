<?php
  session_start();
  require_once "config.php";

  if (!isset($_SESSION['user_id'])) {
    header('Location: ../../login.php');
    die;
  }

  if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $sql = "select * from user where user_id=$user_id";
    $result = $conn->query($sql);
    if (!$result->num_rows) {
        header('Location: ../../logout.php');
        die;
    }
  }
?>