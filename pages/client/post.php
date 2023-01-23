<?php
  session_start();
  require_once "../../check_session.php";

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'addpost') {
      $user_id = $_SESSION['user_id'];
      $caption = $_POST['caption'];

      if (isset($_FILES['files'])) {
        $total = count($_FILES['files']['name']);
        $image_ids = array();

        for ($i = 0; $i < $total; $i++) {
          $tmpFilePath = $_FILES['files']['tmp_name'][$i];
          $filename = strtotime(date('y-m-d H:i')).'_'.basename($_FILES["files"]["name"][$i]);
          $location = "uploads/".$filename;
          move_uploaded_file($tmpFilePath, $location);

          $sql = "insert into image (path) values ('$filename')";
          if ($conn->query($sql)) {
            array_push($image_ids, $conn->insert_id);
          }
        }
        $image_ids_str = json_encode($image_ids);
        $status = 'PUBLISHED';
        $sql = "insert into post (user_id, caption, image_ids, status) values ('$user_id', '$caption', '$image_ids_str', '$status')";
        $conn->query($sql);
      } else {
        $status = 'PUBLISHED';
        $sql = "insert into post (user_id, caption, status) values ('$user_id', '$caption', '$status')";
        $conn->query($sql);
      }
    }
  }
?>