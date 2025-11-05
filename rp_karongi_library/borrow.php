<?php
require 'inc/config.php';
if (!isset($_SESSION['user'])) {
    flash('error', 'Please login to borrow books.');
    header('Location: login.php'); exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $book_id = (int)($_POST['book_id'] ?? 0);
    $student_id = $_SESSION['user']['id'];
    if ($book_id <= 0) {
        flash('error', 'Invalid book ID.');
        header('Location: books.php'); exit;
    }
    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare("SELECT available_status FROM books WHERE book_id = ? FOR UPDATE");
        $stmt->execute([$book_id]);
        $book = $stmt->fetch();
        if (!$book) throw new Exception('Book not found.');
        if (!$book['available_status']) throw new Exception('Book is not available.');
        $borrow_date = date('Y-m-d');
        $return_date = date('Y-m-d', strtotime('+14 days'));
        $ins = $pdo->prepare("INSERT INTO borrowed_books (student_id, book_id, borrow_date, return_date) VALUES (?, ?, ?, ?)");
        $ins->execute([$student_id, $book_id, $borrow_date, $return_date]);
        $upd = $pdo->prepare("UPDATE books SET available_status = 0 WHERE book_id = ?");
        $upd->execute([$book_id]);
        $pdo->commit();
        flash('success', 'Book borrowed successfully.');
    } catch (Exception $e) {
        $pdo->rollBack();
        flash('error', 'Unable to borrow: ' . $e->getMessage());
    }
    header('Location: dashboard.php'); exit;
}
header('Location: books.php'); exit;