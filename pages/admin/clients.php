<?php
  session_start();
  require_once "../../check_session.php";

  $sql = "select * from user where type='CLIENT'";
  $result = $conn->query($sql);

  if (isset($_POST['action']) && $_POST['action'] == 'deleteclient') {
    if (isset($_POST['userId'])) {
        $user_id = $_POST['userId'];

        // Prepare and bind
        $stmt = $conn->prepare("DELETE user, booking, comment, message, likes, post, rating
        FROM user 
        LEFT JOIN booking ON booking.user_id_client = user.user_id
        LEFT JOIN comment ON comment.user_id = user.user_id 
        LEFT JOIN likes ON likes.user_id = user.user_id 
        LEFT JOIN post ON post.user_id = user.user_id 
        LEFT JOIN rating ON rating.user_id_client = user.user_id
        LEFT JOIN message ON message.user_id_client = user.user_id
        WHERE user.user_id = ?");
        $stmt->bind_param("i", $user_id);

        // Execute
        $stmt->execute();

        json_response([
          'success' => true,
          'message' => 'Successfully deleted.'
        ]);

        $stmt->close();
        $conn->close();
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
  <title>E-Port :: Clients</title>
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
            <h1 class="h3 mb-0 text-gray-800">Clients</h1>
          </div>
          <div class="card shadow mb-4">
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Mobile</th>
                      <th>Address</th>
                      <th>Bookings</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      if ($result->num_rows) {
                      while ($row = $result->fetch_assoc()) {
                        $user_id_client = $row['user_id'];
                        $sql_booking = "select * from booking where user_id_client=$user_id_client";
                        $result_booking = $conn->query($sql_booking);
                        $total_booking = $result_booking->num_rows;
                    ?>
                    <tr>
                      <td><?php echo $row['firstname']." ".$row['lastname'] ?></td>
                      <td><?php echo $row['mobile'] ?></td>
                      <td><?php echo $row['address'] ?></td>
                      <td><?php echo $total_booking ?></td>
                      <td>
                        <a href="#" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deletePostModal<?php echo $user_id_client ?>">Delete</a>
                        <div class="modal fade" id="deletePostModal<?php echo $user_id_client ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Delete Client?</h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">×</span>
                                </button>
                              </div>
                              <div class="modal-body">Are you sure you want to delete this client?</div>
                              <div class="modal-footer">
                                <button class="btn btn-light" type="button" data-dismiss="modal">Cancel</button>
                                <button class="btn btn-danger" type="button" data-dismiss="modal" onclick="deleteClient(<?php echo $user_id_client ?>)">Yes</a>
                              </div>
                            </div>
                          </div>
                        </div>
                      </td>
                    </tr>
                    <?php }} ?>
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
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <script src="../../js/demo/datatables-demo.js"></script>
  <script>
    function deleteClient(userId) {
      $.ajax({
        url: "clients.php",
        type: "post",
        data: {
          userId,
          action: 'deleteclient',
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