<?php
require 'config.php';
if (!is_logged_in() || current_user()['role'] !== 'admin') {
    header('Location: login.php');
    exit;
}


// fetch groups and candidates
$groups = $pdo->query('SELECT * FROM `groups` ORDER BY name')->fetchAll();
$candidates = $pdo->query('SELECT c.id, u.name AS candidate_name, g.name AS group_name, c.manifesto
FROM candidates c JOIN users u ON u.id = c.user_id LEFT JOIN `groups` g ON g.id = c.group_id')->fetchAll();
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h2>Admin Panel</h2>
        <p><a href="logout.php">Logout</a> | <a href="results.php">View Results</a></p>


        <h3>Add Group</h3>
        <form method="post" action="add_group.php"><input name="name" placeholder="Group name" required><br><textarea name="description" placeholder="Description"></textarea><br><button>Add Group</button></form>


        <h3>Add Candidate (must be registered as candidate)</h3>
        <form method="post" action="add_candidate.php">
            <label>User ID (candidate must register first)</label>
            <input name="user_id" type="number" required>
            <label>Group ID (optional)</label>
            <input name="group_id" type="number">
            <label>Manifesto</label>
            <textarea name="manifesto"></textarea>
            <button>Add Candidate</button>
        </form>


        <h3>Existing Groups</h3>
        <ul>
            <?php foreach ($groups as $g) echo "<li>" . htmlspecialchars($g['name']) . " (ID: {$g['id']})</li>"; ?>
        </ul>

        <h3>Registered Candidates</h3>
        <ul>
            <?php foreach ($candidates as $c) echo "<li>" . htmlspecialchars($c['candidate_name']) . " (Group: " . htmlspecialchars($c['group_name'] ?? '') . ") - ID: {$c['id']}</li>"; ?>
        </ul>


    </div>
</body>

</html>