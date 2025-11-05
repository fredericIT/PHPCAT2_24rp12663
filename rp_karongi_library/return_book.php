<?php
require 'inc/config.php';
if (!isset($_SESSION['user'])) { header('Location: login.php'); exit; }
$id = (int)($_GET['id'] ?? 0);
$userId = $_SESSION['user']['id'];
if ($id <= 0) { flash('error', 'Invalid id'); header('Location: dashboard.php'); exit; }
try {
    $pdo->beginTransaction();
    $stmt = $pdo->prepare("SELECT * FROM borrowed_books WHERE id = ? AND student_id = ? AND status = 'borrowed' FOR UPDATE");
    $stmt->execute([$id, $userId]);
    $rec = $stmt->fetch();
    if (!$rec) throw new Exception('Borrow record not found.');
    $upd = $pdo->prepare("UPDATE borrowed_books SET status = 'returned', return_date = ? WHERE id = ?");
    $upd->execute([date('Y-m-d'), $id]);
    $upd2 = $pdo->prepare("UPDATE books SET available_status = 1 WHERE book_id = ?");
    $upd2->execute([$rec['book_id']]);
    $pdo->commit();
    flash('success', 'Book returned. Thank you.');
} catch (Exception $e) {
    $pdo->rollBack();
    flash('error', 'Unable to return: ' . $e->getMessage());
}
header('Location: dashboard.php');
exit;