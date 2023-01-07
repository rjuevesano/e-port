<?php
  session_start();
  require_once "../../config.php";

  if (!isset($_SESSION['user_id'])) {
    header('Location: ../../login.php');
    die;
  }

  $user_id = $_SESSION['user_id'];
  $sql = "select * from user where user_id=$user_id";
  $result = $conn->query($sql);
  $row = $result->fetch_assoc();
  $_SESSION['user_avatar'] = $row['avatar'];

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'updatephoto') {
      $location = "uploads/".$row['avatar'];
      if (file_exists($location)) {
        unlink($location);
      }

      $tmpFilePath = $_FILES['files']['tmp_name'];
      $filename = strtotime(date('y-m-d H:i')).'_'.basename($_FILES["files"]["name"]);
      $location = "uploads/".$filename;
      move_uploaded_file($tmpFilePath, $location);

      $sql = "update user set avatar='$filename' where user_id=$user_id";
      $conn->query($sql);
      json_response([
        'success' => true,
        'message' => 'Successfully updated.'
      ]);
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
  <title>E-Port :: Profile</title>
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <style>
    .image_inner_container img{
      height: 150px;
      width: 150px;
      border-radius: 50%;
      border: 5px solid;
    }
    .upload__inputfile {
      width: .1px;
      height: .1px;
      opacity: 0;
      overflow: hidden;
      position: absolute;
      z-index: -1;
    }
  </style>
</head>

<body id="page-top">
  <div id="wrapper">
    <?php include 'sidebar.php' ?>
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <?php include 'topbar.php' ?>
        <div class="container-fluid">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Profile</h1>
          </div>
          <div class="row">
            <form onsubmit="return updateUser(<?php echo $row['user_id'] ?>)">
              <div class="modal-body">
                <h6 class="mb-3" style="font-weight: bold;">Personal Information</h6>
                <div class="row mb-4 align-items-center">
                  <div class="d-flex justify-content-center h-100">
                    <div class="image_inner_container">
                      <img id="profile-photo" src="<?php echo $_SESSION['user_avatar'] ? "uploads/".$_SESSION['user_avatar'] : '../../img/undraw_profile.svg' ?>">
                    </div>
                  </div>
                  <div class="ml-3">
                    <label class="btn btn-primary">
                      Change photo
                      <input type="file" accept="image/png, image/gif, image/jpeg" class="upload__inputfile">
                    </label>
                  </div>
                </div>
                <div class="row mb-4">
                  <div class="col">
                    <div class="form-outline">
                      <label class="form-label" for="form3Example1">First name</label>
                      <input type="text" name="firstname" value="<?php echo $row['firstname'] ?>" required class="form-control" />
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-outline">
                      <label class="form-label" for="form3Example2">Last name</label>
                      <input type="text" name="lastname" value="<?php echo $row['lastname'] ?>" required class="form-control" />
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer" style="border-top:none">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <button class="btn btn-primary" type="submit">Update</a>
              </div>
            </form>
          </div>
          <hr>
          <div class="row mb-4">
            <form onsubmit="return updateUser(<?php echo $row['user_id'] ?>)">
              <div class="modal-body">
                <h6 class="mb-3" style="font-weight: bold;">Login Information</h6>
                <div class="form-outline mb-4">
                  <label class="form-label" for="form3Example3">Username</label>
                  <input type="text" name="username" value="<?php echo $row['username'] ?>" required class="form-control" />
                </div>
                <div class="row">
                  <div class="col">
                    <div class="form-outline">
                      <label class="form-label" for="form3Example4">Password</label>
                      <input type="password" name="password" class="form-control" />
                    </div>
                  </div>
                  <div class="col">
                    <div class="form-outline">
                      <label class="form-label" for="form3Example4">Confirm Password</label>
                      <input type="password" name="confirmpassword" class="form-control" />
                    </div>
                  </div>
                </div>
              </div>
              <div class="modal-footer" style="border-top:none">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                <button class="btn btn-primary" type="submit">Update</a>
              </div>
            </form>
          </div>
        </div>
      </div>
      <?php include 'footer.php' ?>
    </div>
  </div>
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../js/sb-admin-2.min.js"></script>
  <script>
    jQuery(document).ready(function () {
      ImgUpload();
    });

    function ImgUpload() {
      $('.upload__inputfile').each(function () {
        $(this).on('change', function (e) {
          var files = e.target.files;
          var reader = new FileReader();
          reader.onload = function (e) {
            document.getElementById('profile-photo').setAttribute('src', e.target.result);
          }
          reader.readAsDataURL(files[0]);

          var formData = new FormData();
          formData.append('action', 'updatephoto');
          formData.append("files", files[0]);

          $.ajax({
            url: "profile.php",
            type: "post",
            data: formData,
            contentType: false,
            cache: false,
            processData:false,
            success: function(data) {
              alert(data.message);
              window.location.reload();
            },
            error: function(error) {
              alert('Something went wrong.');
            }
          });
        });
      })
    }

    function updateUser(userId) {
      var firstname = document.getElementsByName("firstname")[0].value;
      var lastname = document.getElementsByName("lastname")[0].value;
      var username = document.getElementsByName("username")[0].value;
      var password = document.getElementsByName("password")[0].value;
      var confirmpassword = document.getElementsByName("confirmpassword")[0].value;

      $.ajax({
        url: "users.php",
        type: "post",
        data: {
          userId,
          action: 'updateuser',
          firstname,
          lastname,
          username,
          password,
          confirmpassword
        },
        success: function(data) {
          if (data.success === false) {
            alert(data.message);
          } else {
            alert(data.message);
            window.location.reload();
          }
        },
        error: function(error) {
          alert('Something went wrong.');
        }
      });
      return false;
    }
  </script>
</body>

</html>

<?php $conn->close() ?>