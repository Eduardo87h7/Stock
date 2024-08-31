<?php
require 'config/db.php';

$username = 'admin'; // Cambia esto por el nombre de usuario
$password = 'admin'; // Contraseña en texto plano

// Consulta para obtener el hash de la contraseña almacenada
$stmt = $pdo->prepare('SELECT password FROM usuarios WHERE username = :username');
$stmt->execute(['username' => $username]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    // Verifica si el hash coincide con la contraseña en texto plano
    if (password_verify($password, $user['password'])) {
        echo 'La contraseña es correcta.';
    } else {
        echo 'La contraseña no es válida.';
    }
} else {
    echo 'Usuario no encontrado.';
}
?>
