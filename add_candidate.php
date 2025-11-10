<?php
require 'config.php';
if (!is_logged_in() || current_user()['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = (int)$_POST['user_id'];
    $group_id = $_POST['group_id'] ? (int)$_POST['group_id'] : null;
    $manifesto = $_POST['manifesto'] ?? null;
    // ensure user exists and is role candidate
    $u = $pdo->prepare('SELECT * FROM users WHERE id = ?');
    $u->execute([$user_id]);
    $user = $u->fetch();
    if ($user && $user['role'] === 'candidate') {
        $ins = $pdo->prepare('INSERT INTO candidates (user_id, group_id, manifesto) VALUES (?, ?, ?)');
        $ins->execute([$user_id, $group_id, $manifesto]);
    }
}
header('Location: admin_dashboard.php');
exit;
