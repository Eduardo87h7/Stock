<?php
session_start();
require 'config/db.php';

// Verificar si el usuario está autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Verificar el rol del usuario
$role = $_SESSION['role'];

if ($role !== 'admin') {
    // Si el usuario no es admin, muestra el mensaje y el botón de regreso
    ?>
    <!DOCTYPE html>
    <html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Acceso Denegado</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css">
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <div class="container mt-5">
            <div class="alert alert-danger" role="alert">
                <h4 class="alert-heading">Acceso Denegado</h4>
                <p>No tienes permisos de administrador para acceder a esta página.</p>
                <hr>
                <a href="index.php" class="btn btn-primary">Regresar al Panel de Control</a>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit();
}

// Obtener todos los reportes de la base de datos
$stmt = $pdo->query('
    SELECT r.id, u.username AS usuario_nombre, p.nombre AS producto_nombre, r.accion AS accion,
           r.campo_modificado_anterior, r.campo_modificado_nuevo, r.fecha_modificacion
    FROM reportes r
    JOIN usuarios u ON r.usuario_id = u.id
    JOIN products p ON r.producto_id = p.id
    ORDER BY r.fecha_modificacion DESC
');
$reportes = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reportes de Modificaciones</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.0/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="custom.css">
    <style>
        /* Estilo del Header Lateral */
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

        .sidebar .list-group-item {
            border: none;
            background-color: #343a40;
            color: #adb5bd;
        }

        .sidebar .list-group-item:hover {
            background-color: #495057;
            color: white;
        }

        .content {
            margin-left: 250px;
            padding: 20px;
        }

        /* Ajustes para pantallas pequeñas */
        @media (max-width: 768px) {
            .sidebar {
                display: none; /* Ocultar el sidebar */
            }
            .content {
                margin-left: 0; /* Ajustar el margen del contenido */
            }
  /* Reducir el tamaño de la fuente en dispositivos móviles */
  .table {
            font-size: 12px;
            /* Reduce el tamaño de la fuente de la tabla */
        }

        /* Ajustar las celdas para que no se desborden */
        .table td,
        .table th {
            white-space: nowrap;
            /* Evita que el contenido se divida en varias líneas */
            overflow: hidden;
            text-overflow: ellipsis;
            /* Agrega "..." si el texto es demasiado largo */
        }

        /* Quitar padding innecesario */
        .table th,
        .table td {
            padding: 0.5rem;
        }

        /* Ajustar el contenedor de la tabla */
        .table-responsive {
            overflow-x: auto;
            /* Asegura que la tabla se pueda hacer scroll horizontal si es necesario */
        }
    }

    @media (max-width: 768px) {

        /* Estilos compactos para móviles */
        .table td,
        .table th {
            padding: 4px;
            /* Reducir el padding de las celdas */
            font-size: 12px;
            /* Tamaño de fuente reducido */
        }

        /* Ajustar el ancho de las columnas */
        .table th,
        .table td {
            word-wrap: break-word;
            /* Permite que el texto se divida en varias líneas si es necesario */
        }

        /* Ajustar el tamaño de la tabla al 100% del contenedor */
        .table-responsive {
            max-width: 100%;
            /* Asegura que la tabla no se desborde horizontalmente */
        }
    }


        body {
            overflow: hidden; /* Evitar el scroll en toda la página */
        }

        .fixed-header {
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1030;
        }

        .header-options {
            background-color: #343a40;
            color: white;
            padding: 10px;
        }
          /* Header fijo en la parte superior */
    @media (max-width: 768px) {
        .mobile-header {
            display: flex;
            justify-content: space-around;
            position: fixed;
            top: 0;
            width: 100%;
            background-color: #343a40;
            z-index: 1000;
            padding: 10px 0;
        }

        .mobile-header a {
            color: #fff;
            font-size: 14px;
            text-decoration: none;
        }

         /* Ajustar el espacio del contenido para que no quede detrás del header */
         .content {
            padding-top: 60px;
        }
    }
    </style>
</head>
<body>
<!-- Header para móviles -->
<div class="mobile-header d-md-none">
    <a href="manage_products.php"><i class="bi bi-box-seam"></i> Productos</a>
    <a href="manage_users.php"><i class="bi bi-people"></i> Usuarios</a>
    <a href="report.php"><i class="bi bi-graph-up"></i> Reportes</a>
    <a href="settings.php"><i class="bi bi-gear"></i> Configuración</a>
    <a href="logout.php"><i class="bi bi-box-arrow-right"></i> Cerrar Sesión</a>
</div>
<!-- Sidebar -->
<div class="sidebar">
    <a href="manage_products.php" class="list-group-item"><i class="bi bi-box-seam"></i> Productos</a>
    <a href="manage_users.php" class="list-group-item"><i class="bi bi-people"></i> Usuarios</a>
    <a href="report.php" class="list-group-item"><i class="bi bi-graph-up"></i> Reportes</a>
    <a href="settings.php" class="list-group-item"><i class="bi bi-gear"></i> Configuración</a>
    <a href="logout.php" class="list-group-item"><i class="bi bi-box-arrow-right"></i> Cerrar Sesión</a>
</div>

<!-- Main Content -->
<div class="content">
    <h2>Reportes de Modificaciones</h2>
    <div class="table-responsive">
        <table class="table table-hover table-bordered" id="reportTable">
            <thead class="thead-dark">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Usuario</th>
                    <th scope="col">Producto</th>
                    <th scope="col">Acción</th>
                    <th scope="col">Fecha de Modificación</th>
                    <th scope="col">Detalles</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reportes as $reporte): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($reporte['id']); ?></td>
                        <td><?php echo htmlspecialchars($reporte['usuario_nombre']); ?></td>
                        <td><?php echo htmlspecialchars($reporte['producto_nombre']); ?></td>
                        <td><?php echo htmlspecialchars($reporte['accion']); ?></td>
                        <td><?php echo htmlspecialchars($reporte['fecha_modificacion']); ?></td>
                        <td>
                            <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#detailsModal<?php echo $reporte['id']; ?>">
                                Ver Cambio Realizado
                            </button>
                        </td>
                    </tr>
                    
                    <!-- Modal para mostrar detalles del cambio -->
                    <div class="modal fade" id="detailsModal<?php echo $reporte['id']; ?>" tabindex="-1" role="dialog" aria-labelledby="detailsModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="detailsModalLabel">Detalles del Cambio</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <h5>Producto: <?php echo htmlspecialchars($reporte['producto_nombre']); ?></h5>
                                    <p><strong>Usuario:</strong> <?php echo htmlspecialchars($reporte['usuario_nombre']); ?></p>
                                    <p><strong>Acción:</strong> <?php echo htmlspecialchars($reporte['accion']); ?></p>
                                    <p><strong>Cambio Anterior:</strong></p>
                                    <pre><?php echo htmlspecialchars($reporte['campo_modificado_anterior']); ?></pre>
                                    <p><strong>Cambio Actual:</strong></p>
                                    <pre><?php echo htmlspecialchars($reporte['campo_modificado_nuevo']); ?></pre>
                                    <p><strong>Fecha de Modificación:</strong> <?php echo htmlspecialchars($reporte['fecha_modificacion']); ?></p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Scripts de Bootstrap y jQuery -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
