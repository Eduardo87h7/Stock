<?php
require 'config/db.php';

// Datos del nuevo usuario
$username = 'eduardo';
$email = 'eduardo@example.com';
$password = 'admin'; // Contraseña en texto plano
$role = 'admin';

// Genera el hash de la contraseña
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Inserta el usuario en la base de datos
$stmt = $pdo->prepare('INSERT INTO usuarios (username, email, password, role) VALUES (:username, :email, :password, :role)');
$stmt->execute([
    'username' => $username,
    'email' => $email,
    'password' => $hashed_password,
    'role' => $role
]);

echo 'Usuario insertado con éxito.';
?>
