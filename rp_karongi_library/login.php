<?php
require 'inc/config.php';
require 'inc/header.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $emailOrUsername = trim($_POST['email_or_username'] ?? '');
    $password = $_POST['password'] ?? '';
    $errors = [];
    if ($emailOrUsername === '' || $password === '') {
        $errors[] = "Both fields are required.";
    } else {
        $stmt = $pdo->prepare("SELECT id, username, email, password, role FROM users WHERE email = ? OR username = ?");
        $stmt->execute([$emailOrUsername, $emailOrUsername]);
        $user = $stmt->fetch();
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user'] = [
              'id' => $user['id'],
              'username' => $user['username'],
              'email' => $user['email'],
              'role' => $user['role']
            ];
            header('Location: dashboard.php');
            exit;
        } else {
            $errors[] = "Wrong credentials.";
        }
    }
}
?>
<div class="row justify-content-center">
  <div class="col-md-5">
    <h2>Login</h2>
    <?php if (!empty($errors)): ?>
      <div class="alert alert-danger"><?= implode('<br>', $errors) ?></div>
    <?php endif; ?>
    <form method="post" class="needs-validation" novalidate>
      <div class="mb-3">
        <label class="form-label">Email or Username</label>
        <input name="email_or_username" required type="text" class="form-control">
        <div class="invalid-feedback">Required</div>
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input name="password" required type="password" class="form-control">
        <div class="invalid-feedback">Required</div>
      </div>
      <button class="btn btn-primary">Login</button>
    </form>
  </div>
</div>
<?php require 'inc/footer.php'; ?>