<?php
session_start();

if (isset($_SESSION['user_id'])) {
  header('Location: index.php');
  die;
}

require_once "config.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (isset($_POST['username']) && isset($_POST['password'])) {
    $username = validate($_POST['username']);
    $password = md5(validate($_POST['password']));

    $sql = "select * from user where username='$username' and password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        if ($row['username'] === $username && $row['password'] === $password) {
          $_SESSION['user_id'] = $row['user_id'];
          $_SESSION['type'] = $row['type'];
          $_SESSION['user_avatar'] = $row['avatar'];
          header('Location: index.php');
          die;
        } else {
          header('Location: login.php?error=Incorrect login details');
          die;
        }
      } else {
        header('Location: login.php?error=Incorrect login details');
        die;
      }
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

  <title>E-Port :: Login</title>
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <link href="css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-primary">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-xl-10 col-lg-12 col-md-9">
        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <div class="row">
              <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
              <div class="col-lg-6">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                  </div>
                  <?php if (isset($_GET['error'])) { ?>
                    <div class="alert alert-danger" role="alert">
                      <?php echo $_GET['error'] ?>
                    </div>
                  <?php } ?>
                  <form class="user" method="post" action="login.php">
                    <div class="form-group">
                      <input type="text" class="form-control" placeholder="Username" required name="username">
                    </div>
                    <div class="form-group">
                      <input type="password" class="form-control" placeholder="Password" required name="password">
                    </div>
                    <button type="submit" class="btn btn-primary btn-user btn-block">
                      Login
                    </button>
                  </form>
                  <hr>
                  <div class="text-center">
                    <a class="small" href="register.php">Create an Account</a>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="vendor/jquery/jquery.min.js"></script>
  <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="js/sb-admin-2.min.js"></script>
</body>

</html>

<?php $conn->close() ?>