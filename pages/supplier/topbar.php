<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
  <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
    <i class="fa fa-bars"></i>
  </button>
  <div class="d-flex flex-row justify-content-center" style="width: 100%;">
    <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
      <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
      </symbol>
      <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
        <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
      </symbol>
      <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
      </symbol>
    </svg>
    <?php
      $current_user_id = $_SESSION['user_id'];
      $sql_check = "select status from user where user_id=$current_user_id";
      $row_check = $conn->query($sql_check);
      $status = $row_check->num_rows ? $row_check->fetch_assoc()['status'] : '';

      if ($status == 'PENDING') {
    ?>
    <div class="alert alert-info mt-3 d-flex flex-row" role="alert">
      <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:"><use xlink:href="#info-fill"/></svg>
      <span class="ml-2">Your account is still pending and waiting for approval. You can still add a post but can't be published.</span>
    </div>
    <?php } else if ($status == 'REJECTED') { ?>
    <div class="alert alert-danger mt-3 d-flex flex-row" role="alert">
      <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
      <span class="ml-2"><strong>Oops!</strong> Your account is rejected! Please contact the administrator.</span>
    </div>
    <?php } ?>
  </div>
  <ul class="navbar-nav ml-auto">
    <li class="nav-item dropdown no-arrow mx-1">
      <?php
        $current_user_id = $_SESSION['user_id'];
        $sql_notification = "select a.*, b.* from notification as a inner join message as b on a.message_id=b.message_id and a.is_read=false and b.user_id_supplier=$current_user_id and b.sender='CLIENT'";
        $result_notification = $conn->query($sql_notification);
      ?>
      <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-envelope fa-fw"></i>
        <?php if ($result_notification->num_rows) { ?>
        <span class="badge badge-danger badge-counter"><?php echo $result_notification->num_rows ?></span>
        <?php } ?>
      </a>
      <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="messagesDropdown">
        <h6 class="dropdown-header pl-2 pr-2">Message Center</h6>
        <?php
          if ($result_notification->num_rows) {
          while($row_notification = $result_notification->fetch_assoc()) {
            $user_id_client = $row_notification['user_id_client'];
            $sql_user = "select * from user where user_id=$user_id_client";
            $result_user = $conn->query($sql_user);
            $row_user = $result_user->fetch_assoc();
            $avatar = $row_user['avatar'] ? '../client/uploads/'.$row_user['avatar'] : '../../img/undraw_profile.svg';
        ?>
        <a class="dropdown-item d-flex align-items-center pl-2 pr-2" href="messages.php?client_id=<?php echo $row_notification['user_id_client'] ?>&notification_id=<?php echo $row_notification['notification_id'] ?>">
          <div class="dropdown-list-image mr-3">
            <img class="rounded-circle" src="<?php echo $avatar ?>" alt="...">
          </div>
          <div class="font-weight-bold">
            <div class="text-truncate"><?php echo $row_notification['text'] ?></div>
            <div class="small text-gray-500"><?php echo $row_user['firstname']." ".$row_user['lastname'] ?> · <?php echo getDateTimeDifferenceString($row_notification['created']) ?></div>
          </div>
        </a>
        <?php }} else { echo "<div class='p-2'>No recent messages.</div>"; } ?>
        <a class="dropdown-item text-center small text-gray" href="messages.php">Read More Messages</a>
      </div>
    </li>
    <li class="nav-item dropdown show no-arrow">
      <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <img class="img-profile rounded-circle" style="border: 1px solid" src="<?php echo $_SESSION['user_avatar'] ? "uploads/".$_SESSION['user_avatar'] : '../../img/undraw_profile.svg' ?>">
      </a>
      <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
        <a class="dropdown-item" href="profile.php">
          <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
          Profile
        </a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
          <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
          Logout
        </a>
      </div>
    </li>
  </ul>
</nav>
<div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
      <div class="modal-footer">
        <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
        <a class="btn btn-primary" href="../../logout.php">Logout</a>
      </div>
    </div>
  </div>
</div>