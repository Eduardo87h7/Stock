<?php
session_start();
require 'config/db.php';

$response = ['success' => false, 'message' => ''];

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    $response['message'] = 'No autorizado';
} elseif (isset($_POST['id'])) {
    $id = $_POST['id'];

    try {
        $stmt = $pdo->prepare('DELETE FROM usuarios WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $response['success'] = true;
        $response['message'] = 'Usuario eliminado con éxito.';
    } catch (Exception $e) {
        $response['message'] = 'Error al eliminar el usuario: ' . $e->getMessage();
    }
} else {
    $response['message'] = 'ID de usuario no proporcionado.';
}

header('Content-Type: application/json');
echo json_encode($response);
?>
