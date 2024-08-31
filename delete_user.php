<?php
session_start();
require 'config/db.php';

$success_message = '';
$error_message = '';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $stmt = $pdo->prepare('DELETE FROM usuarios WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $success_message = 'Usuario eliminado con éxito.';
    } catch (Exception $e) {
        $error_message = 'Error al eliminar el usuario: ' . $e->getMessage();
    }
} else {
    $error_message = 'ID de usuario no proporcionado.';
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Eliminar Usuario</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <form action="delete_user.php" method="GET">
        <h2>Eliminar Usuario</h2>
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($_GET['id']); ?>">
        <button type="submit">Eliminar Usuario</button>
    </form>

    <!-- Modal -->
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <p id="modalMessage"><?php echo isset($success_message) ? $success_message : (isset($error_message) ? $error_message : ''); ?></p>
        </div>
    </div>

    <script>
        function showModal(message) {
            document.getElementById('modalMessage').innerText = message;
            var modal = document.getElementById('myModal');
            modal.style.display = "block";

            var span = document.getElementsByClassName("close")[0];
            span.onclick = function() {
                modal.style.display = "none";
            }

            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        }

        // Mostrar el modal si hay un mensaje de éxito o error
        <?php if (isset($success_message) || isset($error_message)): ?>
            showModal('<?php echo isset($success_message) ? $success_message : $error_message; ?>');
        <?php endif; ?>
    </script>
</body>
</html>
