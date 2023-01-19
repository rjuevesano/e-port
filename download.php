<?php
  header("Content-Type: application/octet-stream");
    
  $file = "pages/supplier/uploads/". $_GET["file"];
  $filename = $_GET['file'];
    
  header("Content-Disposition: attachment; filename=" . urlencode($filename));   
  header("Content-Type: application/download");
  header("Content-Description: File Transfer");            
  header("Content-Length: " . filesize($file));
    
  flush(); // This doesn't really matter.
    
  $fp = fopen($file, "r");
  while (!feof($fp)) {
    echo fread($fp, 65536);
    flush(); // This is essential for large downloads
  }

  fclose($fp); 
?>