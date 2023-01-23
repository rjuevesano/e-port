<?php
  session_start();
  require_once "../../check_session.php";

  $sql = "select * from booking";
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
            <h1 class="h3 mb-0 text-gray-800">Bookings</h1>
          </div>
          <div class="card shadow mb-4">
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Client Name</th>
                      <th>Supplier Name</th>
                      <th>Appoinment Date</th>
                      <th>Note</th>
                      <th>Status</th>
                      <th>Date Created</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      if ($result->num_rows) {
                      while ($row = $result->fetch_assoc()) {
                        $user_id_client = $row['user_id_client'];
                        $sql_client = "select * from user where user_id=$user_id_client";
                        $result_client = $conn->query($sql_client);
                        $row_client = $result_client->fetch_assoc();

                        $user_id_supplier = $row['user_id_supplier'];
                        $sql_supplier = "select * from user where user_id=$user_id_supplier";
                        $result_supplier = $conn->query($sql_supplier);
                        $row_supplier = $result_supplier->fetch_assoc();
                        
                        if ($row['status'] == 'PENDING') {
                          $badge = 'badge-primary';
                        } else if ($row['status'] == 'COMPLETED') {
                          $badge='badge-success';
                        } else {
                          $badge = 'badge-secondary';
                        }
                    ?>
                    <tr>
                      <td><?php echo $row_client['firstname']." ".$row_client['lastname'] ?></td>
                      <td><?php echo $row_supplier['firstname']." ". $row_supplier['lastname'] ?></td>
                      <td><?php echo date_format(date_create($row['schedule_date']), 'm/d/Y h:ia') ?></td>
                      <td><?php echo $row['note'] ?></td>
                      <td><span class="badge <?php echo $badge ?>"><?php echo $row['status'] ?></span></td>
                      <td><?php echo $row['created'] ?></td>
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
</body>

</html>

<?php $conn->close() ?>