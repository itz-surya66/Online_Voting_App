<?php
require 'config.php';
if (!is_logged_in() || current_user()['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $desc = trim($_POST['description']);
    if ($name) {
        $stmt = $pdo->prepare('INSERT INTO `groups` (name, description) VALUES (?, ?)');
        $stmt->execute([$name, $desc]);
    }
}
header('Location: admin_dashboard.php');
exit;
