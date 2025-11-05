<?php
require 'inc/config.php';
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') { header('Location: login.php'); exit; }
$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) { header('Location: books.php'); exit; }
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = trim($_POST['title'] ?? '');
  $author = trim($_POST['author'] ?? '');
  $category = trim($_POST['category'] ?? '');
  $available = isset($_POST['available']) ? 1 : 0;
  $stmt = $pdo->prepare("UPDATE books SET title=?, author=?, category=?, available_status=? WHERE book_id=?");
  $stmt->execute([$title, $author, $category, $available, $id]);
  flash('success','Book updated.');
  header('Location: books.php'); exit;
}
$stmt = $pdo->prepare("SELECT * FROM books WHERE book_id=?");
$stmt->execute([$id]);
$book = $stmt->fetch();
require 'inc/header.php';
?>
<form method="post">
  <div class="mb-3"><label>Title</label><input name="title" class="form-control" value="<?= htmlspecialchars($book['title']) ?>" required></div>
  <div class="mb-3"><label>Author</label><input name="author" class="form-control" value="<?= htmlspecialchars($book['author']) ?>" required></div>
  <div class="mb-3"><label>Category</label><input name="category" class="form-control" value="<?= htmlspecialchars($book['category']) ?>"></div>
  <div class="mb-3"><label><input type="checkbox" name="available" <?= $book['available_status'] ? 'checked' : '' ?>> Available</label></div>
  <button class="btn btn-primary">Save</button>
</form>
<?php require 'inc/footer.php'; ?>