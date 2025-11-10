<?php
require 'config.php';

$name = 'Administrator';
$email = 'admin@example.com';
$password = password_hash('admin123', PASSWORD_DEFAULT);
$role = 'admin';

$stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
$stmt->execute([$email]);
if ($stmt->fetch()) {
    echo "Admin already exists.\n";
    exit;
}


$insert = $pdo->prepare('INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, ?)');
$insert->execute([$name, $email, $password, $role]);


echo "Admin created: $email with password admin123. Please change password after first login.\n";
