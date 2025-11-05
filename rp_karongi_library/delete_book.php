<?php
require 'inc/config.php';
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') { header('Location: login.php'); exit; }
$id = (int)($_GET['id'] ?? 0);
if ($id > 0) {
  $stmt = $pdo->prepare("DELETE FROM books WHERE book_id=?");
  $stmt->execute([$id]);
  flash('success','Book deleted.');
}
header('Location: books.php'); exit;