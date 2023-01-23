<?php
  session_start();
  require_once "../../check_session.php";

  $sql = "select * from post";
  $result = $conn->query($sql);

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] == 'deletepost') {
      if (isset($_POST['postId'])) {
        $post_id = $_POST['postId'];
        $sql = "delete from post where post_id=$post_id";
        $conn->query($sql);

        if (isset($_POST['imageIds'])) {
          $image_ids = $_POST['imageIds'];

          for ($i=0; $i<count($image_ids); $i++) {
            $image_id = $image_ids[$i];
            $result_image = $conn->query("select * from image where image_id=$image_id");
            $row_image = $result_image->fetch_assoc();
            if ($result_image->num_rows) {
              try {
                $location = "../supplier/uploads/".$row_image['path'];
                unlink($location);
              } catch (Exception $e) {
                $location = "../client/uploads/".$row_image['path'];
                unlink($location);
              }
            }
            
            $conn->query("delete from image where image_id=$image_id");
          }
        }
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
  <title>E-Port :: Posts</title>
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
            <h1 class="h3 mb-0 text-gray-800">Posts</h1>
          </div>
          <div class="card shadow mb-4">
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>Name</th>
                      <th>Caption</th>
                      <th>Image</th>
                      <th>Status</th>
                      <th>Date Created</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    if ($result->num_rows) {
                    while ($row = $result->fetch_assoc()) {
                      $post_id = $row['post_id'];
                      
                      $user_id = $row['user_id'];
                      $sql_user = "select * from user where user_id=$user_id";
                      $result_user = $conn->query($sql_user);
                      $row_user = $result_user->fetch_assoc();

                      $sql_likes = "select count(*) as total from likes where post_id=$post_id";
                      $result_likes = $conn->query($sql_likes);
                      $likes = $result_likes->num_rows ? $result_likes->fetch_assoc()['total'] : 0;

                      $sql_comments = "select comment.*, user.avatar, user.firstname, user.lastname, user.type from comment inner join user on comment.user_id=user.user_id and comment.post_id=$post_id order by comment.created desc";
                      $result_comments = $conn->query($sql_comments);
                      $comments = $result_comments->num_rows;

                      $image_ids = json_decode($row['image_ids']);

                      $badge = 'badge-primary';
                      if ($row['status'] == 'PUBLISHED') {
                        $badge='badge-success';
                      }
                    ?>
                      <tr>
                        <td>
                          <a href="#" data-toggle="modal" data-target="#postInfoModal<?php echo $post_id ?>"><?php echo $row_user['firstname'] . " " . $row_user['lastname'] ?></a>
                          <div class="modal fade" id="postInfoModal<?php echo $post_id ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                              <div class="modal-content">
                                <div class="modal-body">
                                  <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                  </button>
                                  <div class="card-body">
                                    <div class="mb-3">
                                      <div class="p-4 mt-3" style="background-color: #f8f9fa; border-radius: 6px;">
                                        <?php echo $row['caption'] ?>
                                      </div>
                                    </div>
                                    <?php
                                      if ($image_ids) {
                                    ?>
                                    <div class="d-flex justify-content-between align-items-center mb-4">
                                      <p class="lead fw-normal mb-0">Photos</p>
                                    </div>
                                    <div class="upload__box mb-5">
                                      <div class="upload__img-wrap">
                                      <?php
                                      for ($i=0; $i<count($image_ids); $i++) {
                                        $sql_image = "select * from image where image_id=$image_ids[$i]";
                                        $result_image = $conn->query($sql_image);

                                        if ($result_image->num_rows) {
                                          while ($row_image = $result_image->fetch_assoc()) {
                                            $image = '';
                                            if ($row_user['type'] == 'SUPPLIER') {
                                              $image = '../supplier/uploads/'.$row_image['path'];
                                            } else {
                                              $image = '../client/uploads/'.$row_image['path'];
                                            }
                                      ?>
                                        <div class='upload__img-box' style="width: 148px;">
                                          <div style="background-image: url('<?php echo $image ?>')" class='img-bg'></div>
                                        </div>
                                      <?php
                                            }
                                          }
                                        }
                                      ?>
                                      </div>
                                    </div>
                                    <?php
                                      }
                                    ?>
                                    <div class="p-2 text-black">
                                      <div class="d-flex justify-content-start text-center py-1">
                                        <div>
                                          <p class="mb-1 h5"><?php echo $likes ?></p>
                                          <p class="small text-muted mb-0">Likes</p>
                                        </div>
                                        <div class="px-3">
                                          <p class="mb-1 h5"><?php echo $comments ?></p>
                                          <p class="small text-muted mb-0">Comments</p>
                                        </div>
                                      </div>
                                    </div>
                                  </div>
                                  <?php if ($result_comments->num_rows) { ?>
                                  <div class="card-footer">
                                    <?php
                                      if ($result_comments->num_rows) {
                                        while ($row_comment = $result_comments->fetch_assoc()) {
                                          $avatar = $row_comment['avatar'] ? ($row_comment['type'] == 'SUPPLIER' ? "../supplier/uploads/".$row_comment['avatar'] : "../client/uploads/".$row_comment['avatar']) : '../../img/undraw_profile.svg'
                                    ?>
                                    <div class="d-flex flex-start">
                                      <img class="rounded-circle shadow-1-strong mr-3" src="<?php echo $avatar ?>" alt="avatar" width="40" height="40" />
                                      <div>
                                        <h6 class="fw-bold mb-1"><?php echo $row_comment['firstname']." ".$row_comment['lastname'] ?></h6>
                                        <div class="d-flex align-items-center mb-3">
                                          <p class="mb-0" style="font-size: 12px;"><?php echo date_format(date_create($row_comment['created']), 'D, M j Y h:ia') ?></p>
                                        </div>
                                        <p class="mb-0"><?php echo $row_comment['message'] ?></p>
                                      </div>
                                    </div>
                                    <hr class="my-3" />
                                    <?php
                                        }
                                      }
                                    ?>
                                  </div>
                                  <?php } ?>
                                </div>
                              </div>
                            </div>
                          </div>
                        </td>
                        <td><?php echo $row['caption'] ?></td>
                        <td><?php echo $image_ids ? count($image_ids) : '0' ?></td>
                        <td><span class="badge <?php echo $badge ?>"><?php echo $row['status'] ?></span></td>
                        <td><?php echo $row['created'] ?></td>
                        <td>
                          <a href="#" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#deletePostModal<?php echo $post_id ?>">Delete</a>
                          <div class="modal fade" id="deletePostModal<?php echo $post_id ?>" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                              <div class="modal-content">
                                <div class="modal-header">
                                  <h5 class="modal-title" id="exampleModalLabel">Delete Post?</h5>
                                  <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                  </button>
                                </div>
                                <div class="modal-body">Are you sure you want to delete this post?</div>
                                <div class="modal-footer">
                                  <button class="btn btn-light" type="button" data-dismiss="modal">Cancel</button>
                                  <button class="btn btn-danger" type="button" data-dismiss="modal" onclick="deletePost(<?php echo $post_id ?>, <?php echo $row['image_ids'] ?>)">Yes</a>
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
    function deletePost(postId, imageIds) {
      $.ajax({
        url: "posts.php",
        type: "post",
        data: {
          postId,
          imageIds,
          action: 'deletepost',
        },
        success: function(data) {
          window.location.replace("posts.php");
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