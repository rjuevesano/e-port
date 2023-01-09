<?php
  session_start();

  if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    die;
  }

  require_once "config.php";

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['confirmpassword'])) {
      $username = validate($_POST['username']);
      $password = md5(validate($_POST['password']));

      $sql = "select * from user where username='$username'";
      $result = $conn->query($sql);

      if ($result->num_rows === 1) {
        header('Location: register.php?error=Username is not available.');
        die;
      }

      $firstname = validate($_POST['firstname']);
      $lastname = validate($_POST['lastname']);
      $mobile = validate($_POST['mobile']) or '';
      $address = validate($_POST['address']) or '';
      $facebook_url = validate($_POST['facebook_url']) or '';
      $portfolio_url = validate($_POST['portfolio_url']) or '';
      $username = validate($_POST['username']);
      $password = $_POST['password'];
      $confirmpassword = $_POST['confirmpassword'];
      $type = $_POST['type'];

      if ($password !== $confirmpassword) {
        header('Location: register.php?error=Password did not match.');
        die;
      }

      $password = md5($_POST['password']);
      $status = $type == 'CLIENT' ? 'ACTIVE' : 'PENDING';
      $sql = "insert into user (username, password, type, status, firstname, lastname, mobile, address, facebook_url, portfolio_url) values ('$username', '$password', '$type', '$status', '$firstname', '$lastname', '$mobile', '$address', '$facebook_url', '$portfolio_url')";
      $conn->query($sql);
      $_SESSION['user_id'] = $conn->insert_id;
      $_SESSION['type'] = $type;
      $_SESSION['user_avatar'] = "";
      header('Location: index.php');
    }
  }
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>E-Port :: Register</title>
  <!-- Custom fonts for this template-->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <!-- Custom styles for this template-->
  <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-primary">
  <div class="container">
    <div class="card o-hidden border-0 shadow-lg my-5">
      <div class="card-body p-0">
        <div class="row">
          <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
          <div class="col-lg-7">
            <div class="p-5">
              <div class="text-center">
                <h1 class="h4 text-gray-900 mb-4">Create an Account</h1>
              </div>
              <?php if (isset($_GET['error'])) { ?>
                <div class="alert alert-danger" role="alert">
                  <?php echo $_GET['error'] ?>
                </div>
              <?php } ?>
              <form class="user" method="post" action="register.php">
                <div class="form-group">
                  <select class="form-control" name="type" required onchange="accountType()">
                    <option value="CLIENT">Client</option>
                    <option value="SUPPLIER">Supplier</option>
                  </select>
                </div>
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="text" class="form-control" placeholder="First Name" name="firstname" required>
                  </div>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" placeholder="Last Name" name="lastname" required>
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="text" class="form-control" placeholder="Mobile" name="mobile">
                  </div>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" placeholder="Address" name="address">
                  </div>
                </div>
                <div class="form-group row" id="more" style="display:none">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="text" class="form-control" placeholder="Facebook" name="facebook_url">
                  </div>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" placeholder="Portfolio" name="portfolio_url">
                  </div>
                </div>
                <hr class="my-4">
                <div class="form-group">
                  <input type="text" class="form-control" placeholder="Username" name="username" required>
                </div>
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="password" class="form-control" placeholder="Password" name="password" required>
                  </div>
                  <div class="col-sm-6">
                    <input type="password" class="form-control" placeholder="Confirm Password" name="confirmpassword" required>
                  </div>
                </div>
                <button type="submit" class="btn btn-primary btn-user btn-block">Register</button>
              </form>
              <hr>
              <div class="text-center">
                <a class="small" href="login.php">Already have an account?</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Bootstrap core JavaScript-->
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <!-- Core plugin JavaScript-->
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
  <!-- Custom scripts for all pages-->
  <script src="js/sb-admin-2.min.js"></script>
  <script>
    function accountType() {
      var type =document.getElementsByName('type')[0].value;
      if (type == 'CLIENT') {
        document.getElementById('more').style.display = 'none';
      } else {
        document.getElementById('more').style.display = 'flex';
      }
    }
  </script>
</body>

</html>

<?php $conn->close() ?>