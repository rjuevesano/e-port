<?php
  session_start();
  require_once "config.php";

  if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    die;
  }

  if (isset($_SESSION['type']) && $_SESSION['type'] == 'ADMIN') {
    header ("Location: ./pages/admin/index.php");
    die;
  } else if (isset($_SESSION['type']) && $_SESSION['type'] == 'SUPPLIER') {
    header ("Location: ./pages/supplier/index.php");
    die;
  } else if (isset($_SESSION['type']) && $_SESSION['type'] == 'CLIENT') {
    header ("Location: ./pages/client/index.php");
    die;
  }
?>