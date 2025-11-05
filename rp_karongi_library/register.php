<?php
require 'inc/config.php';
require 'inc/header.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';
    $student_id = trim($_POST['student_id'] ?? '');
    $errors = [];
    if ($username === '' || $email === '' || $password === '' || $student_id === '') {
        $errors[] = "All fields are required.";
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email address.";
    }
    if (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }
    if ($password !== $confirm) {
        $errors[] = "Passwords do not match.";
    }
    if (empty($errors)) {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? OR username = ? OR student_id = ?");
        $stmt->execute([$email, $username, $student_id]);
        if ($stmt->fetch()) {
            $errors[] = "User with same email/username/student ID already exists.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, student_id) VALUES (?, ?, ?, ?)");
            $stmt->execute([$username, $email, $hash, $student_id]);
            flash('success', 'Registration successful. Please login.');
            header('Location: login.php');
            exit;
        }
    }
}
?>
<div class="row justify-content-center">
  <div class="col-md-6">
    <h2>Register</h2>
    <?php if (!empty($errors)): ?>
      <div class="alert alert-danger">
        <ul><?php foreach ($errors as $e) echo "<li>$e</li>"; ?></ul>
      </div>
    <?php endif; ?>
    <form method="post" novalidate class="needs-validation" id="registerForm">
      <div class="mb-3">
        <label class="form-label">Username</label>
        <input name="username" required type="text" class="form-control" value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
        <div class="invalid-feedback">Please provide a username.</div>
      </div>
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input name="email" required type="email" class="form-control" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        <div class="invalid-feedback">Please provide a valid email.</div>
      </div>
      <div class="mb-3">
        <label class="form-label">Student ID</label>
        <input name="student_id" required type="text" class="form-control" value="<?= htmlspecialchars($_POST['student_id'] ?? '') ?>">
        <div class="invalid-feedback">Please provide your student ID.</div>
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input name="password" required type="password" class="form-control">
        <div class="invalid-feedback">Please enter a password (min 6 characters).</div>
      </div>
      <div class="mb-3">
        <label class="form-label">Confirm Password</label>
        <input name="confirm_password" required type="password" class="form-control">
        <div class="invalid-feedback">Please confirm your password.</div>
      </div>
      <button class="btn btn-primary" type="submit">Register</button>
    </form>
  </div>
</div>
<?php require 'inc/footer.php'; ?>