<style>
  .bg-gradient-primary {
    background-color: #6b6d7d;
    background-image: linear-gradient(180deg,#3a3b45 10%,#6b6d7d 100%);
    background-size: cover;
}
</style>
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
  <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
    <div class="sidebar-brand-icon rotate-n-15">
      <i class="fa fa-folder-open" aria-hidden="true"></i>    </div>
    <div class="sidebar-brand-text mx-3">E-Port <sup><span class="badge badge-light">Supplier</span></sup></div>
  </a>
  <hr class="sidebar-divider my-0">
  <li class="nav-item <?php echo str_contains($_SERVER['REQUEST_URI'], 'dashboard.php') ? 'active': '' ?>">
    <a class="nav-link" href="dashboard.php">
      <i class="fas fa-fw fa-tachometer-alt"></i>
      <span>Dashboard</span></a>
  </li>
  <hr class="sidebar-divider">
  <li class="nav-item <?php echo str_contains($_SERVER['REQUEST_URI'], 'bookings.php') ? 'active': '' ?>">
    <a class="nav-link" href="bookings.php">
      <i class="fas fa-fw fa-table"></i>
      <span>Bookings</span></a>
  </li>
  <li class="nav-item <?php echo str_contains($_SERVER['REQUEST_URI'], 'posts.php') ? 'active': '' ?>">
    <a class="nav-link" href="posts.php">
      <i class="fas fa-fw fa-list"></i>
      <span>Posts</span></a>
  </li>
  <li class="nav-item <?php echo str_contains($_SERVER['REQUEST_URI'], 'reviews.php') ? 'active': '' ?>">
    <a class="nav-link" href="reviews.php">
      <i class="fas fa-fw fa-star"></i>
      <span>Reviews</span></a>
  </li>
  <li class="nav-item <?php echo str_contains($_SERVER['REQUEST_URI'], 'index.php') ? 'active': '' ?>">
    <a class="nav-link" href="index.php">
      <i class="fas fa-fw fa-history"></i>
      <span>Timeline</span></a>
  </li>
</ul>