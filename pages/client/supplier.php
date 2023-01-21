<?php
  session_start();
  require_once "../../config.php";

  if (!isset($_SESSION['user_id'])) {
    header('Location: ../../login.php');
    die;
  }

  $user_id = $_GET['id'];
  $sql = "select * from user where user_id=$user_id";
  $result = $conn->query($sql);
  $row = $result->fetch_assoc();

  $current_user_id = $_SESSION['user_id'];
  $sql_current_user = "select * from user where user_id=$current_user_id";
  $result_current_user = $conn->query($sql_current_user);
  $row_current_user = $result_current_user->fetch_assoc();

  $sql_posts = "select * from post where user_id=$user_id order by created desc";
  $result_posts = $conn->query($sql_posts);

  $sql_rating_exist = "select * from rating where user_id_client=$current_user_id and user_id_supplier=$user_id";
  $result_rating_exist = $conn->query($sql_rating_exist);

  $sql_rating = "select * from rating where user_id_supplier=$user_id";
  $result_rating = $conn->query($sql_rating);

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'addappointment') {
      $firstname = validate($_POST['firstname']);
      $lastname = validate($_POST['lastname']);
      $mobile = validate($_POST['mobile']);
      $address = validate($_POST['address']);
      $date = validate($_POST['date']);
      $converted_date = date("Y-m-d H:i:s", strtotime($date));
      $note = validate($_POST['note']);

      $sql_check = "select count(*) as total from booking where (status='PENDING' or status='ACCEPTED') and user_id_supplier=$user_id and schedule_date >= '$converted_date' and schedule_date <= '$converted_date'";
      $result_check = $conn->query($sql_check);
      $total = $result_check->fetch_assoc()['total'];
      if ($total) {
        json_response([
          'success' => false,
          'message' => 'Appointment date is already occupied! Please try another date.'
        ]);
      } else {
        $sql_booking = "insert into booking (user_id_client, user_id_supplier, schedule_date, status, note) values ($current_user_id, $user_id, '$converted_date', 'PENDING', '$note')";
        $conn->query($sql_booking);
        json_response([
          'success' => true,
          'message' => 'Successfully added.'
        ]);
      }
    }

    if (isset($_POST['action']) && $_POST['action'] == 'addreview') {
      $rating = $_POST['rating'];
      $message = $_POST['message'];

      $sql = "insert into rating (user_id_client, user_id_supplier, message, rate) values('$current_user_id', '$user_id', '$message', '$rating')";
      $conn->query($sql);
      json_response([
        'success' => true,
        'message' => 'Thank you for your feedback!'
      ]);
    }

    if (isset($_POST['action']) && $_POST['action'] == 'addmessage') {
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

    if (isset($_POST['action']) && $_POST['action'] == 'likepost') {
      $post_id = $_POST['postId'];
      $like = $_POST['like'];
      $user_id = $_SESSION['user_id'];

      if ($like==1) {
        $sql = "delete from likes where user_id=$user_id and post_id=$post_id";
        $conn->query($sql);
        json_response([
          'success' => true,
          'message' => 'Successfully removed.'
        ]);
      } else {
        $sql = "insert into likes (user_id, post_id) values ('$user_id', '$post_id')";
        $conn->query($sql);
        json_response([
          'success' => true,
          'message' => 'Successfully added.'
        ]);
      }
      
    }

    if (isset($_POST['action']) && $_POST['action'] == 'addcomment') {
      $post_id = $_POST['postId'];
      $message = $_POST['message'];
      $user_id = $_SESSION['user_id'];

      $sql = "insert into comment (user_id, post_id, message) values ('$user_id', '$post_id', '$message')";
      $conn->query($sql);
      json_response([
        'success' => true,
        'message' => 'Successfully added.'
      ]);
    }

    if (isset($_POST['action']) && $_POST['action'] == 'deletecomment') {
      $comment_id = $_POST['commentId'];

      $sql = "delete from comment where comment_id=$comment_id";
      $conn->query($sql);
      json_response([
        'success' => true,
        'message' => 'Successfully deleted.'
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

  <title>E-Port :: Supplier</title>
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <style>
    .gradient-custom-2 {
      /* fallback for old browsers */
      background: #fbc2eb;

      /* Chrome 10-25, Safari 5.1-6 */
      background: -webkit-linear-gradient(to right, rgba(251, 194, 235, 1), rgba(166, 193, 238, 1));

      /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
      background: linear-gradient(to right, rgba(251, 194, 235, 1), rgba(166, 193, 238, 1))
    }
    .avatar {
      vertical-align: middle;
      width: 40px;
      height: 40px;
      border-radius: 50%;
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
    .required {
      color: red;
    }
    .lg-grid {
      position: relative;
      display: block;
      height: 37.8rem !important;
    }
    .lb-item {
      position: absolute;
      background-position: 50%;
      background-repeat: no-repeat;
      background-size: cover;
      border-top: 2px solid #fff;
      border-right: 2px solid #fff;
      cursor: pointer;
      width: 50%;
    }
    .lb-item:first-child {
      height: 50%;
    }
    .lb-item:nth-child(2) {
      height: 50%;
      bottom: 0;
      top: auto;
    }
    .lb-item:nth-child(3) {
      height: 33.3333333%;
      left: auto;
      right: 0;
      border-right: 0;
    }
    .lb-item:nth-child(4) {
      height: 33.3333333%;
      left: auto;
      right: 0;
      border-right: 0;
    }
    .lb-item:nth-child(5) {
      height: 33.3333333%;
      bottom: 0;
      top: auto;
      left: auto;
      right: 0;
      border-right: 0;
    }
    .rate {
      float: left;
      height: 46px;
      padding: 0 10px;
    }
    .rate:not(:checked) > input {
        position:absolute;
        top:-9999px;
    }
    .rate:not(:checked) > label {
        float:right;
        width:1em;
        overflow:hidden;
        white-space:nowrap;
        cursor:pointer;
        font-size:30px;
        color:#ccc;
    }
    .rate:not(:checked) > label:before {
        content: '★ ';
    }
    .rate > input:checked ~ label {
        color: #ffc700;    
    }
    .rate:not(:checked) > label:hover,
    .rate:not(:checked) > label:hover ~ label {
        color: #deb217;  
    }
    .rate > input:checked + label:hover,
    .rate > input:checked + label:hover ~ label,
    .rate > input:checked ~ label:hover,
    .rate > input:checked ~ label:hover ~ label,
    .rate > label:hover ~ input:checked ~ label {
        color: #c59b08;
    }
  </style>
</head>

<body id="page-top">
  <div id="wrapper">
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content" style="min-height: 100vh;">
        <?php include 'topbar.php' ?>
        <div class="container-fluid" style="padding: 0; padding-top: 70px;">
          <section class="h-100 gradient-custom-2">
            <div class="container py-5 h-100">
              <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col">
                  <div class="card">
                    <div class="rounded-top text-white d-flex flex-row justify-content-between" style="background-color: #000; height:200px;">
                      <div class="d-flex flex-row">
                        <div class="ml-4 mt-5 d-flex flex-column" style="width: 150px; height: 280px;">
                          <img src=<?php echo $row['avatar'] ? "../supplier/uploads/".$row['avatar'] : '../../img/undraw_profile.svg' ?> alt="Generic placeholder image" class="img-fluid img-thumbnail mt-4 mb-2" style="width: 150px; height: 150px; z-index: 1">
                          <button type="button" class="btn btn-primary" style="z-index: 1;" data-toggle="modal" data-target="#bookModal">
                            Book
                          </button>
                          <?php if (!$result_rating_exist->num_rows) { ?>
                          <button type="button" class="btn btn-info mt-2" style="z-index: 1;" data-toggle="modal" data-target="#reviewModal">
                            Add Review
                          </button>
                          <?php } ?>
                        </div>
                        <div class="ml-3" style="margin-top: 110px;">
                          <h5><?php echo $row['firstname'].' '.$row['lastname'] ?></h5>
                          <span class="text-gray-500"><i class="fa fa-phone"></i> <?php echo $row['mobile'] ?></span>
                          <br/>
                          <span class="text-gray-500"><i class="fa fa-map"></i> <?php echo $row['address'] ?></span>
                        </div>
                      </div>
                      <div class="d-flex flex-column justify-content-end mb-3 mr-3">
                        <button type="button" class="btn btn-success mt-2" style="z-index: 1;" data-toggle="modal" data-target="#sendMessageModal">Send a message</button>
                        <?php if ($row['file']) { ?>
                        <a href="../../download.php?file=<?php echo $row['file'] ?>" target="_blank" class="btn btn-secondary mt-2" style="z-index: 1;">Generate Curriculum Vitae</a>
                        <?php } ?>
                      </div>
                    </div>
                    <div class="p-4 text-black" style="background-color: #f8f9fa;">
                      <div class="d-flex justify-content-end text-center py-1">
                        <div>
                          <?php
                            $total_rating = 0;
                            while ($rating = $result_rating->fetch_assoc()) {
                              $total_rating += $rating['rate'];
                            }

                            $total_rating = $result_rating->num_rows ? number_format((float)($total_rating / $result_rating->num_rows), 2, '.', '') : 0;
                          ?>
                          <p class="mb-1 h5"><?php echo str_replace(".00", "", $total_rating) ?></p>
                          <div class="stars">
                            <i class="fa fa-star <?php echo $total_rating >= 1 ? 'text-warning' : '' ?>"></i>
                            <i class="fa fa-star <?php echo $total_rating >= 2 ? 'text-warning' : '' ?>"></i>
                            <i class="fa fa-star <?php echo $total_rating >= 3 ? 'text-warning' : '' ?>"></i>
                            <i class="fa fa-star <?php echo $total_rating >= 4 ? 'text-warning' : '' ?>"></i>
                            <i class="fa fa-star <?php echo $total_rating >= 5 ? 'text-warning' : '' ?>"></i>
                          </div>
                          <a href="#" class="small text-muted mb-0" data-toggle="modal" data-target="#reviewsModal">View Reviews</a>
                        </div>
                      </div>
                    </div>
                    <div class="card-body p-4 text-black">
                      <div class="mb-5">
                        <p class="lead fw-normal mb-1">About</p>
                        <div class="p-4" style="background-color: #f8f9fa;">
                          <p class="font-italic mb-0"><?php echo $row['about'] ?></p>
                        </div>
                      </div>
                      <div class="d-flex justify-content-between align-items-center mb-4">
                        <p class="lead fw-normal mb-0">Posts</p>
                      </div>
                      <?php
                        while ($row_post = $result_posts->fetch_assoc()) {
                          $post_id = $row_post['post_id'];

                          $user_id = $row_post['user_id'];
                          $sql_user = "select * from user where user_id=$user_id";
                          $result_user = $conn->query($sql_user);
                          $row_user = $result_user->fetch_assoc();

                          $current_user_id = $_SESSION['user_id'];
                          $sql_like = "select count(*) as total from likes where user_id=$current_user_id and post_id=$post_id";
                          $result_like = $conn->query($sql_like);
                          $liked = $result_like->fetch_assoc()['total'];

                          $sql_likes = "select count(*) as total from likes where post_id=$post_id";
                          $result_likes = $conn->query($sql_likes);
                          $likeds = $result_likes->fetch_assoc()['total'];

                          $sql_comments = "select comment.*, user.avatar, user.firstname, user.lastname, user.type from comment inner join user on comment.user_id=user.user_id and comment.post_id=$post_id order by comment.created desc";
                          $result_comments = $conn->query($sql_comments);

                          $image_ids = json_decode($row_post['image_ids']);
                      ?>
                      <div class="card shadow m-3">
                        <div class="card-header py-3 d-flex flex-row justify-content-between align-items-center">
                          <a href="profile.php?supplier_id=<?php echo $row_user['user_id'] ?>" class="d-flex flex-row align-items-center text-decoration-none">
                            <div class="mr-2">
                              <img class="avatar" src="<?php echo $row_user['avatar'] ? "../supplier/uploads/".$row_user['avatar'] : '../../img/undraw_profile.svg' ?>" alt=""/>
                            </div>
                            <div>
                              <h6 class="m-0 font-weight-bold text-gray-900"><?php echo $row_user['firstname']." ".$row_user['lastname'] ?></h6>
                              <div class="small mb-1">@<?php echo $row_user['username'] ?></div>
                            </div>
                          </a>
                          <div>
                            <?php echo date_format(date_create($row_post['created']), 'D, M j Y h:ia') ?>
                          </div>
                        </div>
                        <div class="card-body">
                          <p><?php echo $row_post['caption'] ?></p>
                          <?php if ($image_ids) { ?>
                          <div class="lg-grid mb-3">
                          <?php
                            for ($i=0; $i<count($image_ids); $i++) {
                              $sql_image = "select * from image where image_id=$image_ids[$i]";
                              $result_image = $conn->query($sql_image);

                              while ($row_image = $result_image->fetch_assoc()) {
                            ?>
                              <a class="lb-item" data-fslightbox="gallery<?php echo $post_id ?>" href="<?php echo "../supplier/uploads/".$row_image['path'] ?>" style="background-image: url('<?php echo "../supplier/uploads/".$row_image['path'] ?>')"></a>
                            <?php
                                }
                              }
                            ?>
                          </div>
                          <?php } ?>
                          <div class="small d-flex justify-content-start">
                            <a href="#!" class="d-flex align-items-center mr-3 text-decoration-none text-gray-800" onclick="togglePostLike(<?php echo $post_id ?>, <?php echo $liked ?>)">
                              <i class="<?php echo $liked ? 'fa':'far' ?> fa-thumbs-up mr-1"></i>
                              <p class="mb-0">Like (<?php echo $likeds ?>)</p>
                            </a>
                            <a href="#!" class="d-flex align-items-center text-decoration-none text-gray-800">
                              <i class="far fa-comment-dots mr-1"></i>
                              <p class="mb-0">Comment (<?php echo $result_comments->num_rows ?>)</p>
                            </a>
                          </div>
                        </div>
                        <div class="card-footer py-3 border-0" style="background-color: #f8f9fa;">
                          <div class="d-flex flex-start w-100">
                            <img class="rounded-circle shadow-1-strong mr-3"
                              src="<?php echo $_SESSION['user_avatar'] ? "uploads/".$_SESSION['user_avatar'] : '../../img/undraw_profile.svg' ?>" alt="avatar" width="40"
                              height="40" />
                            <div class="form-outline w-100">
                              <textarea id="comment<?php echo $post_id ?>" class="form-control" rows="4" style="background: #fff; resize: none;" placeholder="Your message here..."></textarea>
                            </div>
                          </div>
                          <div class="d-flex flex-row justify-content-end mt-2 pt-1">
                            <button type="button" class="btn btn-primary btn-sm" onclick="addComment(<?php echo $post_id ?>)">Post comment</button>
                          </div>
                          <?php
                            while ($row_comment = $result_comments->fetch_assoc()) {
                              $avatar = $row_comment['avatar'] ? ($row_comment['type'] == 'SUPPLIER' ? "../supplier/uploads/".$row_comment['avatar'] : "uploads/".$row_comment['avatar']) : '../../img/undraw_profile.svg'
                          ?>
                          <div class="d-flex flex-start">
                            <img class="rounded-circle shadow-1-strong mr-3" src="<?php echo $avatar ?>" alt="avatar" width="40" height="40" />
                            <div>
                              <h6 class="fw-bold mb-1"><?php echo $row_comment['firstname']." ".$row_comment['lastname'] ?></h6>
                              <div class="d-flex align-items-center mb-3">
                                <p class="mb-0" style="font-size: 12px;"><?php echo date_format(date_create($row_comment['created']), 'D, M j Y h:ia') ?></p>
                              </div>
                              <p class="mb-0"><?php echo $row_comment['message'] ?></p>
                              <?php if ($row_comment['user_id'] == $_SESSION['user_id']) { ?>
                              <button type="button" class="btn btn-danger btn-sm mt-2" onclick="deleteComment(<?php echo $row_comment['comment_id'] ?>)">Delete</button>
                              <?php } ?>
                            </div>
                          </div>
                          <hr class="my-3" />
                          <?php } ?>
                        </div>
                      </div>
                      <?php } ?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </section>
        </div>
      </div>
      <?php include 'footer.php' ?>
    </div>
  </div>
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>
  <div class="modal fade" id="bookModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Appointment Form</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form onsubmit="return addAppointment()">
          <div class="modal-body">
            <div class="row mb-4">
              <div class="col">
                <div class="form-outline">
                  <label class="form-label" for="form3Example1">Firstname</label>
                  <input type="text" name="firstname" value="<?php echo $row_current_user['firstname'] ?>" required class="form-control" />
                </div>
              </div>
              <div class="col">
                <div class="form-outline">
                  <label class="form-label" for="form3Example2">Lastname</label>
                  <input type="text" name="lastname" value="<?php echo $row_current_user['lastname'] ?>" required class="form-control" />
                </div>
              </div>
            </div>
            <div class="row mb-4">
              <div class="col">
                <div class="form-outline">
                  <label class="form-label" for="form3Example1">Mobile</label>
                  <input type="text" name="mobile" value="<?php echo $row_current_user['mobile'] ?>" required class="form-control" />
                </div>
              </div>
              <div class="col">
                <div class="form-outline">
                  <label class="form-label" for="form3Example2">Address</label>
                  <input type="text" name="address" value="<?php echo $row_current_user['address'] ?>" required class="form-control" />
                </div>
              </div>
            </div>
            <div class="form-outline mb-4">
              <label class="form-label" for="form3Example1">Appointment Date <span class="required">*</span></label>
              <input type="datetime-local" name="date" required class="form-control" />
            </div>
            <div class="form-outline mb-4">
              <label class="form-label" for="form3Example1">Note <span class="required">*</span></label>
              <textarea rows="4" name="note" required class="form-control"></textarea>
            </div>
          </div>
          <div class="modal-footer" style="border-top:none">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <button class="btn btn-primary" type="submit">Submit</a>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="modal fade" id="reviewModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Add Review</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form onsubmit="return addReview()">
          <div class="modal-body">
            <div class="form-outline d-flex justify-content-center">
              <div class="rate">
                <input type="radio" id="star5" name="rate" value="5" onclick="postToController()" />
                <label for="star5">5 stars</label>
                <input type="radio" id="star4" name="rate" value="4" onclick="postToController()"/>
                <label for="star4">4 stars</label>
                <input type="radio" id="star3" name="rate" value="3" onclick="postToController()"/>
                <label for="star3">3 stars</label>
                <input type="radio" id="star2" name="rate" value="2" onclick="postToController()"/>
                <label for="star2">2 stars</label>
                <input type="radio" id="star1" name="rate" value="1" onclick="postToController()"/>
                <label for="star1">1 star</label>
              </div>
            </div>
            <div style="clear:both"></div>
            <div class="form-outline mb-4">
              <label class="form-label" for="form3Example1">Message <span class="required">*</span></label>
              <input type="text" name="rating" style="display:none;"/>
              <textarea rows="4" name="message" required class="form-control"></textarea>
            </div>
          </div>
          <div class="modal-footer" style="border-top:none">
            <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
            <button class="btn btn-primary" type="submit">Submit</a>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="modal fade" id="reviewsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Reviews</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
        <?php
          $sql_rating = "select * from rating where user_id_supplier=$user_id order by created desc";
          $result_rating = $conn->query($sql_rating);

          if (!$result_rating->num_rows) {
            echo "No reviews yet.";
          }


          while ($rating = $result_rating->fetch_assoc()) {
            $user_id_client = $rating['user_id_client'];
            $sql_client = "select * from user where user_id=$user_id_client";
            $result_client = $conn->query($sql_client);
            $row_client = $result_client->fetch_assoc();
            $avatar = $row_client['avatar'] ? "uploads/".$row_client['avatar'] : '../../img/undraw_profile.svg'
        ?>
        <div class="d-flex flex-start">
          <img class="rounded-circle shadow-1-strong mr-3" src="<?php echo $avatar ?>" alt="avatar" width="40" height="40" />
          <div>
            <h6 class="fw-bold mb-1"><?php echo $row_client['firstname']." ".$row_client['lastname'] ?></h6>
            <div class="d-flex align-items-center mb-3">
              <p class="mb-0" style="font-size: 12px;"><?php echo date_format(date_create($rating['created']), 'D, M j Y h:ia') ?></p>
            </div>
            <p class="mb-0"><?php echo $rating['message'] ?></p>
            <div class="stars">
              <i class="fa fa-star <?php echo $rating['rate'] >= 1 ? 'text-warning' : '' ?>"></i>
              <i class="fa fa-star <?php echo $rating['rate'] >= 2 ? 'text-warning' : '' ?>"></i>
              <i class="fa fa-star <?php echo $rating['rate'] >= 3 ? 'text-warning' : '' ?>"></i>
              <i class="fa fa-star <?php echo $rating['rate'] >= 4 ? 'text-warning' : '' ?>"></i>
              <i class="fa fa-star <?php echo $rating['rate'] >= 5 ? 'text-warning' : '' ?>"></i>
            </div>
          </div>
        </div>
        <hr class="my-3" />
        <?php } ?>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="sendMessageModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Send a message</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <form onsubmit="return addMessage()">
          <div class="modal-body">
            <div class="form-outline mb-4">
              <textarea rows="4" name="send-message-text" required class="form-control"></textarea>
            </div>
          </div>
          <div class="modal-footer" style="border-top:none">
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
  <script src="../../vendor/lightbox/fslightbox.js"></script>
  <script src="../../js/sb-admin-2.min.js"></script>
  <script src="../../js/client.js"></script>
  <script>
    function addAppointment() {
      var firstname = document.getElementsByName("firstname")[0].value;
      var lastname = document.getElementsByName("lastname")[0].value;
      var mobile = document.getElementsByName("mobile")[0].value;
      var address = document.getElementsByName("address")[0].value;
      var date = document.getElementsByName("date")[0].value;
      var note = document.getElementsByName("note")[0].value;

      $.ajax({
        url: "supplier.php?id=<?php echo $user_id ?>",
        type: "post",
        data: {
          action: 'addappointment',
          firstname,
          lastname,
          mobile,
          address,
          date,
          note
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

    function postToController() {
      for (i = 0; i < document.getElementsByName('rate').length; i++) {
        if (document.getElementsByName('rate')[i].checked == true) {
          var rateValue = document.getElementsByName('rate')[i].value;
          break;
        }
      }
      document.getElementsByName('rating')[0].value = rateValue;
    }

    function addReview() {
      var rating = document.getElementsByName("rating")[0].value;
      var message = document.getElementsByName("message")[0].value;

      $.ajax({
        url: "supplier.php?id=<?php echo $user_id ?>",
        type: "post",
        data: {
          action: 'addreview',
          rating,
          message
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

    function addMessage() {
      var message = document.getElementsByName("send-message-text")[0].value;

      $.ajax({
        url: "supplier.php?id=<?php echo $user_id ?>",
        type: "post",
        data: {
          action: 'addmessage',
          message
        },
        success: function(data) {
          alert(data.message);
          if (data.success) {
            window.location.href = "messages.php?supplier_id=<?php echo $user_id ?>";
          }
        },
        error: function(error) {
          alert('Something went wrong.');
        }
      });
      return false;
    }

    function togglePostLike(postId, like) {
      $.ajax({
        url: "supplier.php?id=<?php echo $user_id ?>",
        type: "post",
        data: {
          postId,
          like,
          action: 'likepost',
        },
        success: function(data) {
          if (data.success) {
            window.location.reload();
          }
        },
        error: function(error) {
          alert('Something went wrong.');
        }
      });
    }

    function addComment(postId) {
      $.ajax({
        url: "supplier.php?id=<?php echo $user_id ?>",
        type: "post",
        data: {
          postId,
          message: document.getElementById('comment'+postId).value,
          action: 'addcomment',
        },
        success: function(data) {
          if (data.success) {
            window.location.reload();
          }
        },
        error: function(error) {
          alert('Something went wrong.');
        }
      });
    }

    function deleteComment(commentId) {
      $.ajax({
        url: "supplier.php?id=<?php echo $user_id ?>",
        type: "post",
        data: {
          commentId,
          action: 'deletecomment',
        },
        success: function(data) {
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