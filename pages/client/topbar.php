<style>
  /*the container must be positioned relative:*/
  .autocomplete {
    position: relative;
  }
  .autocomplete-items {
    position: absolute;
    border: 1px solid #d4d4d4;
    border-bottom: none;
    border-top: none;
    z-index: 99;
    /*position the autocomplete items to be the same width as the container:*/
    top: 100%;
    left: 0;
    right: 0;
    box-shadow: rgb(0 0 0 / 16%) 0px 10px 36px 0px, rgb(0 0 0 / 6%) 0px 0px 0px 1px;
  }
  .autocomplete-items div {
    padding: 15px 10px;
    cursor: pointer;
    background-color: #fff; 
    border-bottom: 1px solid #d4d4d4; 
    display: flex;
    align-items: center;
  }
  .autocomplete-items img {
    margin-right: 10px;
  }
  /*when hovering an item:*/
  .autocomplete-items div:hover {
    background-color: #e9e9e9; 
  }
  /*when navigating through the items using the arrow keys:*/
  .autocomplete-active {
    background-color: DodgerBlue !important; 
    color: #ffffff; 
  }
  .autocomplete-avatar {
    vertical-align: middle;
    width: 40px;
    height: 40px;
    border-radius: 50%;
  }
</style>
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
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow" style="position: fixed; width: 100%; z-index: 2;">
  <a class="sidebar-brand d-flex align-items-center justify-content-center text-decoration-none text-white" href="index.php">
    <div class="sidebar-brand-icon rotate-n-15">
      <i class="fas fa-folder-open"></i>
    </div>
    <div class="sidebar-brand-text mx-3">E-Port</div>
  </a>
  <form class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search" style="display: flex!important;">
    <div class="input-group autocomplete" style="width: 280px;">
      <input type="text" id="searchInput" class="form-control bg-light border-0 small" placeholder="Search" aria-label="Search" aria-describedby="basic-addon2">
      <div class="input-group-append">
        <button class="btn btn-primary" type="button" id="btn-search">
          <i class="fas fa-search fa-sm"></i>
        </button>
      </div>
    </div>
    <button class="btn btn-info ml-2" type="button" data-toggle="modal" data-target="#addPostModal">Add Post</button>
  </form>
  <ul class="navbar-nav ml-auto">
    <li class="nav-item dropdown no-arrow d-sm-none">
      <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fas fa-search fa-fw"></i>
      </a>
      <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in" aria-labelledby="searchDropdown">
        <form class="form-inline mr-auto w-100 navbar-search">
          <div class="input-group autocomplete">
            <input type="text" id="searchInput" class="form-control bg-light border-0 small" placeholder="Search" aria-label="Search" aria-describedby="basic-addon2">
            <div class="input-group-append">
              <button class="btn btn-primary" type="button" id="btn-search">
                <i class="fas fa-search fa-sm"></i>
              </button>
            </div>
          </div>
        </form>
      </div>
    </li>
    <li class="nav-item dropdown no-arrow mx-1">
      <?php
        $current_user_id = $_SESSION['user_id'];
        $sql_notification = "select a.*, b.* from notification as a inner join message as b on a.message_id=b.message_id and a.is_read=false and b.user_id_client=$current_user_id and b.sender='SUPPLIER'";
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
            $user_id_supplier = $row_notification['user_id_supplier'];
            $sql_user = "select * from user where user_id=$user_id_supplier";
            $result_user = $conn->query($sql_user);
            $row_user = $result_user->fetch_assoc();
            $avatar = $row_user['avatar'] ? '../supplier/uploads/'.$row_user['avatar'] : '../../img/undraw_profile.svg';
        ?>
        <a class="dropdown-item d-flex align-items-center pl-2 pr-2" href="messages.php?supplier_id=<?php echo $row_notification['user_id_supplier'] ?>&notification_id=<?php echo $row_notification['notification_id'] ?>">
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
    <li class="nav-item dropdown no-arrow">
      <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <img class="img-profile rounded-circle" style="border: 1px solid" src="<?php echo $_SESSION['user_avatar'] ? "uploads/" . $_SESSION['user_avatar'] : '../../img/undraw_profile.svg' ?>">
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
<div class="modal fade" id="addPostModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Add Post</h5>
        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">×</span>
        </button>
      </div>
      <form onsubmit="return addPost()">
        <div class="modal-body">
          <div class="form-outline mb-4">
            <label class="form-label" for="form3Example3">Caption</label>
            <input type="text" name="caption" required class="form-control" />
          </div>
          <div class="form-outline mb-4">
            <div class="upload__box">
              <div class="upload__btn-box">
                <label class="btn btn-primary">
                  Upload photos
                  <input type="file" multiple accept="image/png, image/gif, image/jpeg" class="upload__inputfile">
                </label>
              </div>
              <div class="upload__img-wrap"></div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <button class="btn btn-primary" type="submit">Submit</a>
        </div>
      </form>
    </div>
  </div>
</div>
<script>
  function autocomplete(inp, arr) {
    /*the autocomplete function takes two arguments,
    the text field element and an array of possible autocompleted values:*/
    var currentFocus;
    /*execute a function when someone writes in the text field:*/
    inp.addEventListener("input", function(e) {
        var a, b, i, val = this.value;
        /*close any already open lists of autocompleted values*/
        closeAllLists();
        if (!val) { return false;}
        currentFocus = -1;
        /*create a DIV element that will contain the items (values):*/
        a = document.createElement("DIV");
        a.setAttribute("id", this.id + "autocomplete-list");
        a.setAttribute("class", "autocomplete-items");
        /*append the DIV element as a child of the autocomplete container:*/
        this.parentNode.appendChild(a);
        /*for each item in the array...*/
        for (i = 0; i < arr.length; i++) {
          /*check if the item starts with the same letters as the text field value:*/
          if (arr[i].name.substr(0, val.length).toUpperCase() == val.toUpperCase()) {
            /*create a DIV element for each matching element:*/
            b = document.createElement("DIV");
            b.innerHTML = "<img class='autocomplete-avatar' src='"+arr[i].avatar+"'/>";
            /*make the matching letters bold:*/
            b.innerHTML += "<strong>" + arr[i].name.substr(0, val.length) + "</strong>";
            b.innerHTML += arr[i].name.substr(val.length);
            /*insert a input field that will hold the current array item's value:*/
            b.innerHTML += "<input type='hidden' value='" + arr[i].name + "'>";
            /*execute a function when someone clicks on the item value (DIV element):*/
            let id = arr[i].id;
            b.addEventListener("click", function(e) {
              /*insert the value for the autocomplete text field:*/
              inp.value = this.getElementsByTagName("input")[0].value;
              var btn = document.getElementById('btn-search');
              btn.setAttribute("onclick","window.location.href='supplier.php?id="+id+"'")
              btn.click();

              /*close the list of autocompleted values,
              (or any other open lists of autocompleted values:*/
              closeAllLists();
            });
            a.appendChild(b);
          }
        }
    });
    /*execute a function presses a key on the keyboard:*/
    inp.addEventListener("keydown", function(e) {
        var x = document.getElementById(this.id + "autocomplete-list");
        if (x) x = x.getElementsByTagName("div");
        if (e.keyCode == 40) {
          /*If the arrow DOWN key is pressed,
          increase the currentFocus variable:*/
          currentFocus++;
          /*and and make the current item more visible:*/
          addActive(x);
        } else if (e.keyCode == 38) { //up
          /*If the arrow UP key is pressed,
          decrease the currentFocus variable:*/
          currentFocus--;
          /*and and make the current item more visible:*/
          addActive(x);
        } else if (e.keyCode == 13) {
          /*If the ENTER key is pressed, prevent the form from being submitted,*/
          e.preventDefault();
          if (currentFocus > -1) {
            /*and simulate a click on the "active" item:*/
            if (x) x[currentFocus].click();
          }
        }
    });
    function addActive(x) {
      /*a function to classify an item as "active":*/
      if (!x) return false;
      /*start by removing the "active" class on all items:*/
      removeActive(x);
      if (currentFocus >= x.length) currentFocus = 0;
      if (currentFocus < 0) currentFocus = (x.length - 1);
      /*add class "autocomplete-active":*/
      x[currentFocus].classList.add("autocomplete-active");
    }
    function removeActive(x) {
      /*a function to remove the "active" class from all autocomplete items:*/
      for (var i = 0; i < x.length; i++) {
        x[i].classList.remove("autocomplete-active");
      }
    }
    function closeAllLists(elmnt) {
      /*close all autocomplete lists in the document,
      except the one passed as an argument:*/
      var x = document.getElementsByClassName("autocomplete-items");
      for (var i = 0; i < x.length; i++) {
        if (elmnt != x[i] && elmnt != inp) {
          x[i].parentNode.removeChild(x[i]);
        }
      }
    }
    /*execute a function when someone clicks in the document:*/
    document.addEventListener("click", function (e) {
        closeAllLists(e.target);
    });
  }

  var suppliers = [];
  <?php
    $sql_list = "select * from user where type='SUPPLIER' and status='APPROVED'";
    $result_list = $conn->query($sql_list);
    
    if ($result_list->num_rows) {
    while ($list = $result_list->fetch_assoc()) {
  ?>
  suppliers.push({
    id: '<?php echo $list['user_id'] ?>',
    avatar: '<?php echo $list['avatar'] ? "../supplier/uploads/".$list['avatar'] : '../../img/undraw_profile.svg' ?>',
    name: '<?php echo $list['firstname']." ".$list['lastname'] ?>'
  })
  <?php
    }}
  ?>

  /*initiate the autocomplete function on the "myInput" element, and pass along the countries array as possible autocomplete values:*/
  autocomplete(document.getElementById("searchInput"), suppliers);
</script>