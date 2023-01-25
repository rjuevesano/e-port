<?php
  session_start();
  require_once "../../check_session.php";

  $current_user_id = $_SESSION['user_id'];
  $sql = "select * from user where type='ADMIN'";
  $result = $conn->query($sql);

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'adduser') {
      if (isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['username']) && isset($_POST['password']) && isset($_POST['confirmpassword'])) {
        $firstname = validate($_POST['firstname']);
        $lastname = validate($_POST['lastname']);
        $username = validate($_POST['username']);
        $password = $_POST['password'];
        $confirmpassword = $_POST['confirmpassword'];

        if ($password !== $confirmpassword) {
          json_response([
            'success' => false,
            'message' => 'Password did not match.'
          ]);
        }

        $password = md5($_POST['password']);
        $sql = "insert into user (username, password, type, status, firstname, lastname) values ('$username', '$password', 'ADMIN', 'ACTIVE', '$firstname', '$lastname')";
        $conn->query($sql);
        json_response([
          'success' => true,
          'message' => 'Successfully added.'
        ]);
      }
    }

    if (isset($_POST['action']) && $_POST['action'] == 'updateuser') {
      if (isset($_POST['userId']) && isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['username'])) { 
        $user_id = $_POST['userId'];
        $firstname = validate($_POST['firstname']);
        $lastname = validate($_POST['lastname']);
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
          $sql = "update user set username='$username', password='$password', firstname='$firstname', lastname='$lastname' where user_id=$user_id";
        } else {
          $sql = "update user set username='$username', firstname='$firstname', lastname='$lastname' where user_id=$user_id";
        }
        $conn->query($sql);
        json_response([
          'success' => true,
          'message' => 'Successfully updated.'
        ]);
      }
    }

    if (isset($_POST['action']) && $_POST['action'] == 'deleteuser') {
      if (isset($_POST['userId'])) {
        $user_id = $_POST['userId'];
        $sql = "delete from user where user_id=$user_id";
        $conn->query($sql);
        json_response([
          'success' => true,
          'message' => 'Successfully deleted.'
        ]);
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
  <title>E-Port :: Admin Users</title>
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>

<body id="page-top">
  <div id="wrapper">
    <?php include 'sidebar.php' ?>
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <?php include 'topbar.php' ?>
        <div class="container-fluid">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Admin Users</h1>
            <a href="#" class="btn btn-primary btn-icon-split" data-toggle="modal" data-target="#addUserModal">
              <span class="icon text-white-50">
                <i class="fas fa-user"></i>
              </span>
              <span class="text">Add User</span>
            </a>
          </div>
          <div class="card shadow mb-4">
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Username</th>
                      <th>Password</th>
                      <th>Date Created</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      if ($result->num_rows) {
                        while ($row = $result->fetch_assoc()) {
                    ?>
                      <tr>
                        <td>
                          <div>
                            <a href="#" data-toggle="modal" data-target="#userInfoModal<?php echo $row['user_id'] ?>"><?php echo $row['firstname'] . " " . $row['lastname'] ?></a>
                            <div class="modal fade" id="userInfoModal<?php echo $row['user_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                              <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                  <div class="modal-body">
                                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                      <span aria-hidden="true">×</span>
                                    </button>
                                    <form onsubmit="return updateUser(<?php echo $row['user_id'] ?>)">
                                      <div class="modal-body">
                                        <div class="row mb-4">
                                          <div class="col">
                                            <div class="form-outline">
                                              <label class="form-label" for="form3Example1">First name</label>
                                              <input type="text" name="firstname<?php echo $row['user_id'] ?>" required class="form-control" value="<?php echo $row['firstname'] ?>" />
                                            </div>
                                          </div>
                                          <div class="col">
                                            <div class="form-outline">
                                              <label class="form-label" for="form3Example2">Last name</label>
                                              <input type="text" name="lastname<?php echo $row['user_id'] ?>" required class="form-control" value="<?php echo $row['lastname'] ?>" />
                                            </div>
                                          </div>
                                        </div>
                                        <div class="form-outline mb-4">
                                          <label class="form-label" for="form3Example3">Username</label>
                                          <input type="text" name="username<?php echo $row['user_id'] ?>" required class="form-control" value="<?php echo $row['username'] ?>" />
                                        </div>
                                        <div class="row mb-4">
                                          <div class="col">
                                            <div class="form-outline">
                                              <label class="form-label" for="form3Example4">Password</label>
                                              <input type="password" name="password<?php echo $row['user_id'] ?>" class="form-control" />
                                            </div>
                                          </div>
                                          <div class="col">
                                            <div class="form-outline">
                                              <label class="form-label" for="form3Example4">Confirm Password</label>
                                              <input type="password" name="confirmpassword<?php echo $row['user_id'] ?>" class="form-control" />
                                            </div>
                                          </div>
                                        </div>
                                      </div>
                                      <div class="modal-footer">
                                        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                                        <button class="btn btn-primary" type="submit">Update</a>
                                      </div>
                                    </form>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </td>
                        <td><?php echo $row['username'] ?></td>
                        <td>****</td>
                        <td><?php echo $row['created'] ?></td>
                        <td>
                          <?php if ($current_user_id != $row['user_id']) { ?>
                          <a href="#" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deletUserModal<?php echo $row['user_id'] ?>">Delete</a>
                          <div class="modal fade" id="deletUserModal<?php echo $row['user_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="exampleModalLabel">Delete User?</h5>
                                  <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                  </button>
                                </div>
                                <div class="modal-body">Are you sure you want to delete this user?</div>
                                <div class="modal-footer">
                                  <button class="btn btn-light" type="button" data-dismiss="modal">Cancel</button>
                                  <button class="btn btn-danger" type="button" data-dismiss="modal" onclick="deleteUser(<?php echo $row['user_id'] ?>)">Yes</a>
                                </div>
                              </div>
                            </div>
                          </div>
                          <?php } ?>
                        </td>
                      </tr>
                    <?php
                        }
                      }
                    ?>
                  </tbody>
                </table>
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
  <div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add User</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form onsubmit="return addUser()">
          <div class="modal-body">
            <div class="row mb-4">
              <div class="col">
                <div class="form-outline">
                  <label class="form-label" for="form3Example1">First name</label>
                  <input type="text" name="firstname" required class="form-control" />
                </div>
              </div>
              <div class="col">
                <div class="form-outline">
                  <label class="form-label" for="form3Example2">Last name</label>
                  <input type="text" name="lastname" required class="form-control" />
                </div>
              </div>
            </div>
            <div class="form-outline mb-4">
              <label class="form-label" for="form3Example3">Username</label>
              <input type="text" name="username" required class="form-control" />
            </div>
            <div class="row mb-4">
              <div class="col">
                <div class="form-outline">
                  <label class="form-label" for="form3Example4">Password</label>
                  <input type="password" name="password" required class="form-control" />
                </div>
              </div>
              <div class="col">
                <div class="form-outline">
                  <label class="form-label" for="form3Example4">Confirm Password</label>
                  <input type="password" name="confirmpassword" required class="form-control" />
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <button class="btn btn-primary" type="submit">Submit</a>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <script src="../../js/demo/datatables-demo.js"></script>
  <script>
    function addUser() {
      var firstname = document.getElementsByName("firstname")[0].value;
      var lastname = document.getElementsByName("lastname")[0].value;
      var username = document.getElementsByName("username")[0].value;
      var password = document.getElementsByName("password")[0].value;
      var confirmpassword = document.getElementsByName("confirmpassword")[0].value;

      $.ajax({
        url: "users.php",
        type: "post",
        data: {
          action: 'adduser',
          firstname,
          lastname,
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

    function updateUser(userId) {
      var firstname = document.getElementsByName("firstname"+userId)[0].value;
      var lastname = document.getElementsByName("lastname"+userId)[0].value;
      var username = document.getElementsByName("username"+userId)[0].value;
      var password = document.getElementsByName("password"+userId)[0].value;
      var confirmpassword = document.getElementsByName("confirmpassword"+userId)[0].value;

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

    function deleteUser(userId) {
      $.ajax({
        url: "users.php",
        type: "post",
        data: {
          userId,
          action: 'deleteuser',
        },
        success: function(data) {
          alert(data.message);
          window.location.reload();
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