<?php
	$host= "localhost";
	$username= "root";
	$password = "";
	$database = "e-port";

	$conn = mysqli_connect($host, $username, $password, $database);

	if (!$conn) {
		echo "Connection failed!";
	}

  function json_response($data=null, $httpStatus=200) {
    header_remove();
    header('Content-type: application/json');
    http_response_code($httpStatus);
    echo json_encode($data);
    exit();
  }

  function validate($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }
?>