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
    <link rel="stylesheet" href="./css/products.css">
    
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
           
            <h2 class="mb-1">Gestión de Productos</h2>
            <!-- Botón Agregar Producto -->
            
            <!-- Barra de Búsqueda -->
            <div class="row mb-2">
                <div class="col-md-5">
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
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addProductModal">
                <i class="bi bi-file-earmark-plus"></i>
                </button>
            </div>
        </div>

        <!-- Tabla -->
        <div class="table-responsive">
            <table class="table table-hover table-bordered" id="productTable">
                <thead class="thead-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Nombre del Producto</th>
                        <th scope="col">Marca</th>
                        <th scope="col">Modelo</th>
                        <th scope="col">Cantidad</th>
                        <th scope="col">Ubicación</th>
                        <th scope="col">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($products as $product): ?>
    <tr>
        <td><?php echo htmlspecialchars($product['id']); ?></td>
        <td><?php echo htmlspecialchars($product['nombre']); ?></td>
        <td><?php echo htmlspecialchars($product['marca']); ?></td>
        <td><?php echo htmlspecialchars($product['modelo']); ?></td>
        <td class="<?php echo ($product['cantidad'] >= 10) ? 'quantity-high' : (($product['cantidad'] >= 5) ? 'quantity-medium' : 'quantity-low'); ?>">
            <?php echo htmlspecialchars($product['cantidad']); ?>
        </td>
        <td><?php echo htmlspecialchars($product['ubicacion']); ?></td>
        <td>
            <button type="button" class="btn btn-outline-warning btn-sm edit-button" 
                    data-id="<?php echo $product['id']; ?>" 
                    data-nombre="<?php echo htmlspecialchars($product['nombre']); ?>" 
                    data-marca="<?php echo htmlspecialchars($product['marca']); ?>" 
                    data-modelo="<?php echo htmlspecialchars($product['modelo']); ?>" 
                    data-cantidad="<?php echo htmlspecialchars($product['cantidad']); ?>" 
                    data-ubicacion="<?php echo htmlspecialchars($product['ubicacion']); ?>">
                Editar
            </button>
            <button type="button" class="btn btn-outline-danger btn-sm delete-button" 
                    data-id="<?php echo $product['id']; ?>">
                Borrar
            </button>
        </td>
    </tr>
<?php endforeach; ?>

                </tbody>
            </table>
        </div>

        <!-- Paginate -->
        <div class="pagination-container">
            <nav aria-label="Page navigation">
                <ul class="pagination">
                    <!-- Pagination items here -->
                </ul>
            </nav>
        </div>
    </div>
</div>

<!-- Modal para agregar producto -->
<div class="modal fade" id="addProductModal" tabindex="-1" role="dialog" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addProductModalLabel">Agregar Producto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="add_product.php" method="post">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="nombre">Nombre del Producto</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="marca">Marca</label>
                        <input type="text" class="form-control" id="marca" name="marca" required>
                    </div>
                    <div class="form-group">
                        <label for="modelo">Modelo</label>
                        <input type="text" class="form-control" id="modelo" name="modelo" required>
                    </div>
                    <div class="form-group">
                        <label for="cantidad">Cantidad</label>
                        <input type="number" class="form-control" id="cantidad" name="cantidad" required>
                    </div>
                    <div class="form-group">
                        <label for="ubicacion">Ubicación</label>
                        <input type="text" class="form-control" id="ubicacion" name="ubicacion" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Agregar Producto</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para editar producto -->
<div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProductModalLabel">Editar Producto</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="update_product.php" method="post">
                <input type="hidden" id="edit_id" name="id">
                <div class="modal-body">
                    <div class="form-group">
                        <label for="edit_nombre">Nombre del Producto</label>
                        <input type="text" class="form-control" id="edit_nombre" name="nombre" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_marca">Marca</label>
                        <input type="text" class="form-control" id="edit_marca" name="marca" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_modelo">Modelo</label>
                        <input type="text" class="form-control" id="edit_modelo" name="modelo" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_cantidad">Cantidad</label>
                        <input type="number" class="form-control" id="edit_cantidad" name="cantidad" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_ubicacion">Ubicación</label>
                        <input type="text" class="form-control" id="edit_ubicacion" name="ubicacion" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Actualizar Producto</button>
                </div>
            </form>
        </div>
    </div>
</div>


<!-- Scripts de Bootstrap y jQuery -->
<script src="./js/custom.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="custom.js"></script>
</body>
</html>
