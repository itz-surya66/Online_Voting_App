<?php
require 'config.php';
if (!is_logged_in() || current_user()['role'] !== 'voter') {
    header('Location: login.php');
    exit;
}
$user = current_user();


$candidates = $pdo->query('SELECT c.id AS candidate_id, u.name AS candidate_name, g.name AS group_name, c.manifesto
FROM candidates c
JOIN users u ON u.id = c.user_id
LEFT JOIN `groups` g ON g.id = c.group_id')->fetchAll();


$stmt = $pdo->prepare('SELECT id FROM votes WHERE voter_id = ?');
$stmt->execute([$user['id']]);
$has_voted = (bool)$stmt->fetch();
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Voter Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h2>Welcome, <?= htmlspecialchars($user['name']) ?></h2>
        <p><a href="logout.php">Logout</a> | <a href="results.php">View Results</a></p>
        <?php if ($has_voted): ?>
            <p class="info">You have already voted. Thank you.</p>
        <?php else: ?>
            <h3>Cast your vote</h3>
            <form method="post" action="vote.php">
                <?php foreach ($candidates as $c): ?>
                    <div class="candidate">
                        <input type="radio" name="candidate_id" value="<?= $c['candidate_id'] ?>" required>
                        <strong><?= htmlspecialchars($c['candidate_name']) ?></strong>
                        <?php if ($c['group_name']): ?> <em>(<?= htmlspecialchars($c['group_name']) ?>)</em><?php endif; ?>
                        <p><?= nl2br(htmlspecialchars($c['manifesto'])) ?></p>
                    </div>
                <?php endforeach; ?>
                <button type="submit">Vote</button>
            </form>
        <?php endif; ?>


    </div>
</body>

</html>