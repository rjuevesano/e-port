<?php
  session_start();
  require_once "../../config.php";

  if (!isset($_SESSION['user_id'])) {
    header('Location: ../../login.php');
    die;
  }

  $user_id = $_SESSION['user_id'];
  $sql = "select * from booking where user_id_supplier=$user_id";
  $result = $conn->query($sql);

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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
            <button class="btn btn-primary btn-icon-split" onclick="toggleView()">
              <span class="icon text-white-50">
                <i class="fas fa-calendar"></i>
              </span>
              <span class="text" id="calendar-text">Table View</span>
            </button>
          </div>
          <div class="card shadow mb-4" id="table-view" style="display:none">
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Client Name</th>
                      <th>Appoinment Date</th>
                      <th>Note</th>
                      <th>Status</th>
                      <th>Date Created</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      while ($row = $result->fetch_assoc()) {
                        $user_id_client = $row['user_id_client'];
                        $sql_client = "select * from user where user_id=$user_id_client";
                        $result_client = $conn->query($sql_client);
                        $row_client = $result_client->fetch_assoc();
                        
                        if ($row['status'] == 'PENDING') {
                          $badge = 'badge-primary';
                        } else if ($row['status'] == 'ACCEPTED') {
                          $badge='badge-info';
                        } else if ($row['status'] == 'COMPLETED') {
                          $badge='badge-success';
                        } else {
                          $badge = 'badge-secondary';
                        }
                    ?>
                    <tr>
                      <td>
                        <a href="#" data-toggle="modal" data-target="#clientInfoModal<?php echo $row['booking_id'] ?>"><?php echo $row_client['firstname']." ".$row_client['lastname'] ?></a>
                        <div class="modal fade" id="clientInfoModal<?php echo $row['booking_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <div class="modal-body">
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">×</span>
                                </button>
                                <div class="card-body text-center">
                                  <div class="mt-3 mb-4">
                                    <img src="<?php echo $row_client['avatar'] ? "../client/uploads/".$row_client['avatar'] : '../../img/undraw_profile.svg' ?>" class="rounded-circle img-fluid" style="width: 100px;" />
                                  </div>
                                  <h4 class="mb-2"><?php echo $row_client['firstname'] . " " . $row_client['lastname'] ?></h4>
                                  <p class="text-muted mb-1"><?php echo $row_client['mobile'] ?></p>
                                  <p class="text-muted mb-4"><?php echo $row_client['address'] ?></p>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>
                      </td>
                      <td><?php echo date_format(date_create($row['schedule_date']), 'm/d/Y h:ia') ?></td>
                      <td><?php echo $row['note'] ?></td>
                      <td><span class="badge <?php echo $badge ?>"><?php echo $row['status'] ?></span></td>
                      <td><?php echo $row['created'] ?></td>
                      <td>
                        <?php if ($row['status'] == 'PENDING') { ?>
                        <a href="#" class="btn btn-info btn-sm" data-toggle="modal" data-target="#acceptBookingModal<?php echo $row['booking_id'] ?>">Accept</a>
                        <div class="modal fade" id="acceptBookingModal<?php echo $row['booking_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Accept Booking?</h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">×</span>
                                </button>
                              </div>
                              <div class="modal-body">Are you sure you want to accept this booking?</div>
                              <div class="modal-footer">
                                <button class="btn btn-light" type="button" data-dismiss="modal">Cancel</button>
                                <button class="btn btn-info" type="button" onclick="updateBooking(<?php echo $row['booking_id'] ?>, 'ACCEPTED')">Yes</a>
                              </div>
                            </div>
                          </div>
                        </div>
                        <a href="#" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#cancelBookingModal<?php echo $row['booking_id'] ?>">Cancel</a>
                        <div class="modal fade" id="cancelBookingModal<?php echo $row['booking_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                <button class="btn btn-danger" type="button" onclick="updateBooking(<?php echo $row['booking_id'] ?>, 'CANCELLED')">Yes</a>
                              </div>
                            </div>
                          </div>
                        </div>
                        <?php } ?>
                        <?php if ($row['status'] == 'ACCEPTED') { ?>
                        <a href="#" class="btn btn-success btn-sm" data-toggle="modal" data-target="#completeBookingModal<?php echo $row['booking_id'] ?>">Complete</a>
                        <div class="modal fade" id="completeBookingModal<?php echo $row['booking_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Complete Booking?</h5>
                                <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                  <span aria-hidden="true">×</span>
                                </button>
                              </div>
                              <div class="modal-body">Are you sure you want to complete this booking?</div>
                              <div class="modal-footer">
                                <button class="btn btn-light" type="button" data-dismiss="modal">Cancel</button>
                                <button class="btn btn-success" type="button" data-dismiss="modal" onclick="updateBooking(<?php echo $row['booking_id'] ?>, 'COMPLETED')">Yes</a>
                              </div>
                            </div>
                          </div>
                        </div>
                        <a href="#" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#cancelBookingModal<?php echo $row['booking_id'] ?>">Cancel</a>
                        <div class="modal fade" id="cancelBookingModal<?php echo $row['booking_id'] ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                                <button class="btn btn-danger" type="button" data-dismiss="modal" onclick="updateBooking(<?php echo $row['booking_id'] ?>, 'CANCELLED')">Yes</a>
                              </div>
                            </div>
                          </div>
                        </div>
                        <?php } ?>
                      </td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
          <div class="card shadow mb-4 p-3" id="calendar-view" >
            <div id="calendar"></div>
          </div>
        </div>
      </div>
      <?php include 'footer.php' ?>
    </div>
  </div>
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>
  <div class="modal fade" id="viewBookingModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-body">
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
          <div class="card-body text-center">
            <div class="mt-3 mb-4">
              <img src="" id="viewbooking-img" class="rounded-circle img-fluid" style="width: 100px;" />
            </div>
            <h4 class="mb-2" id="viewbooking-name"></h4>
            <p class="text-muted mb-1" id="viewbooking-mobile"></p>
            <p class="text-muted mb-4" id="viewbooking-address"></p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script src="../../vendor/jquery/jquery.min.js"></script>
  <script src="../../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="../../vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src='../../vendor/fullcalendar/dist/index.global.js'></script>
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="../../vendor/datatables/dataTables.bootstrap4.min.js"></script>
  <script src="../../js/demo/datatables-demo.js"></script>
  <script>
    var events = [];
    <?php
      $sql_calendar = "select * from booking where user_id_supplier=$user_id";
      $result_calendar = $conn->query($sql_calendar);
      
      while ($calendar = $result_calendar->fetch_assoc()) {
        $user_id_client = $calendar['user_id_client'];
        $sql_client = "select * from user where user_id=$user_id_client";
        $result_client = $conn->query($sql_client);
        $row_client = $result_client->fetch_assoc();

        if ($calendar['status'] == 'PENDING' or $calendar['status'] =='ACCEPTED') {
    ?>
    events.push({
      bookingId: '<?php echo $calendar['booking_id'] ?>',
      avatar: '<?php echo "../client/uploads/".$row_client['avatar'] ?>',
      title: '<?php echo $calendar['note'] ?>',
      name: '<?php echo $row_client['firstname'].' '.$row_client['lastname'] ?>',
      mobile: '<?php echo $row_client['mobile'] ?>',
      address: '<?php echo $row_client['address'] ?>',
      description: '<?php echo $calendar['note'] ?>',
      start: '<?php echo $calendar['schedule_date'] ?>'
    })
    <?php
        }
      }
    ?>

    window.view = 'calendar-view';
    document.addEventListener('DOMContentLoaded', function() {
      var calendarEl = document.getElementById('calendar');
      var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        events,
        eventClick: function(info) {
          document.getElementById('viewbooking-img').setAttribute("src", info.event.extendedProps.avatar);
          document.getElementById('viewbooking-name').innerText = info.event.extendedProps.name;
          document.getElementById('viewbooking-mobile').innerText = info.event.extendedProps.mobile;
          document.getElementById('viewbooking-address').innerText = info.event.extendedProps.address;
          $("#viewBookingModal").modal("show");
        },
        headerToolbar: {
          end: 'today prev,next dayGridMonth,timeGridWeek,timeGridDay'
        },
      });
      calendar.render();
    });

    function toggleView() {
      if (window.view=='table-view') {
        window.view='calendar-view';
        document.getElementById('calendar-text').innerText = 'Table View';
        document.getElementById("table-view").style.display = 'none';
        document.getElementById("calendar-view").style.display = 'block';
      } else {
        window.view='table-view';
        document.getElementById('calendar-text').innerText = 'Calendar View';
        document.getElementById("table-view").style.display = 'block';
        document.getElementById("calendar-view").style.display = 'none';
      }
    }

    function updateBooking(bookingId, status) {
      $.ajax({
        url: "bookings.php",
        type: "post",
        data: {
          action: 'updatebooking',
          bookingId,
          status
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