<?php
session_start();
require 'config/db.php';

$success_message = '';
$error_message = '';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];
    $movement_type = $_POST['movement_type'];

    try {
        // Registrar el movimiento
        $stmt = $pdo->prepare('INSERT INTO movements (product_id, quantity, movement_type, user_id, created_at) VALUES (:product_id, :quantity, :movement_type, :user_id, NOW())');
        $stmt->execute([
            'product_id' => $product_id,
            'quantity' => $quantity,
            'movement_type' => $movement_type,
            'user_id' => $_SESSION['user_id']
        ]);

        // Actualizar la cantidad en productos
        if ($movement_type == 'entrada') {
            $stmt = $pdo->prepare('UPDATE products SET quantity = quantity + :quantity WHERE id = :product_id');
        } else {
            $stmt = $pdo->prepare('UPDATE products SET quantity = quantity - :quantity WHERE id = :product_id');
        }
        $stmt->execute([
            'quantity' => $quantity,
            'product_id' => $product_id
        ]);

        $success_message = 'Movimiento registrado con éxito.';
    } catch (Exception $e) {
        $error_message = 'Error al registrar el movimiento: ' . $e->getMessage();
    }
}

// Obtener productos para el formulario
$stmt = $pdo->query('SELECT * FROM products');
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Movimiento</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Registrar Movimiento</h2>
        <?php if ($success_message): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <?php if ($error_message): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <form action="record_movement.php" method="POST">
            <div class="form-group">
                <label for="product_id">Producto:</label>
                <select name="product_id" class="form-control" required>
                    <?php foreach ($products as $product): ?>
                        <option value="<?php echo $product['id']; ?>"><?php echo htmlspecialchars($product['product_name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="quantity">Cantidad:</label>
                <input type="number" name="quantity" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="movement_type">Tipo de Movimiento:</label>
                <select name="movement_type" class="form-control" required>
                    <option value="entrada">Entrada</option>
                    <option value="salida">Salida</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Registrar Movimiento</button>
        </form>
    </div>
</body>
</html>
