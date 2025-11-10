<?php
require 'config.php';
$sql = 'SELECT c.id AS candidate_id, u.name AS candidate_name, g.name AS group_name, COUNT(v.id) AS votes
FROM candidates c
JOIN users u ON u.id = c.user_id
LEFT JOIN `groups` g ON g.id = c.group_id
LEFT JOIN votes v ON v.candidate_id = c.id
GROUP BY c.id ORDER BY votes DESC';
$rows = $pdo->query($sql)->fetchAll();
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Results</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h2>Election Results</h2>
        <p><a href="index.php">Home</a></p>
        <table>
            <thead>
                <tr>
                    <th>Candidate</th>
                    <th>Group</th>
                    <th>Votes</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $r): ?>
                    <tr>
                        <td><?= htmlspecialchars($r['candidate_name']) ?></td>
                        <td><?= htmlspecialchars($r['group_name'] ?? '') ?></td>
                        <td><?= (int)$r['votes'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>

</html>