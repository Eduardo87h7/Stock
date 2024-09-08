<?php
require 'config/db.php';

// Obtener el ID del reporte
$id = $_POST['id'];

// Obtener los detalles del cambio
$stmt = $pdo->prepare('
    SELECT campo_modificado, valor_anterior, valor_nuevo
    FROM detalle_reportes
    WHERE reporte_id = ?
');
$stmt->execute([$id]);
$cambios = $stmt->fetchAll();

// Mostrar los detalles del cambio
if ($cambios) {
    echo '<table class="table table-bordered">';
    echo '<thead><tr><th>Campo</th><th>Valor Anterior</th><th>Valor Nuevo</th></tr></thead>';
    echo '<tbody>';
    foreach ($cambios as $cambio) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($cambio['campo_modificado']) . '</td>';
        echo '<td>' . htmlspecialchars($cambio['valor_anterior']) . '</td>';
        echo '<td>' . htmlspecialchars($cambio['valor_nuevo']) . '</td>';
        echo '</tr>';
    }
    echo '</tbody>';
    echo '</table>';
} else {
    echo 'No se encontraron detalles para este cambio.';
}
?>
