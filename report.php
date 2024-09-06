<?php
session_start();
require 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

try {
    // Obtener el inventario actual
    $stmt = $pdo->query('SELECT * FROM products');
    $inventory = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Obtener el historial de movimientos
    $stmt = $pdo->query('
        SELECT m.id, p.product_name, m.quantity, m.movement_type, u.username, m.movement_date
        FROM movements m
        JOIN products p ON m.product_id = p.id
        JOIN usuarios u ON m.user_id = u.id
        ORDER BY m.movement_date DESC
    ');
    $movements = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo 'Error en la consulta: ' . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes y Estadísticas</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <!-- Botón Regresar -->
        <div class="mb-3">
            <a href="manage_products.php" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Regresar
            </a>
        </div>

        <h2>Reportes y Estadísticas</h2>
        
        <h3>Inventario Actual</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre del Producto</th>
                    <th>Cantidad</th>
                    <th>Precio</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($inventory as $product): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($product['id']); ?></td>
                        <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                        <td><?php echo htmlspecialchars($product['quantity']); ?></td>
                        <td><?php echo htmlspecialchars($product['price']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <h3>Historial de Movimientos</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Producto</th>
                    <th>Cantidad</th>
                    <th>Tipo de Movimiento</th>
                    <th>Usuario</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($movements as $movement): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($movement['id']); ?></td>
                        <td><?php echo htmlspecialchars($movement['product_name']); ?></td>
                        <td><?php echo htmlspecialchars($movement['quantity']); ?></td>
                        <td><?php echo htmlspecialchars($movement['movement_type']); ?></td>
                        <td><?php echo htmlspecialchars($movement['username']); ?></td>
                        <td><?php echo htmlspecialchars($movement['movement_date']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
