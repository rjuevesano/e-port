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
      $file_curriculum = '';
      $file_id1 = '';
      $file_id2 = '';
      $file_permit = '';

      if (isset($_FILES['usercurriculum'])) {
        $tmpFilePath = $_FILES['usercurriculum']['tmp_name'];
        $file_curriculum = strtotime(date('y-m-d H:i')).'_'.basename($_FILES["usercurriculum"]["name"]);
        $location = "pages/supplier/uploads/".$file_curriculum;
        move_uploaded_file($tmpFilePath, $location);
      }
      if (isset($_FILES['userid1'])) {
        $tmpFilePath = $_FILES['userid1']['tmp_name'];
        $file_id1 = strtotime(date('y-m-d H:i')).'_'.basename($_FILES["userid1"]["name"]);
        $location = "pages/supplier/uploads/".$file_id1;
        move_uploaded_file($tmpFilePath, $location);
      }
      if (isset($_FILES['userid2'])) {
        $tmpFilePath = $_FILES['userid2']['tmp_name'];
        $file_id2 = strtotime(date('y-m-d H:i')).'_'.basename($_FILES["userid2"]["name"]);
        $location = "pages/supplier/uploads/".$file_id2;
        move_uploaded_file($tmpFilePath, $location);
      }
      if (isset($_FILES['userpemit'])) {
        $tmpFilePath = $_FILES['userpemit']['tmp_name'];
        $file_permit = strtotime(date('y-m-d H:i')).'_'.basename($_FILES["userpemit"]["name"]);
        $location = "pages/supplier/uploads/".$file_permit;
        move_uploaded_file($tmpFilePath, $location);
      }

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
      $sql = "insert into user (username, password, type, status, firstname, lastname, mobile, address, facebook_url, portfolio_url, file_curriculum, file_id1, file_id2, file_permit) values ('$username', '$password', '$type', '$status', '$firstname', '$lastname', '$mobile', '$address', '$facebook_url', '$portfolio_url', '$file_curriculum', '$file_id1', '$file_id2', '$file_permit')";
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
 <style>
    .upload__inputfile {
      width: .1px;
      height: .1px;
      opacity: 0;
      overflow: hidden;
      position: absolute;
      z-index: -1;
    }
    .bg-register-image1 {
  background: url("reg.webp");
  background-position: center;
  background-size: cover;
}
.bg-gradient-primary1 {
    background-color: #4e73df;
     background: url("back.jpg");
    background-size: cover;
    }
.card{
  background-color: #121314;
}
.text-gray-900 {
    color: #ffffff !important;
  }
  .btn-primary {
    color: #fff;
   background: linear-gradient(90deg, rgba(1,1,1,1) 23%, #8b6300 100%);
    border-color: #fff;
  }
  .btn-primary:hover {
    color: #fff;
   background: linear-gradient(90deg, rgba(191,123,1,1) 23%, rgba(11,11,11,1) 100%);
    border-color: #fff;
  }
  </style>
</head>

<body class="bg-gradient-primary1">
  <div class="container">
    <div class="card o-hidden border-0 shadow-lg my-5">
      <div class="card-body p-0">
        <div class="row">
          <div class="col-lg-5 d-none d-lg-block bg-register-image1"></div>
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
              <form class="user" method="post" action="register.php" enctype="multipart/form-data">
                <div class="form-group">
                  <select class="form-control" name="type" required onchange="accountType()">
                    <option value="CLIENT">Client</option>
                    <option value="SUPPLIER">Supplier</option>
                  </select>
                </div>
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="text" class="form-control" placeholder="First Name" name="firstname" required onkeyup="lettersOnly(this)">
                  </div>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" placeholder="Last Name" name="lastname" required onkeyup="lettersOnly(this)">
                  </div>
                </div>
                <div class="form-group row">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="text" class="form-control" placeholder="Mobile" name="mobile" required onkeypress="return isNumeric(event)">
                  </div>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" placeholder="Address" name="address" required>
                  </div>
                </div>
                <div class="form-group row supplier" style="display:none">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <input type="text" id="inputId" class="form-control" placeholder="Facebook" name="facebook_url">
                  </div>
                  <div class="col-sm-6">
                    <input type="text" class="form-control" placeholder="Portfolio" name="portfolio_url">
                  </div>
                </div>
                <div class="form-group row supplier" style="display:none">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <label>UPLOAD CURRICULUM VITAE <span style="color:red">*</span></label>
                    <input type="file" name="usercurriculum" accept=".pdf, image/*">
                  </div>
                </div>
                <div class="form-group row supplier" style="display:none">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <label>UPLOAD 2 VALID IDS <span style="color:red">*</span></label>
                    <input type="file" name="userid1" accept=".pdf, image/*">
                  </div>
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <label>&nbsp;</label>
                    <input type="file" name="userid2" accept=".pdf, image/*">
                  </div>
                </div>
                <div class="form-group row supplier" style="display:none">
                  <div class="col-sm-6 mb-3 mb-sm-0">
                    <label>UPLOAD BUSINESS PERMIT <span style="color:red">*</span></label>
                    <input type="file" name="userpemit" accept=".pdf, image/*">
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
      var type = document.getElementsByName('type')[0].value;

      if (type == 'CLIENT') {
        $(".supplier").css({display: 'none'});
        document.getElementsByName('facebook_url')[0].removeAttribute('required');
        document.getElementsByName('portfolio_url')[0].removeAttribute('required');
        document.getElementsByName('usercurriculum')[0].removeAttribute('required');
        document.getElementsByName('userid1')[0].removeAttribute('required');
        document.getElementsByName('userid2')[0].removeAttribute('required');
        document.getElementsByName('userpemit')[0].removeAttribute('required');
      } else {
        $(".supplier").css({display: 'flex'});
        document.getElementsByName('facebook_url')[0].setAttribute('required', true);
        document.getElementsByName('portfolio_url')[0].setAttribute('required', true);
        document.getElementsByName('usercurriculum')[0].setAttribute('required', true);
        document.getElementsByName('userid1')[0].setAttribute('required', true);
        document.getElementsByName('userid2')[0].setAttribute('required', true);
        document.getElementsByName('userpemit')[0].setAttribute('required', true);
      }
    }
  </script>
  <script>
    function lettersOnly(input) {
          var regex = /[^a-z]/gi;
          input.value = input.value.replace(regex,"");

  }
  </script>
   <script>
  function isNumeric(e) {
    var key = e.keyCode || e.which;
    if (key < 48 || key > 57) {
      e.preventDefault();
    }
  }
</script>
<script>
  window.onload = function(){
    //get the input element by its id
    var input = document.getElementById("inputId");
    //add an event listener to the input
    input.addEventListener("blur", function() {
    // check if the input value is a valid URL
    if (!input.value.includes("https://www.facebook.com/") && !input.value.includes("www.facebook.com/")) {
        alert("Please enter a valid Facebook link");
    }
    });
  }
</script>
</body>

</html>

<?php $conn->close() ?>