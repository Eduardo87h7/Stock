<?php
session_start();
require 'config/db.php';

// Verificar si el usuario está autenticado y es administrador
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: dashboard.php');
    exit();
}

// Obtener todos los usuarios de la base de datos
$stmt = $pdo->query('SELECT * FROM usuarios');
$users = $stmt->fetchAll();

// Manejar el registro de nuevos usuarios
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username'], $_POST['email'], $_POST['password'], $_POST['role'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = trim($_POST['role']);

    $error_message = '';

    // Validar datos y comprobar duplicados
    if (empty($username) || empty($email) || empty($password) || empty($role)) {
        $error_message = 'Todos los campos son obligatorios.';
    } else {
        $stmt = $pdo->prepare('SELECT id FROM usuarios WHERE username = :username OR email = :email');
        $stmt->execute(['username' => $username, 'email' => $email]);
        $existingUser = $stmt->fetch();

        if ($existingUser) {
            $error_message = 'El usuario o correo electrónico ya existe. Intenta con otro nombre o correo.';
        } else {
            // Insertar nuevo usuario
            $stmt = $pdo->prepare('INSERT INTO usuarios (username, email, password, role) VALUES (:username, :email, :password, :role)');
            $stmt->execute([
                'username' => $username,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_BCRYPT),
                'role' => $role
            ]);

            // Recargar los datos para actualizar la tabla
            $stmt = $pdo->query('SELECT * FROM usuarios');
            $users = $stmt->fetchAll();
        }
    }

    // Enviar respuesta en formato JSON
    header('Content-Type: application/json');
    echo json_encode(['error' => $error_message]);
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Usuarios</title>
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

        .content-container {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden; /* Evita que el contenido haga scroll */
            padding: 20px;
        }

        .table-container {
            position: relative;
            height: 400px; /* Ajusta la altura fija según tus necesidades */
            overflow: hidden; /* Evita el cambio de tamaño del contenedor */
        }

        .table-wrapper {
            height: 100%;
            overflow: auto; /* Permite el scroll solo dentro de la tabla */
        }

        .table {
            margin-bottom: 0; /* Asegura que no haya espacio extra debajo de la tabla */
            width: 100%; /* Asegura que la tabla ocupe todo el ancho disponible */
        }

        .pagination-container {
            margin-top: 10px;
            text-align: center;
        }

        .btn-add-user {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background-color: gray;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            font-size: 20px;
            border: none;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .btn-add-user:hover {
            background-color: darkgray;
            color: white;
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
    <!-- Content with Table -->
    <div class="content-container">
        <h2>Gestión de Usuarios</h2>

        <!-- Button to Open the Modal -->
        <button type="button" class="btn btn-add-user" data-toggle="modal" data-target="#addUserModal">
            <i class="bi bi-person-plus"></i>
        </button>

        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <!-- Search Bar -->
        <div class="row mb-3">
            <div class="col-md-6">
                <input type="text" id="searchInput" class="form-control" placeholder="Buscar usuario...">
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

        <!-- Table Container -->
        <div class="table-container">
            <div class="table-wrapper">
                <table class="table table-hover table-bordered" id="userTable">
                    <thead class="thead-dark">
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Nombre de Usuario</th>
                            <th scope="col">Correo Electrónico</th>
                            <th scope="col">Rol</th>
                            <th scope="col">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user['id']); ?></td>
                                <td><?php echo htmlspecialchars($user['username']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td><?php echo htmlspecialchars($user['role']); ?></td>
                                <td>
                                    <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="btn btn-outline-warning btn-sm">
                                        <i class="bi bi-pencil"></i> Editar
                                    </a>
                                    <a href="manage_users.php?delete_id=<?php echo $user['id']; ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar este usuario?');">
                                        <i class="bi bi-trash"></i> Eliminar
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
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

<!-- Modal for Adding Users -->
<div class="modal fade" id="addUserModal" tabindex="-1" role="dialog" aria-labelledby="addUserModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addUserModalLabel">Agregar Usuario</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="addUserForm">
                    <div class="form-group">
                        <label for="username">Nombre de Usuario</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Correo Electrónico</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="form-group">
                        <label for="password">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="form-group">
                        <label for="role">Rol</label>
                        <select class="form-control" id="role" name="role" required>
                            <option value="admin">Administrador</option>
                            <option value="user">Usuario</option>
                        </select>
                    </div>
                    <div id="error-message" class="alert alert-danger d-none"></div>
                    <button type="submit" class="btn btn-primary">Agregar Usuario</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('addUserForm');
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(form);
            fetch('manage_users.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                const errorMessage = document.getElementById('error-message');
                if (data.error) {
                    errorMessage.textContent = data.error;
                    errorMessage.classList.remove('d-none');
                } else {
                    errorMessage.classList.add('d-none');
                    form.reset();
                    $('#addUserModal').modal('hide');
                    location.reload(); // Recargar la página para actualizar la tabla
                }
            });
        });
    });
</script>
</body>
</html>
