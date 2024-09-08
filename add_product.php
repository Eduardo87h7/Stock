<?php
require 'config/db.php';

// Verificar si se han enviado datos por POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario
    $nombre = $_POST['nombre'] ?? '';
    $marca = $_POST['marca'] ?? '';
    $modelo = $_POST['modelo'] ?? '';
    $cantidad = $_POST['cantidad'] ?? '';
    $ubicacion = $_POST['ubicacion'] ?? '';

    // Validar los datos
    if (!empty($nombre) && !empty($marca) && !empty($modelo) && !empty($cantidad) && !empty($ubicacion)) {
        // Preparar la consulta SQL
        $sql = "INSERT INTO products (nombre, marca, modelo, cantidad, ubicacion) VALUES (:nombre, :marca, :modelo, :cantidad, :ubicacion)";
        
        // Preparar y ejecutar la consulta
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':nombre' => $nombre,
            ':marca' => $marca,
            ':modelo' => $modelo,
            ':cantidad' => $cantidad,
            ':ubicacion' => $ubicacion,
        ]);

        // Redirigir a la página de gestión de productos
        header('Location: manage_products.php');
        exit();
    } else {
        echo "Todos los campos son obligatorios.";
    }
}
?>
