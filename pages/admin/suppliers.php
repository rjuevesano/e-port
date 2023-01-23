<?php
  session_start();
  require_once "../../check_session.php";

  $sql = "select * from user where type='SUPPLIER'";
  $result = $conn->query($sql);

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['userId']) && isset($_POST['status'])) {
      $user_id = $_POST['userId'];
      $status = $_POST['status'];
      $sql = "update user set status='$status' where user_id=$user_id";

      // this will set all supplier's post status to pending or published
      $post_status = $status == 'APPROVED' ? 'PUBLISHED': 'PENDING';
      $sql_post = "update post set status='$post_status' where user_id=$user_id";
      $conn->query($sql_post);

      if ($conn->query($sql)) {
        echo json_encode(true);
      } else {
        echo json_encode(false);
      }
      die;
    }

    if (isset($_POST['action']) && $_POST['action'] == 'deletesupplier') {
    if (isset($_POST['userId'])) {
        $user_id = $_POST['userId'];

        // Prepare and bind
        $stmt = $conn->prepare("DELETE user, booking, comment, likes, message, post, rating
        FROM user 
        LEFT JOIN booking ON booking.user_id_supplier = user.user_id
        LEFT JOIN comment ON comment.user_id = user.user_id 
        LEFT JOIN likes ON likes.user_id = user.user_id 
        LEFT JOIN post ON post.user_id = user.user_id 
        LEFT JOIN rating ON rating.user_id_supplier = user.user_id
        LEFT JOIN message ON message.user_id_supplier = user.user_id

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
  <title>E-Port :: Suppliers</title>
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
            <h1 class="h3 mb-0 text-gray-800">Suppliers</h1>
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
                      <th>Status</th>
                      <th>Date Created</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      if ($result->num_rows) {
                        while ($row = $result->fetch_assoc()) {
                          $badge = 'badge-danger';
                          if ($row['status'] == 'APPROVED') {
                            $badge='badge-success';
                          }

                          $user_id = $row['user_id'];

                          $sql_booking = "select count(*) as total from booking where user_id_supplier=$user_id";
                          $result_booking = $conn->query($sql_booking);
                          $booking_counter = $result_booking->num_rows ? $result_booking->fetch_assoc()['total'] : 0;

                          $sql_post = "select count(*) as total from post where user_id=$user_id";
                          $result_post = $conn->query($sql_post);
                          $post_counter = $result_post->num_rows ? $result_post->fetch_assoc()['total'] : 0;

                          $sql_rating = "select * from rating where user_id_supplier=$user_id";
                          $result_rating = $conn->query($sql_rating);
                          $total_rating = 0;
                          if ($result_rating->num_rows){
                            while ($rating = $result_rating->fetch_assoc()) {
                              $total_rating +=  (int)$rating['rate'];
                            }
                          }
                          $total_rating = $result_rating->num_rows ? number_format((float)($total_rating / $result_rating->num_rows), 2, '.', '') : 0;
                    ?>
                      <tr>
                        <td>
                          <a href="#" data-toggle="modal" data-target="#supplierInfoModal<?php echo $row['user_id'] ?>"><?php echo $row['firstname'] . " " . $row['lastname'] ?></a>
                          <div class="modal fade" id="supplierInfoModal<?php echo $row['user_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                              <div class="modal-content">
                                <div class="modal-body">
                                  <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                  </button>
                                  <div class="card-body text-center">
                                    <div class="mt-3 mb-4">
                                      <img src="<?php echo $row['avatar'] ? "../supplier/uploads/".$row['avatar'] : '../../img/undraw_profile.svg' ?>" class="rounded-circle img-fluid" style="width: 100px; height:100px;" />
                                    </div>
                                    <h4 class="mb-2"><?php echo $row['firstname'] . " " . $row['lastname'] ?></h4>
                                    <p class="text-muted mb-1"><?php echo $row['mobile'] ?></p>
                                    <p class="text-muted mb-4"><?php echo $row['address'] ?></p>
                                    <div class="mb-4 pb-2">
                                      <a href="<?php echo $row['facebook_url'] ?>" target="_blank" class="btn btn-outline-primary btn-floating">
                                        <i class="fab fa-facebook-f fa-lg"></i>
                                      </a>
                                      <a href="<?php echo $row['portfolio_url'] ?>" target="_blank" class="btn btn-outline-primary btn-floating">
                                        <i class="fa fa-link fa-lg"></i>
                                      </a>
                                    </div>
                                    <div>
                                      <a href="../../download.php?file=<?php echo $row['file'] ?>" target="_blank" class="btn btn-primary">GENERATE CURRICULUM VITAE</a>
                                    </div>
                                    <div class="d-flex justify-content-between text-center mt-5 mb-2">
                                      <div>
                                        <p class="mb-2 h5"><?php echo $booking_counter ?></p>
                                        <p class="text-muted mb-0">Bookings</p>
                                      </div>
                                      <div class="px-3">
                                        <p class="mb-2 h5"><?php echo $post_counter ?></p>
                                        <p class="text-muted mb-0">Posts</p>
                                      </div>
                                      <div>
                                        <div class="mb-2 h5">
                                          <div class="stars">
                                            <i class="fa fa-star <?php echo $total_rating >= 1 ? 'text-warning' : 'text-gray-500' ?>"></i>
                                            <i class="fa fa-star <?php echo $total_rating >= 2 ? 'text-warning' : 'text-gray-500' ?>"></i>
                                            <i class="fa fa-star <?php echo $total_rating >= 3 ? 'text-warning' : 'text-gray-500' ?>"></i>
                                            <i class="fa fa-star <?php echo $total_rating >= 4 ? 'text-warning' : 'text-gray-500' ?>"></i>
                                            <i class="fa fa-star <?php echo $total_rating >= 5 ? 'text-warning' : 'text-gray-500' ?>"></i>
                                          </div>
                                        </div>
                                        <p class="text-muted mb-0">Reviews (Ratings)</p>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                          </div>
                        </td>
                        <td><?php echo $row['mobile'] ?></td>
                        <td><?php echo $row['address'] ?></td>
                        <td>
                        <?php if ($row['status'] !== 'APPROVED') { ?>
                          <a href="#" data-toggle="modal" data-target="#supplierStatusModal<?php echo $row['user_id'] ?>"><?php echo $row['status'] ?></a>
                          <div class="modal fade" id="supplierStatusModal<?php echo $row['user_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                              <div class="modal-content">
                                <div class="modal-body">
                                  <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                  </button>
                                  <div class="card-body text-center">
                                    <div class="mt-3 mb-4">
                                      <img src="../../img/undraw_profile.svg" class="rounded-circle img-fluid" style="width: 100px;" />
                                    </div>
                                    <h4 class="mb-2"><?php echo $row['firstname'] . " " . $row['lastname'] ?></h4>
                                    <p class="text-muted mb-1"><?php echo $row['mobile'] ?></p>
                                    <p class="text-muted mb-4"><?php echo $row['address'] ?></p>
                                    <div class="mb-4 pb-2">
                                      <a href="<?php echo $row['facebook_url'] ?>" target="_blank" class="btn btn-outline-primary btn-floating">
                                        <i class="fab fa-facebook-f fa-lg"></i>
                                      </a>
                                      <a href="<?php echo $row['portfolio_url'] ?>" target="_blank" class="btn btn-outline-primary btn-floating">
                                        <i class="fa fa-link fa-lg"></i>
                                      </a>
                                    </div>
                                    <div>
                                      <a href="../../download.php?file=<?php echo $row['file'] ?>" target="_blank" class="btn btn-primary">GENERATE CURRICULUM VITAE</a>
                                    </div>
                                </div>
                                <?php if ($row['status'] !== 'APPROVED') { ?>
                                <div class="modal-footer" style="justify-content: center;">
                                  <button class="btn btn-danger" type="button" data-dismiss="modal" onClick="rejectSupplier(<?php echo $row['user_id'] ?>)">Reject</a>
                                  <button class="btn btn-success" type="button" data-dismiss="modal" onClick="approveSupplier(<?php echo $row['user_id'] ?>)">Approve</a>
                                </div>
                                <?php } ?>
                            </div>
                          </div>
                        <?php } else echo '<span class="badge '.$badge.'">'.$row['status'].'</span>' ?>
                        </td>
                        <td><?php echo $row['created'] ?></td>
                        <td>
                          <a href="#" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deletePostModal<?php echo $user_id ?>">Delete</a>
                          <div class="modal fade" id="deletePostModal<?php echo $user_id ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="exampleModalLabel">Delete Supplier?</h5>
                                  <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                  </button>
                                </div>
                                <div class="modal-body">Are you sure you want to delete this supplier?</div>
                                <div class="modal-footer">
                                  <button class="btn btn-light" type="button" data-dismiss="modal">Cancel</button>
                                  <button class="btn btn-danger" type="button" data-dismiss="modal" onclick="deleteSupplier(<?php echo $user_id ?>)">Yes</a>
                                </div>
                              </div>
                            </div>
                          </div>
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
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <script src="../../js/demo/datatables-demo.js"></script>
  <script>
    function rejectSupplier(userId) {
      $.ajax({
        url: "suppliers.php",
        type: "post",
        data: {
          status: 'REJECTED',
          userId
        },
        success: function(data) {
          if (data === 'true') {
            window.location.reload();
          } else {
            alert('Something went wrong.');  
          }
        },
        error: function(error) {
          alert('Something went wrong.');
        }
      });
    }

    function approveSupplier(userId) {
      $.ajax({
        url: "suppliers.php",
        type: "post",
        data: {
          status: 'APPROVED',
          userId
        },
        success: function(data) {
          if (data === 'true') {
            window.location.reload();
          } else {
            alert('Something went wrong.');  
          }
        },
        error: function(error) {
          alert('Something went wrong.');
        }
      });
    }

    function deleteSupplier(userId) {
      $.ajax({
        url: "suppliers.php",
        type: "post",
        data: {
          userId,
          action: 'deletesupplier',
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