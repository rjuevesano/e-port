<?php
  $file = "pages/supplier/uploads/". $_GET["file"];
  $filename = $_GET['file'];

  if (strpos($filename, ".pdf") !== false) {
    // Header content type
    header('Content-type: application/pdf');  
    header('Content-Disposition: inline; filename="' . $filename . '"');
    header('Content-Transfer-Encoding: binary');
    header('Accept-Ranges: bytes');
    // Read the file
    @readfile($file);
  } else {
    if ($_SERVER['HTTP_HOST'] == 'localhost') {
      $url = 'http://localhost/e-port-latest/'.$file;
    } else {
      $url = 'https://www.multimediaeport.com/'.$file;
    }
    header('Location: '.$url);
  }
?>