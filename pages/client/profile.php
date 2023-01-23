<?php
  session_start();
  require_once "../../check_session.php";

  $user_id = $_SESSION['user_id'];
  $sql = "select * from user where user_id=$user_id";
  $results = $conn->query($sql);
  $row = $results->fetch_assoc();
  $_SESSION['user_avatar'] = $row['avatar'];

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'updatephoto') {
      $location = "uploads/".$row['avatar'];
      unlink($location);

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

    if (isset($_POST['action']) && $_POST['action'] == 'updateuser') {
      $firstname = validate($_POST['firstname']);
      $lastname = validate($_POST['lastname']);
      $mobile = validate($_POST['mobile']);
      $address = validate($_POST['address']);

      $sql = "update user set firstname='$firstname', lastname='$lastname', mobile='$mobile', address='$address' where user_id=$user_id";
      $conn->query($sql);
      json_response([
        'success' => true,
        'message' => 'Successfully updated.'
      ]);
    }

    if (isset($_POST['action']) && $_POST['action'] == 'updatelogin') {
      $username = validate($_POST['username']);
      $password = $_POST['password'];
      $confirmpassword = $_POST['confirmpassword'];

      if ($password !== $confirmpassword) {
        json_response([
          'success' => false,
          'message' => 'Password did not match.'
        ]);
      }

      if ($password) {
        $password = md5($_POST['password']);
        $sql = "update user set username='$username', password='$password' where user_id=$user_id";
      } else {
        $sql = "update user set username='$username' where user_id=$user_id";
      }
      $conn->query($sql);
      json_response([
        'success' => true,
        'message' => 'Successfully updated.'
      ]);
    }

    if (isset($_POST['action']) && $_POST['action'] == 'updatebooking') {
      $booking_id = $_POST['bookingId'];
      $status = $_POST['status'];

      $sql = "update booking set status='$status' where booking_id=$booking_id";
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
  <link href="../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
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
 .bg-white1 {
    background-color: #eaecf4!important;
}
  </style>
</head>

<body id="page-top">
  <div id="wrapper">
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content" style="min-height: 100vh;">
        <?php include 'topbar.php' ?>
        <div class="container-fluid" style="padding: 0; padding-top: 70px;">
          <div class="container" style="padding-top: 20px;">
            <div class="row">
              <div class="col-12">
                <div class="bg-white1">
                  <div class="card-body">
                    <div class="row">
                      <div class="col-12">
                        <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
                          <li class="nav-item">
                            <a class="nav-link active" id="personalInfo-tab" data-toggle="tab" href="#personalInfo" role="tab" aria-controls="personalInfo" aria-selected="true">Personal Information</a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link" id="booking-tab" data-toggle="tab" href="#booking" role="tab" aria-controls="booking" aria-selected="false">Manage Booking</a>
                          </li>
                          <li class="nav-item">
                            <a class="nav-link" id="settings-tab" data-toggle="tab" href="#settings" role="tab" aria-controls="settings" aria-selected="false">Settings</a>
                          </li>
                        </ul>
                        <div class="tab-content ml-1" id="myTabContent" style="min-height: 50vh;">
                          <div class="tab-pane fade show active" id="personalInfo" role="tabpanel" aria-labelledby="personalInfo-tab">
                            <div class="row">
                              <form onsubmit="return updateUser()">
                                <div class="modal-body">
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
                                  <div class="row mb-4">
                                    <div class="col">
                                      <div class="form-outline">
                                        <label class="form-label" for="form3Example1">Mobile</label>
                                        <input type="text" name="mobile" value="<?php echo $row['mobile'] ?>" required class="form-control" />
                                      </div>
                                    </div>
                                    <div class="col">
                                      <div class="form-outline">
                                        <label class="form-label" for="form3Example2">Address</label>
                                        <input type="text" name="address" value="<?php echo $row['address'] ?>" required class="form-control" />
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
                          <div class="tab-pane fade" id="booking" role="tabpanel" aria-labelledby="booking-tab">
                            <div class="table-responsive">
                              <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                  <tr>
                                    <th>Supplier Name</th>
                                    <th>Appoinment Date</th>
                                    <th>Note</th>
                                    <th>Status</th>
                                    <th>Date Created</th>
                                    <th>Action</th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <?php
                                    $sql_booking = "select * from booking where user_id_client=$user_id order by schedule_date asc";
                                    $result_booking = $conn->query($sql_booking);

                                    if ($result_booking->num_rows) {
                                    while ($booking = $result_booking->fetch_assoc()) {
                                      $user_id_supplier = $booking['user_id_supplier'];
                                      $sql_supplier = "select * from user where user_id=$user_id_supplier";
                                      $result_supplier = $conn->query($sql_supplier);
                                      $supplier = $result_supplier->fetch_assoc();

                                      if ($booking['status'] == 'PENDING') {
                                        $badge = 'badge-primary';
                                      } else if ($booking['status'] == 'ACCEPTED') {
                                        $badge='badge-info';
                                      } else if ($booking['status'] == 'COMPLETED') {
                                        $badge='badge-success';
                                      } else {
                                        $badge = 'badge-secondary';
                                      }
                                  ?>
                                  <tr>
                                    <td><a href="supplier.php?id=<?php echo $user_id_supplier ?>"><?php echo $supplier['firstname'].' '.$supplier['lastname'] ?></a></td>
                                    <td><?php echo date_format(date_create($booking['schedule_date']), 'm/d/Y h:ia') ?></td>
                                    <td><?php echo $booking['note'] ?></td>
                                    <td><span class="badge <?php echo $badge ?>"><?php echo $booking['status'] ?></span></td>
                                    <td><?php echo date_format(date_create($booking['created']), 'm/d/Y h:ia') ?></td>
                                    <td>
                                      <?php if ($booking['status'] == 'PENDING' or $booking['status'] == 'ACCEPTED') { ?>
                                      <a href="#" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#cancelBookingModal<?php echo $booking['booking_id'] ?>">Cancel</a>
                                      <div class="modal fade" id="cancelBookingModal<?php echo $booking['booking_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                          <div class="modal-content">
                                            <div class="modal-header">
                                              <h5 class="modal-title" id="exampleModalLabel">Cancel Booking?</h5>
                                              <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">×</span>
                                              </button>
                                            </div>
                                            <div class="modal-body">Are you sure you want to cancel this booking?</div>
                                            <div class="modal-footer">
                                              <button class="btn btn-light" type="button" data-dismiss="modal">Cancel</button>
                                              <button class="btn btn-danger" type="button" data-dismiss="modal" onclick="updateBooking(<?php echo $booking['booking_id'] ?>)">Yes</a>
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                      <?php } ?>
                                    </td>
                                  </tr>
                                  <?php }} ?>
                                </tbody>
                              </table>
                            </div>
                          </div>
                          <div class="tab-pane fade" id="settings" role="tabpanel" aria-labelledby="settings-tab">
                            <div class="row">
                              <form onsubmit="return updateLogin()">
                                <div class="modal-body">
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
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
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
  <script src="../../vendor/lightbox/fslightbox.js"></script>
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <script src="../../js/demo/datatables-demo.js"></script>
  <script src="../../js/client.js"></script>
  <script>
    jQuery(document).ready(function () {
      ImgUpload2();
    });

    function ImgUpload2() {
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
              alert('Successfully updated.');
            }
          });
        });
      })
    }

    function updateUser() {
      var firstname = document.getElementsByName("firstname")[0].value;
      var lastname = document.getElementsByName("lastname")[0].value;
      var mobile = document.getElementsByName("mobile")[0].value;
      var address = document.getElementsByName("address")[0].value;

      $.ajax({
        url: "profile.php",
        type: "post",
        data: {
          action: 'updateuser',
          firstname,
          lastname,
          mobile,
          address,
        },
        success: function(data) {
          alert(data.message);
          if (data.success) {
            window.location.reload();
          }
        },
        error: function(error) {
          alert('Something went wrong.');
        }
      });
      return false;
    }

    function updateLogin() {
      var username = document.getElementsByName("username")[0].value;
      var password = document.getElementsByName("password")[0].value;
      var confirmpassword = document.getElementsByName("confirmpassword")[0].value;

      $.ajax({
        url: "profile.php",
        type: "post",
        data: {
          action: 'updatelogin',
          username,
          password,
          confirmpassword
        },
        success: function(data) {
          alert(data.message);
          if (data.success) {
            window.location.reload();
          }
        },
        error: function(error) {
          alert('Something went wrong.');
        }
      });
      return false;
    }

    function updateBooking(bookingId) {
      $.ajax({
        url: "profile.php",
        type: "post",
        data: {
          action: 'updatebooking',
          bookingId,
          status: 'CANCELLED'
        },
        success: function(data) {
          alert(data.message);
          if (data.success) {
            window.location.reload();
          }
        },
        error: function(error) {
          alert('Something went wrong.');
        }
      });
    }
  </script>
</body>

</html>

<?php $conn->close() ?>