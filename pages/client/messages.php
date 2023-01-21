<?php
  session_start();
  require_once "../../config.php";

  if (!isset($_SESSION['user_id'])) {
    header('Location: ../../login.php');
    die;
  }

  $supplier_id = '';
  if (isset($_GET['supplier_id'])) {
    $supplier_id = $_GET['supplier_id'];
  }

  $current_user_id = $_SESSION['user_id'];
  $sql = "select * from message where user_id_client=$current_user_id and is_main=1 order by updated desc";
  $result = $conn->query($sql);

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'addmessage') {
      $user_id = $_POST['supplier_id'];
      $message = $_POST['message'];

      $sql_check = "select count(*) as total from message where user_id_client=$current_user_id and user_id_supplier=$user_id and is_main=true";
      $result_check = $conn->query($sql_check);
      $total = $result_check->fetch_assoc()['total'];

      if ($total) {
        $sql_update = "update message set updated=now() where user_id_client=$current_user_id and user_id_supplier=$user_id and is_main=true";
        $conn->query($sql_update);

        $sql = "insert into message (user_id_client, user_id_supplier, is_main, sender, text) values('$current_user_id', '$user_id', false, 'CLIENT', '$message')";
        $conn->query($sql);
      } else {
        $sql = "insert into message (user_id_client, user_id_supplier, is_main, sender, text) values('$current_user_id', '$user_id', true, 'CLIENT', '$message')";
        $conn->query($sql);
      }
      
      json_response([
        'success' => true,
        'message' => 'Message successfully sent!'
      ]);
    }

    if (isset($_POST['action']) && $_POST['action'] == 'deletemessage') {
      $user_id_client = $_POST['user_id_client'];
      $user_id_supplier = $_POST['user_id_supplier'];

      $sql = "delete from message where user_id_client=$user_id_client and user_id_supplier=$user_id_supplier";
      $conn->query($sql);
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

  <title>E-Port :: Messages</title>
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <style>
    .container {
      max-width: 1170px;
      margin: auto;
    }

    .inbox_people {
      background: #f8f8f8 none repeat scroll 0 0;
      float: left;
      overflow: hidden;
      width: 30%;
      border-right: 1px solid #c4c4c4;
    }

    .inbox_msg {
      border: 1px solid #c4c4c4;
      clear: both;
      overflow: hidden;
    }

    .top_spac {
      margin: 20px 0 0;
    }


    .recent_heading {
      float: left;
      width: 30%;
    }

    .srch_bar {
      display: inline-block;
      text-align: right;
      width: 70%;
    }

    .headind_srch {
      padding: 10px 29px 10px 20px;
      overflow: hidden;
      border-bottom: 1px solid #c4c4c4;
    }

    .recent_heading h4 {
      color: #05728f;
      font-size: 21px;
      margin: auto;
    }

    .srch_bar input {
      border: 1px solid #cdcdcd;
      border-width: 0 0 1px 0;
      width: 80%;
      padding: 2px 0 4px 6px;
      background: none;
    }

    .srch_bar .input-group-addon button {
      background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
      border: medium none;
      padding: 0;
      color: #707070;
      font-size: 18px;
    }

    .srch_bar .input-group-addon {
      margin: 0 0 0 -27px;
    }

    .chat_ib h5 {
      font-size: 15px;
      color: #464646;
      margin: 0 0 8px 0;
    }

    .chat_ib h5 span {
      font-size: 13px;
      float: right;
    }

    .chat_ib p {
      font-size: 14px;
      color: #989898;
      margin: auto
    }

    .chat_img {
      float: left;
      width: 11%;
    }

    .chat_ib {
      float: left;
      padding: 0 0 0 15px;
      width: 88%;
    }

    .chat_people {
      overflow: hidden;
      clear: both;
    }

    .chat_list {
      border-bottom: 1px solid #c4c4c4;
      margin: 0;
      padding: 18px 16px 10px;
    }

    .inbox_chat {
      height: 550px;
      overflow-y: scroll;
    }

    .active_chat {
      background: #ebebeb;
    }

    .incoming_msg {
      overflow: hidden;
      margin: 26px 0 26px;
    }

    .incoming_msg_img {
      display: inline-block;
      width: 6%;
    }

    .received_msg {
      display: inline-block;
      padding: 0 0 0 10px;
      vertical-align: top;
      width: 92%;
    }

    .received_withd_msg p {
      background: #ebebeb none repeat scroll 0 0;
      border-radius: 3px;
      color: #646464;
      font-size: 14px;
      margin: 0;
      padding: 5px 10px 5px 12px;
      width: 100%;
    }

    .time_date {
      color: #747474;
      display: block;
      font-size: 12px;
      margin: 8px 0 0;
    }

    .received_withd_msg {
      width: 57%;
    }

    .mesgs {
      float: left;
      padding: 30px 0 0 25px;
      width: 70%;
    }

    .sent_msg p {
      background: #05728f none repeat scroll 0 0;
      border-radius: 3px;
      font-size: 14px;
      margin: 0;
      color: #fff;
      padding: 5px 10px 5px 12px;
      width: 100%;
    }

    .outgoing_msg {
      overflow: hidden;
      margin: 26px 0 26px;
    }

    .sent_msg {
      float: right;
      width: 46%;
    }

    .input_msg_write input {
      background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
      border: medium none;
      color: #4c4c4c;
      font-size: 15px;
      min-height: 48px;
      width: 100%;
    }

    .type_msg {
      border-top: 1px solid #c4c4c4;
      position: relative;
      margin-right: 15px;
    }

    .write_msg {
      outline: none;
    }

    .msg_send_btn {
      background: #05728f none repeat scroll 0 0;
      border: medium none;
      border-radius: 50%;
      color: #fff;
      cursor: pointer;
      font-size: 17px;
      height: 33px;
      position: absolute;
      right: 0;
      top: 11px;
      width: 33px;
    }

    .messaging {
      padding: 0 0 50px 0;
    }

    .msg_history {
      height: 516px;
      overflow-y: auto;
      padding-right: 25px;
    }
  </style>
</head>

<body id="page-top">
  <div id="wrapper">
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content" style="min-height: 100vh;">
        <?php include 'topbar.php' ?>
        <div class="container-fluid" style="padding: 0; padding-top: 70px;">
          <div class="messaging">
            <div class="inbox_msg">
              <div class="inbox_people">
                <div class="headind_srch">
                  <div class="recent_heading">
                    <h4>Messages</h4>
                  </div>
                </div>
                <div class="inbox_chat">
                  <?php
                    while($row = $result->fetch_assoc()) {
                      $user_id_client = $row['user_id_client'];
                      $sql_user_client = "select * from user where user_id=$user_id_client";
                      $result_user_client = $conn->query($sql_user_client);
                      $row_user_client = $result_user_client->fetch_assoc();

                      $user_id_supplier = $row['user_id_supplier'];
                      $sql_user_supplier = "select * from user where user_id=$user_id_supplier";
                      $result_user_supplier = $conn->query($sql_user_supplier);
                      $row_user_supplier = $result_user_supplier->fetch_assoc();

                      $sql_latest_message = "select * from message where user_id_client=$user_id_client and user_id_supplier=$user_id_supplier order by created desc";
                      $result_latest_message = $conn->query($sql_latest_message);
                      $row_latest_message = $result_latest_message->fetch_assoc();

                      $avatar = "../../img/undraw_profile.svg";
                      if ($row_user_supplier['avatar']) {
                        $avatar = "../supplier/uploads/".$row_user_supplier['avatar'];
                      }

                      if ($row_latest_message['sender'] == 'CLIENT') {
                        $sender = $row_user_client['firstname']." ".$row_user_client['lastname'];
                      } else {
                        $sender = $row_user_supplier['firstname']." ".$row_user_supplier['lastname'];
                      }
                  ?>
                  <div class="chat_list <?php echo $user_id_supplier == $supplier_id ? 'active_chat': ''  ?>">
                    <a href="messages.php?supplier_id=<?php echo $user_id_supplier ?>" style="text-decoration: none;">
                      <div class="chat_people">
                        <div class="chat_img">
                          <img src="<?php echo $avatar ?>" alt="" style="width: 40px; height: 40px; border-radius: 100%;"/>
                        </div>
                        <div class="chat_ib">
                          <h5>
                            <?php echo $row_user_supplier['firstname']." ".$row_user_supplier['lastname'] ?>
                            <span class="chat_date"><?php echo date_format(date_create($row['updated']), 'D, M j Y h:ia') ?></span>
                          </h5>
                          <p><?php echo "<strong>".$sender.":</strong> <i>".$row_latest_message['text']."</i>" ?></p>
                          <a href="#!" onclick="deleteMessage(<?php echo $user_id_client ?>, <?php echo $user_id_supplier ?>)">Delete conversation</a>
                        </div>
                      </div>
                    </a>
                  </div>
                  <?php } ?>
                </div>
              </div>
              <div class="mesgs">
                <?php
                  if ($supplier_id) {
                ?>
                <div class="msg_history">
                  <?php
                    $sql_messages = "select * from message where user_id_client=$current_user_id and user_id_supplier=$supplier_id order by created asc";
                    $result_messages = $conn->query($sql_messages);

                    while($row_messages=$result_messages->fetch_assoc()) {
                      $user_id_client = $row_messages['user_id_client'];
                      $sql_user_client = "select * from user where user_id=$user_id_client";
                      $result_user_client = $conn->query($sql_user_client);
                      $row_user_client = $result_user_client->fetch_assoc();

                      $user_id_supplier = $row_messages['user_id_supplier'];
                      $sql_user_supplier = "select * from user where user_id=$user_id_supplier";
                      $result_user_supplier = $conn->query($sql_user_supplier);
                      $row_user_supplier = $result_user_supplier->fetch_assoc();
                      
                      $avatar = $row_user_supplier['avatar'] ? "../supplier/uploads/".$row_user_supplier['avatar'] : '../../img/undraw_profile.svg';

                      if ($row_messages['sender'] == 'CLIENT') {
                  ?>
                  <div class="outgoing_msg">
                    <div class="sent_msg">
                      <p><?php echo $row_messages['text'] ?></p>
                      <span class="time_date"><?php echo date_format(date_create($row_messages['created']), 'D, M j Y h:ia') ?></span> </div>
                    </div>
                  <?php } else { ?>
                    <div class="incoming_msg">
                      <div class="incoming_msg_img"> <img src="<?php echo $avatar ?>" alt="" style="width: 40px; height: 40px; border-radius: 100%;"/> </div>
                      <div class="received_msg">
                        <div class="received_withd_msg">
                          <p><?php echo $row_messages['text'] ?></p>
                          <span class="time_date"><?php echo date_format(date_create($row_messages['created']), 'D, M j Y h:ia') ?></span>
                        </div>
                      </div>
                    </div>
                  <?php }} ?>
                </div>
                <div class="type_msg">
                  <div class="input_msg_write">
                    <input type="text" class="write_msg" id="text-message" placeholder="Type a message" />
                    <button class="msg_send_btn" type="button" onclick="addMessage()"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
                  </div>
                </div>
                <?php } else { ?>
                <div class="alert alert-info">Please select a conversation.</div>
                <?php } ?>
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
  <script src="../../js/client.js"></script>
  <script>
    $(".msg_history").scrollTop($(".msg_history")[0].scrollHeight);
    
    $('#text-message').keypress(function(e) {
      // Enter pressed?
      if(e.which == 10 || e.which == 13) {
        addMessage();
      }
    });

    function addMessage() {
      var message = document.getElementById("text-message").value;
      $.ajax({
        url: "messages.php?supplier_id=<?php echo $supplier_id ?>",
        type: "post",
        data: {
          action: 'addmessage',
          message,
          supplier_id: '<?php echo $supplier_id ?>'
        },
        success: function(data) {
          window.location.reload();
        },
        error: function(error) {
          alert('Something went wrong.');
        }
      });
    }

    function deleteMessage(id1, id2) {
      $.ajax({
        url: "messages.php?supplier_id=<?php echo $supplier_id ?>",
        type: "post",
        data: {
          action: 'deletemessage',
          user_id_client: id1,
          user_id_supplier: id2
        },
        success: function(data) {
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