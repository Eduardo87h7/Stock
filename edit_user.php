<?php
session_start();
require 'config/db.php'; // Asegúrate de que la conexión a la base de datos esté correctamente configurada

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

$success_message = '';
$error_message = '';
$user = []; // Inicializar la variable $user

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Encriptar la contraseña si no está vacía
    $hashed_password = empty($password) ? null : password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare('UPDATE usuarios SET username = :username, email = :email, password = COALESCE(:password, password), role = :role WHERE id = :id');
        $stmt->execute([
            'id' => $id,
            'username' => $username,
            'email' => $email,
            'password' => $hashed_password,
            'role' => $role
        ]);
        $success_message = 'Usuario actualizado con éxito.';
    } catch (Exception $e) {
        $error_message = 'Error al actualizar el usuario: ' . $e->getMessage();
    }
} else {
    // Verificar si se ha proporcionado un ID de usuario en la URL
    if (!isset($_GET['id'])) {
        echo 'ID de usuario no proporcionado.';
        exit();
    }

    $id = $_GET['id'];
    $stmt = $pdo->prepare('SELECT * FROM usuarios WHERE id = :id');
    $stmt->execute(['id' => $id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo 'Usuario no encontrado.';
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Usuario</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>
<body>
    <?php include 'header.php'; ?>

    <form action="edit_user.php" method="POST" class="container mt-5">
        <h2>Editar Usuario</h2>
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['id'] ?? ''); ?>">
        <div class="form-group">
            <label for="username">Usuario:</label>
            <input type="text" name="username" class="form-control" value="<?php echo htmlspecialchars($user['username'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="email">Correo Electrónico:</label>
            <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
        </div>
        <div class="form-group">
            <label for="password">Contraseña (deja en blanco para no cambiar):</label>
            <input type="password" name="password" class="form-control">
        </div>
        <div class="form-group">
            <label for="role">Rol:</label>
            <select name="role" class="form-control">
                <option value="admin" <?php echo ($user['role'] ?? '') == 'admin' ? 'selected' : ''; ?>>Administrador</option>
                <option value="operador" <?php echo ($user['role'] ?? '') == 'operador' ? 'selected' : ''; ?>>Operador</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Actualizar</button>
    </form>

    <!-- Modal -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Resultado</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p id="modalMessage"><?php echo isset($success_message) ? $success_message : (isset($error_message) ? $error_message : ''); ?></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            <?php if (isset($success_message) || isset($error_message)): ?>
                $('#myModal').modal('show');
            <?php endif; ?>
        });
    </script>

    <?php include 'footer.php'; ?>
</body>
</html>

