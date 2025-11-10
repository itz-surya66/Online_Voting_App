<?php
require 'config.php';
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password'])) {
        unset($user['password']);
        $_SESSION['user'] = $user;
        // redirect based on role
        if ($user['role'] === 'admin') header('Location: admin_dashboard.php');
        elseif ($user['role'] === 'candidate') header('Location: candidate_dashboard.php');
        else header('Location: voter_dashboard.php');
        exit;
    } else {
        $errors[] = 'Invalid credentials';
    }
}
?>
<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <h2>Login</h2>
        <?php if (!empty($_GET['registered'])) echo '<p class="success">Registered successfully. Please login.</p>'; ?>
        <?php if ($errors) foreach ($errors as $err) echo "<p class=error>$err</p>"; ?>
        <form method="post">
            <label>Email</label>
            <input type="email" name="email" required>
            <label>Password</label>
            <input type="password" name="password" required>
            <button type="submit">Login</button>
        </form>
        <p><a href="register.php">Register</a></p>
    </div>
</body>

</html>