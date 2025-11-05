<?php require 'inc/config.php'; require 'inc/header.php'; ?>
<div class="row">
  <div class="col-md-8">
    <h1>Welcome to RP Karongi Library</h1>
    <p>Access books, borrow, and manage resources online.</p>

    <div class="row">
      <div class="col-md-6">
        <div class="card mb-3">
          <div class="card-body">
            <h5 class="card-title">Browse Books</h5>
            <p class="card-text">View available books and borrow.</p>
            <a href="books.php" class="btn btn-primary">See Books</a>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card mb-3">
          <div class="card-body">
            <h5 class="card-title">Your Account</h5>
            <p class="card-text">Register or login to manage your borrowed books.</p>
            <?php if(!isset($_SESSION['user'])): ?>
              <a href="register.php" class="btn btn-secondary">Register</a>
              <a href="login.php" class="btn btn-primary">Login</a>
            <?php else: ?>
              <a href="dashboard.php" class="btn btn-primary">Dashboard</a>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

  </div>
  <div class="col-md-4">
    <h4>Contact</h4>
    <p>rp.karongi@library.rw</p>
  </div>
</div>
<?php require 'inc/footer.php'; ?>