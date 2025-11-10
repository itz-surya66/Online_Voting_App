<?php
require 'config.php';
if (!is_logged_in() || current_user()['role'] !== 'voter') {
    header('Location: login.php');
    exit;
}
$user = current_user();
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['candidate_id'])) {
    header('Location: voter_dashboard.php');
    exit;
}
$candidate_id = (int)$_POST['candidate_id'];
try {
    $pdo->beginTransaction();

    $check = $pdo->prepare('SELECT id FROM votes WHERE voter_id = ? FOR UPDATE');
    $check->execute([$user['id']]);
    if ($check->fetch()) {
        $pdo->rollBack();
        header('Location: voter_dashboard.php');
        exit;
    }

    $c = $pdo->prepare('SELECT id FROM candidates WHERE id = ?');
    $c->execute([$candidate_id]);
    if (!$c->fetch()) {
        $pdo->rollBack();
        header('Location: voter_dashboard.php');
        exit;
    }


    $ins = $pdo->prepare('INSERT INTO votes (voter_id, candidate_id) VALUES (?, ?)');
    $ins->execute([$user['id'], $candidate_id]);
    $pdo->commit();
    header('Location: voter_dashboard.php');
    exit;
} catch (Exception $e) {
    if ($pdo->inTransaction()) $pdo->rollBack();
    die('Vote error: ' . $e->getMessage());
}
