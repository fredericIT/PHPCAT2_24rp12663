<?php
require 'inc/config.php';
if (!isset($_SESSION['user'])) {
    header('Location: login.php'); exit;
}
require 'inc/header.php';
$userId = $_SESSION['user']['id'];
$stmt = $pdo->prepare(
  "SELECT bb.*, b.title, b.author FROM borrowed_books bb
   JOIN books b ON bb.book_id = b.book_id
   WHERE bb.student_id = ? ORDER BY bb.borrow_date DESC"
);
$stmt->execute([$userId]);
$borrowed = $stmt->fetchAll();
?>
<h2>Welcome, <?= htmlspecialchars($_SESSION['user']['username']) ?></h2>
<div class="card mb-4">
  <div class="card-body">
    <h5 class="card-title">Your Borrowed Books</h5>
    <?php if ($borrowed): ?>
      <table class="table">
        <thead><tr><th>Title</th><th>Author</th><th>Borrowed</th><th>Return</th><th>Status</th><th>Action</th></tr></thead>
        <tbody>
        <?php foreach ($borrowed as $b): ?>
          <tr>
            <td><?= htmlspecialchars($b['title']) ?></td>
            <td><?= htmlspecialchars($b['author']) ?></td>
            <td><?= htmlspecialchars($b['borrow_date']) ?></td>
            <td><?= htmlspecialchars($b['return_date'] ?? '-') ?></td>
            <td><?= htmlspecialchars($b['status']) ?></td>
            <td>
              <?php if ($b['status'] === 'borrowed'): ?>
                <a href="return_book.php?id=<?= $b['id'] ?>" class="btn btn-sm btn-success">Return</a>
              <?php else: ?>
                -
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    <?php else: ?>
      <p>You have no borrowed books.</p>
    <?php endif; ?>
  </div>
</div>
<?php require 'inc/footer.php'; ?>