<?php
require 'config.php';


$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $role = $_POST['role'] ?? 'voter';


    if (!$name) $errors[] = 'Name required';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Valid email required';
    if (strlen($password) < 6) $errors[] = 'Password at least 6 chars';


    if (empty($errors)) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        try {
            $ins = $pdo->prepare('INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)');
            $ins->execute([$name, $email, $hash, $role]);
            header('Location: login.php?registered=1');
            exit;
        } catch (Exception $e) {
            $errors[] = 'Email may already be registered.';
        }
    }
}
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h2>Register</h2>
        <?php if ($errors) foreach ($errors as $err) echo "<p class=error>$err</p>"; ?>
        <form method="post">
            <label>Name</label>
            <input type="text" name="name" required>
            <label>Email</label>
            <input type="email" name="email" required>
            <label>Password</label>
            <input type="password" name="password" required>
            <label>Role</label>
            <select name="role">
                <option value="voter">Voter</option>
                <option value="candidate">Candidate</option>
            </select>
            <button type="submit">Register</button>
        </form>
        <p><a href="login.php">Login</a></p>
    </div>
</body>

</html>