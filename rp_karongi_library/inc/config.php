<?php
// inc/config.php
session_start();

$host = '127.0.0.1';
$db   = 'library_db';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    exit('Database connection failed: ' . $e->getMessage());
}

// flash helper
function flash($name = '', $message = '') {
    if ($message !== '') {
        $_SESSION['flash'][$name] = $message;
    } elseif (isset($_SESSION['flash'][$name])) {
        $m = $_SESSION['flash'][$name];
        unset($_SESSION['flash'][$name]);
        return $m;
    }
    return null;
}
?>