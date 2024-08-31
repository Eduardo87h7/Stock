<?php
// register.php
require 'db_connection.php';

header('Content-Type: application/json');

$response = array();
$response['success'] = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'];

    // Verificar duplicación de datos
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->execute([$username, $email]);
    $user = $stmt->fetch();

    if ($user) {
        if ($user['username'] === $username) {
            $response['error'] = 'El nombre de usuario ya existe. Por favor, elige otro nombre.';
        } elseif ($user['email'] === $email) {
            $response['error'] = 'El correo electrónico ya está registrado. Por favor, utiliza otro correo.';
        }
    } else {
        $stmt = $conn->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$username, $email, $password, $role])) {
            $response['success'] = true;
        } else {
            $response['error'] = 'Error al registrar el usuario. Por favor, intenta de nuevo.';
        }
    }
}

echo json_encode($response);
?>
