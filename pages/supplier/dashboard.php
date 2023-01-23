<?php
  session_start();
  require_once "../../check_session.php";

  $user_id = $_SESSION['user_id'];
  $sql_client = "select count(distinct user_id_client) as total from booking where user_id_supplier=$user_id";
  $result_client = $conn->query($sql_client);

  $sql_booking = "select count(*) as total from booking where user_id_supplier=$user_id";
  $result_booking = $conn->query($sql_booking);

  $sql_post = "select count(*) as total from post where user_id=$user_id";
  $result_post = $conn->query($sql_post);

  $sql_rating = "select * from rating where user_id_supplier=$user_id";
  $result_rating = $conn->query($sql_rating);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>E-Port :: Dashboard</title>
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
</head>

<body id="page-top">
  <div id="wrapper">
    <?php include 'sidebar.php' ?>
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content">
        <?php include 'topbar.php' ?>
        <div class="container-fluid">
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>
          </div>
          <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                        Clients</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $result_client->num_rows ? $result_client->fetch_assoc()['total'] : 0 ?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                        Bookings</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $result_booking->num_rows ? $result_booking->fetch_assoc()['total'] : 0 ?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-table fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                        Posts</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $result_post->num_rows ? $result_post->fetch_assoc()['total'] : 0 ?></div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-list fa-2x text-gray-300"></i>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-md-6 mb-4">
              <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                  <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                      <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Reviews (Ratings)</div>
                      <div class="h5 mb-0 font-weight-bold text-gray-800">
                      <?php
                        $total_rating = 0;
                        if ($result_rating->num_rows) {
                        while ($rating = $result_rating->fetch_assoc()) {
                          $total_rating += $rating['rate'];
                        }}

                        $total_rating = $result_rating->num_rows ? number_format((float)($total_rating / $result_rating->num_rows), 2, '.', '') : 0;
                      ?>
                      <?php echo str_replace(".00", "", $total_rating) ?>
                      </div>
                    </div>
                    <div class="col-auto">
                      <i class="fas fa-star fa-2x text-gray-300"></i>
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
  <script src="../../js/sb-admin-2.min.js"></script>
</body>

</html>

<?php $conn->close() ?>