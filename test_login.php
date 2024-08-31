<?php
// test_login.php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'config/db.php';

$username = 'admin1'; // Cambia esto por el nombre de usuario
$password = 'admin'; // Contraseña en texto plano

// Consulta para obtener el usuario
$stmt = $pdo->prepare('SELECT password FROM usuarios WHERE username = :username');
$stmt->execute(['username' => $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {
    echo 'La contraseña es correcta.';
} else {
    echo 'Usuario o contraseña incorrectos.';
}
?>
