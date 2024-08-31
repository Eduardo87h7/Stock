<?php
session_start();
require 'config/db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: dashboard.php');
    exit();
}

$success_message = '';
$error_message = '';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $product_name = $_POST['product_name'];
        $quantity = $_POST['quantity'];
        $price = $_POST['price'];

        try {
            $stmt = $pdo->prepare('UPDATE products SET product_name = :product_name, quantity = :quantity, price = :price WHERE id = :id');
            $stmt->execute([
                'product_name' => $product_name,
                'quantity' => $quantity,
                'price' => $price,
                'id' => $id
            ]);
            $success_message = 'Producto actualizado con éxito.';
        } catch (Exception $e) {
            $error_message = 'Error al actualizar el producto: ' . $e->getMessage();
        }
    }

    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $product = $stmt->fetch();
} else {
    header('Location: manage_products.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Producto</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Editar Producto</h2>
        <?php if ($success_message): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <?php if ($error_message): ?>
            <div class="alert alert-danger"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <form action="edit_product.php?id=<?php echo $product['id']; ?>" method="POST">
            <div class="form-group">
                <label for="product_name">Nombre del Producto:</label>
                <input type="text" name="product_name" class="form-control" value="<?php echo htmlspecialchars($product['product_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="quantity">Cantidad:</label>
                <input type="number" name="quantity" class="form-control" value="<?php echo htmlspecialchars($product['quantity']); ?>" required>
            </div>
            <div class="form-group">
                <label for="price">Precio:</label>
                <input type="number" step="0.01" name="price" class="form-control" value="<?php echo htmlspecialchars($product['price']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar Producto</button>
        </form>
    </div>
</body>
</html>
