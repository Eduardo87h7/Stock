<?php
session_start();
require 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

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

// Obtener todos los registros de la tabla reportes, con información del usuario y producto
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
    <style>
        .table th, .table td {
            padding: 0.3rem; /* Ajusta este valor según tus necesidades */
        }
        .modal-body pre {
            max-height: 50px; /* Ajusta la altura máxima para el contenido del modal */
            overflow-y: auto; /* Agrega barra de desplazamiento si el contenido es demasiado largo */
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2>Reportes de Modificaciones</h2>
    <a href="index.php" class="btn btn-primary mb-3">Regresar</a>
    <table class="table table-hover table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Usuario</th>
                <th>Producto</th>
                <th>Acción</th>
                <th>Fecha de Modificación</th>
                <th>Detalles</th>
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

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
