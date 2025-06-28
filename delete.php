<?php
require_once 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$stmt = $pdo->prepare("DELETE FROM posts WHERE id = ? AND user_id = ?");
$stmt->execute([$_GET['id'], $_SESSION['user_id']]);

header("Location: index.php");
exit;
?>