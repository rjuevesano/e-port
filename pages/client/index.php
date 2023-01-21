<?php
  session_start();
  require_once "../../config.php";

  if (!isset($_SESSION['user_id'])) {
    header('Location: ../../login.php');
    die;
  }

  $sql = "select * from post where status='PUBLISHED' order by created desc";
  $result = $conn->query($sql);

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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

  <title>E-Port</title>
  <link href="../../vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  <link href="../../css/sb-admin-2.min.css" rel="stylesheet">
  <style>
    .avatar {
      vertical-align: middle;
      width: 40px;
      height: 40px;
      border-radius: 50%;
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
  </style>
</head>

<body id="page-top">
  <div id="wrapper">
    <div id="content-wrapper" class="d-flex flex-column">
      <div id="content" style="min-height: 100vh;">
        <?php include 'topbar.php' ?>
        <div class="container-fluid" style="padding-top: 70px;">
          <?php
            if (!$result->num_rows) {
              echo "<div class='d-flex justify-content-center mt-5'>No post yet.</div>";
            }

            while ($row = $result->fetch_assoc()) {
              $post_id = $row['post_id'];

              $user_id = $row['user_id'];
              $sql_user = "select * from user where user_id=$user_id";
              $result_user = $conn->query($sql_user);
              $row_user = $result_user->fetch_assoc();
              
              $avatar = '../../img/undraw_profile.svg';
              if ($row_user['avatar']) {
                if ($row_user['type'] === 'SUPPLIER') {
                  $avatar = "../supplier/uploads/".$row_user['avatar'];
                } else {
                  $avatar = "uploads/".$row_user['avatar'];
                }
              }

              $current_user_id = $_SESSION['user_id'];
              $sql_like = "select count(*) as total from likes where user_id=$current_user_id and post_id=$post_id";
              $result_like = $conn->query($sql_like);
              $liked = $result_like->fetch_assoc()['total'];

              $sql_likes = "select count(*) as total from likes where post_id=$post_id";
              $result_likes = $conn->query($sql_likes);
              $likeds = $result_likes->fetch_assoc()['total'];

              $sql_comments = "select comment.*, user.avatar, user.firstname, user.lastname, user.type from comment inner join user on comment.user_id=user.user_id and comment.post_id=$post_id order by comment.created desc";
              $result_comments = $conn->query($sql_comments);

              $image_ids = json_decode($row['image_ids']);
          ?>
          <div class="card shadow m-3">
            <div class="card-header py-3 d-flex flex-row justify-content-between align-items-center">
              <a href="<?php echo $row_user['type'] === 'SUPPLIER' ? 'supplier.php?id='.$row_user['user_id'] : 'client.php?id='.$row_user['user_id'] ?>" class="d-flex flex-row align-items-center text-decoration-none">
                <div class="mr-2">
                  <img class="avatar" src="<?php echo $avatar ?>" alt=""/>
                </div>
                <div>
                  <h6 class="m-0 font-weight-bold text-gray-900"><?php echo $row_user['firstname']." ".$row_user['lastname'] ?></h6>
                  <div class="small mb-1">@<?php echo $row_user['username'] ?></div>
                </div>
              </a>
              <div>
                <?php echo date_format(date_create($row['created']), 'D, M j Y h:ia') ?>
              </div>
            </div>
            <div class="card-body">
              <p><?php echo $row['caption'] ?></p>
              <?php if ($image_ids) { ?>
              <div class="lg-grid mb-3">
                <?php
                for ($i=0; $i<count($image_ids); $i++) {
                  $sql_image = "select * from image where image_id=$image_ids[$i]";
                  $result_image = $conn->query($sql_image);

                  while ($row_image = $result_image->fetch_assoc()) {
                    if ($row_user['type'] === 'SUPPLIER') {
                      $path = "../supplier/uploads/";
                    } else {
                      $path = "uploads/";
                    }
                ?>
                  <a class="lb-item" data-fslightbox="gallery<?php echo $post_id ?>" href="<?php echo $path.$row_image['path'] ?>" style="background-image: url('<?php echo $path.$row_image['path'] ?>')"></a>
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
                  $avatar = '../../img/undraw_profile.svg';
                  if ($row_comment['avatar']) {
                    if ($row_comment['type'] == 'SUPPLIER') {
                      $avatar = "../supplier/uploads/".$row_comment['avatar'];
                    } else {
                      $avatar = "uploads/".$row_comment['avatar'];
                    }
                  }
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
    function togglePostLike(postId, like) {
      $.ajax({
        url: "index.php",
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
        url: "index.php",
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
        url: "index.php",
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