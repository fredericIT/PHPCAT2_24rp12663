<?php
require 'inc/config.php';
require 'inc/header.php';

$search = trim($_GET['q'] ?? '');
$category = trim($_GET['category'] ?? '');

// Base SQL query
$sql = "SELECT * FROM books WHERE 1=1";
$params = [];

// Search filter
if ($search !== '') {
    $sql .= " AND (title LIKE ? OR author LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

// Category filter
if ($category !== '') {
    $sql .= " AND category = ?";
    $params[] = $category;
}

// Prepare and execute
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$books = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Get distinct categories for dropdown
$catStmt = $pdo->query("SELECT DISTINCT category FROM books");
$categories = $catStmt->fetchAll(PDO::FETCH_COLUMN);
?>

<h2>Books</h2>
<form class="row g-2 mb-3" method="get">
  <div class="col-md-5">
    <input name="q" value="<?= htmlspecialchars($search) ?>" class="form-control" placeholder="Search by title or author">
  </div>
  <div class="col-md-4">
    <select name="category" class="form-select">
      <option value="">All categories</option>
      <?php foreach ($categories as $c): ?>
        <option value="<?= htmlspecialchars($c) ?>" <?= $c === $category ? 'selected' : '' ?>>
          <?= htmlspecialchars($c) ?>
        </option>
      <?php endforeach; ?>
    </select>
  </div>
  <div class="col-md-3">
    <button class="btn btn-primary">Search</button>
  </div>
</form>

<table class="table table-striped">
  <thead>
    <tr>
      <th>Title</th>
      <th>Author</th>
      <th>Category</th>
      <th>Available</th>
      <th>Action</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($books as $b): ?>
      <tr>
        <td><?= htmlspecialchars($b['title']) ?></td>
        <td><?= htmlspecialchars($b['author']) ?></td>
        <td><?= htmlspecialchars($b['category']) ?></td>
        <td><?= $b['available_status'] ? 'Yes' : 'No' ?></td>
        <td>
          <?php if (isset($_SESSION['user']) && $b['available_status']): ?>
            <form method="post" action="borrow.php" style="display:inline">
              <input type="hidden" name="book_id" value="<?= $b['book_id'] ?>">
              <button class="btn btn-sm btn-success">Borrow</button>
            </form>
          <?php else: ?>
            <button class="btn btn-sm btn-secondary" disabled>Not available</button>
          <?php endif; ?>

          <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
            <a class="btn btn-sm btn-warning" href="edit_book.php?id=<?= $b['book_id'] ?>">Edit</a>
            <a class="btn btn-sm btn-danger" href="delete_book.php?id=<?= $b['book_id'] ?>" onclick="return confirm('Delete this book?')">Delete</a>
          <?php endif; ?>
        </td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<?php require 'inc/footer.php'; ?>
