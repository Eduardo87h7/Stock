<?php
session_start();
require 'config/db.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Obtener todos los productos de la base de datos
$stmt = $pdo->query('SELECT * FROM products');
$products = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Productos</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="custom.css">
    <style>
        body {
            overflow: hidden; /* Desactiva el scroll de la página */
            display: flex;
        }

        .sidebar {
            width: 250px;
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background-color: #343a40;
            padding-top: 20px;
            z-index: 1000;
        }

        .sidebar a {
            display: block;
            color: #c2c7d0;
            padding: 10px 20px;
            text-decoration: none;
        }

        .sidebar a:hover {
            background-color: #495057;
            color: white;
        }

        .main-content {
            margin-left: 250px; /* Ajusta según el ancho de la barra lateral */
            flex: 1;
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden; /* Evita que el contenido principal haga scroll */
        }

        .table-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden; /* Evita que la tabla cambie el tamaño del contenedor */
            padding: 20px;
        }

        .table-header {
            margin-bottom: 10px;
        }

        .table-responsive {
            flex: 1;
            overflow: auto; /* Permite scroll interno solo en la tabla */
        }

        .table {
            margin-bottom: 0; /* Asegura que no haya espacio extra debajo de la tabla */
            width: 100%; /* Asegura que la tabla ocupe todo el ancho disponible */
        }

        .pagination-container {
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <a href="manage_products.php"><i class="bi bi-box-seam"></i> Productos</a>
    <a href="manage_users.php"><i class="bi bi-people"></i> Usuarios</a>
    <a href="report.php"><i class="bi bi-graph-up"></i> Reportes</a>
    <a href="settings.php"><i class="bi bi-gear"></i> Configuración</a>
    <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Cerrar Sesión</a>
</div>

<!-- Main Content -->
<div class="main-content">
    <div class="table-container">
        <div class="table-header">
            <h2 class="mb-4">Gestión de Productos</h2>
            <!-- Barra de Búsqueda -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <input type="text" id="searchInput" class="form-control" placeholder="Buscar producto...">
                </div>
                <div class="col-md-6 text-right">
                    <label for="entriesCount" class="mr-2">Mostrar</label>
                    <select id="entriesCount" class="custom-select w-auto">
                        <option value="5">5</option>
                        <option value="10" selected>10</option>
                        <option value="25">25</option>
                        <option value="50">50</option>
                    </select>
                    <span> resultados</span>
                </div>
            </div>
        </div>

        <!-- Tabla -->
        <div class="table-responsive">
            <table class="table table-hover table-bordered" id="productTable">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nombre del Producto</th>
                        <th scope="col">Cantidad</th>
                        <th scope="col">Precio</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($product['id']); ?></td>
                            <td><?php echo htmlspecialchars($product['product_name']); ?></td>
                            <td><?php echo htmlspecialchars($product['quantity']); ?></td>
                            <td><?php echo htmlspecialchars($product['price']); ?></td>
                            <td>
                                <a href="edit_product.php?id=<?php echo $product['id']; ?>" class="btn btn-outline-warning btn-sm">
                                    <i class="bi bi-pencil"></i> Editar
                                </a>
                                <a href="delete_product.php?id=<?php echo $product['id']; ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar este producto?');">
                                    <i class="bi bi-trash"></i> Eliminar
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="pagination-container mt-3">
            <nav>
                <ul class="pagination justify-content-center">
                    <!-- Paginación generada dinámicamente -->
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
$(document).ready(function () {
    let currentPage = 1;

    // Actualiza la tabla al buscar o cambiar el número de resultados por página
    function updateTable() {
        const searchTerm = $('#searchInput').val().toLowerCase();
        const selectedRowsPerPage = parseInt($('#entriesCount').val());
        let filteredRows = $('#productTable tbody tr').filter(function () {
            return $(this).text().toLowerCase().includes(searchTerm);
        });

        // Ocultar todas las filas
        $('#productTable tbody tr').hide();
        filteredRows.slice((currentPage - 1) * selectedRowsPerPage, currentPage * selectedRowsPerPage).show();

        // Actualizar la paginación
        updatePagination(filteredRows.length);
    }

    // Actualiza la paginación en función de los resultados filtrados
    function updatePagination(filteredCount) {
        const pages = Math.ceil(filteredCount / $('#entriesCount').val());
        $('.pagination').empty();

        for (let i = 1; i <= pages; i++) {
            $('.pagination').append(`<li class="page-item ${i === currentPage ? 'active' : ''}"><a class="page-link" href="#">${i}</a></li>`);
        }
    }

    // Maneja el evento de cambio en la selección de resultados por página y búsqueda
    $('#searchInput, #entriesCount').on('input change', function () {
        currentPage = 1;
        updateTable();
    });

    // Maneja el evento de clic en los enlaces de la paginación
    $(document).on('click', '.page-link', function (e) {
        e.preventDefault();
        currentPage = parseInt($(this).text());
        updateTable();
    });

    updateTable();
});
</script>
</body>
</html>
