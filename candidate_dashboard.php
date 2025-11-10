<?php
require 'config.php';
if (!is_logged_in() || current_user()['role'] !== 'candidate') {
    header('Location: login.php');
    exit;
}
$user = current_user();
// fetch candidate record
$stmt = $pdo->prepare('SELECT * FROM candidates WHERE user_id = ?');
$stmt->execute([$user['id']]);
$candidate = $stmt->fetch();
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Candidate Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h2>Candidate Area</h2>
        <p><a href="logout.php">Logout</a> | <a href="results.php">View Results</a></p>
        <?php if ($candidate): ?>
            <h3>Your Profile</h3>
            <p><strong>Name:</strong> <?= htmlspecialchars($user['name']) ?></p>
            <p><strong>Manifesto:</strong><br><?= nl2br(htmlspecialchars($candidate['manifesto'])) ?></p>
        <?php else: ?>
            <p class="info">You are not added as a candidate by admin yet.</p>
        <?php endif; ?>
    </div>
</body>

</html>