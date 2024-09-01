<?php
require 'config/db.php';

if (!isset($_GET['id'])) {
    echo json_encode(['error' => 'ID de usuario no proporcionado.']);
    exit();
}

$id = $_GET['id'];
$stmt = $pdo->prepare('SELECT * FROM usuarios WHERE id = :id');
$stmt->execute(['id' => $id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo json_encode(['error' => 'Usuario no encontrado.']);
    exit();
}

echo json_encode($user);
