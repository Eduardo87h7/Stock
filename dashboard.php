<?php
session_start();
require 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$role = $_SESSION['role'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Inventario</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">Inicio</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="create_product.php">Crear Producto</a>
                </li>
                <?php if ($role === 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="manage_users.php">Gestionar Usuarios</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
        <span class="navbar-text">
            Bienvenido, <?php echo $_SESSION['username']; ?>
        </span>
    </nav>

    <div class="container mt-5">
        <h1>Panel de Control</h1>
        <p>Bienvenido al sistema de control de inventario.</p>
        <!-- Aquí puedes agregar más funcionalidades específicas -->
    </div>
</body>
</html>
