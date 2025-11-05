<?php
require 'inc/config.php';
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') { header('Location: login.php'); exit; }
require 'inc/header.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = trim($_POST['title'] ?? '');
  $author = trim($_POST['author'] ?? '');
  $category = trim($_POST['category'] ?? '');
  $available = isset($_POST['available']) ? 1 : 0;
  $errors = [];
  if ($title === '' || $author === '') $errors[] = "Title and author required.";
  if (empty($errors)) {
    $stmt = $pdo->prepare("INSERT INTO books (title, author, category, available_status) VALUES (?, ?, ?, ?)");
    $stmt->execute([$title, $author, $category, $available]);
    flash('success', 'Book added successfully.');
    header('Location: books.php'); exit;
  }
}
?>
<?php if (!empty($errors)): ?><div class="alert alert-danger"><?php foreach ($errors as $e) echo "<div>$e</div>"; ?></div><?php endif; ?>
<form method="post">
  <div class="mb-3"><label>Title</label><input name="title" class="form-control" required></div>
  <div class="mb-3"><label>Author</label><input name="author" class="form-control" required></div>
  <div class="mb-3"><label>Category</label><input name="category" class="form-control"></div>
  <div class="mb-3"><label><input type="checkbox" name="available" checked> Available</label></div>
  <button class="btn btn-primary">Add Book</button>
</form>
<?php require 'inc/footer.php'; ?>