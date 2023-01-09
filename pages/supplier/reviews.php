<?php
  session_start();
  require_once "../../config.php";

  if (!isset($_SESSION['user_id'])) {
    header('Location: ../../login.php');
    die;
  }

  $user_id = $_SESSION['user_id'];
  $sql = "select * from rating where user_id_supplier=$user_id";
  $result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>E-Port :: Reviews</title>
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <link href="../../vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
  <style>
    .upload__inputfile {
      width: .1px;
      height: .1px;
      opacity: 0;
      overflow: hidden;
      position: absolute;
      z-index: -1;
    }
    .upload__btn {
      display: inline-block;
      font-weight: 600;
      color: #fff;
      text-align: center;
      min-width: 116px;
      padding: 5px;
      transition: all .3s ease;
      cursor: pointer;
      border: 2px solid;
      background-color: #4045ba;
      border-color: #4045ba;
      border-radius: 10px;
      line-height: 26px;
      font-size: 14px;
    }
    .upload__btn:hover {
      background-color: unset;
      color: #4045ba;
      transition: all .3s ease;
    }
    .upload__btn-box {
      margin-bottom: 10px;
    }
    .upload__img-wrap {
      display: flex;
      flex-wrap: wrap;
      margin: 0 -10px;
    }
    .upload__img-box {
      width: 162px;
      padding: 0 10px;
      margin-bottom: 12px;
    }
    .upload__img-close {
      width: 24px;
      height: 24px;
      border-radius: 50%;
      background-color: rgba(0, 0, 0, 0.5);
      position: absolute;
      top: 10px;
      right: 10px;
      text-align: center;
      line-height: 24px;
      z-index: 1;
      cursor: pointer;color: white;
    }
    .img-bg {
      background-repeat: no-repeat;
      background-position: center;
      background-size: cover;
      position: relative;
      padding-bottom: 100%;
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
            <h1 class="h3 mb-0 text-gray-800">Reviews</h1>
          </div>
          <div class="card shadow mb-4">
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Client Name</th>
                      <th>Message</th>
                      <th>Rating</th>
                      <th>Date Created</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    while ($row = $result->fetch_assoc()) {
                      $client_user_id = $row['user_id_client'];
                      $sql_user = "select * from user where user_id=$client_user_id";
                      $result_user = $conn->query($sql_user);
                      $user = $result_user->fetch_assoc();
                    ?>
                      <tr>
                        <td><?php echo $user['firstname'].' '.$user['lastname'] ?></td>
                        <td><?php echo $row['message'] ?></td>
                        <td>
                          <div class="stars">
                            <i class="fa fa-star <?php echo $row['rate'] >= 1 ? 'text-warning' : '' ?>"></i>
                            <i class="fa fa-star <?php echo $row['rate'] >= 2 ? 'text-warning' : '' ?>"></i>
                            <i class="fa fa-star <?php echo $row['rate'] >= 3 ? 'text-warning' : '' ?>"></i>
                            <i class="fa fa-star <?php echo $row['rate'] >= 4 ? 'text-warning' : '' ?>"></i>
                            <i class="fa fa-star <?php echo $row['rate'] >= 5 ? 'text-warning' : '' ?>"></i>
                          </div>
                        </td>
                        <td><?php echo date_format(date_create($row['created']), 'D, M j Y h:ia') ?></td>
                      </tr>
                    <?php } ?>
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
</body>

</html>

<?php $conn->close() ?>