<?php
session_start();
require 'config/db.php';

// Verificar si el usuario está autenticado y es administrador
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: dashboard.php');
    exit();
}

// Manejar la actualización del usuario
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['username'], $_POST['email'], $_POST['role'])) {
    $id = (int)$_POST['id'];
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $role = trim($_POST['role']);

    $error_message = '';

    // Validar datos
    if (empty($username) || empty($email) || empty($role)) {
        $error_message = 'Todos los campos son obligatorios.';
    } else {
        // Verificar si el correo electrónico ya existe para otro usuario
        $stmt = $pdo->prepare('SELECT id FROM usuarios WHERE email = :email AND id != :id');
        $stmt->execute(['email' => $email, 'id' => $id]);
        $existingEmail = $stmt->fetch();

        if ($existingEmail) {
            $error_message = 'El correo electrónico ya está en uso.';
        } else {
            // Actualizar usuario
            $stmt = $pdo->prepare('UPDATE usuarios SET username = :username, email = :email, role = :role WHERE id = :id');
            $stmt->execute([
                'username' => $username,
                'email' => $email,
                'role' => $role,
                'id' => $id
            ]);
        }
    }

    // Enviar respuesta en formato JSON
    header('Content-Type: application/json');
    echo json_encode(['error' => $error_message]);
    exit();
}
?>
