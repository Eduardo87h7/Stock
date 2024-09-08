document.addEventListener('DOMContentLoaded', function () {
    var searchInput = document.getElementById('searchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function () {
            var filter = this.value.toLowerCase();
            var rows = document.querySelectorAll('#productTable tbody tr');
            rows.forEach(function (row) {
                var cells = row.getElementsByTagName('td');
                var match = false;
                for (var i = 1; i < cells.length - 1; i++) { // Excluye la primera columna (ID) y la última (Acciones)
                    var cell = cells[i].textContent.toLowerCase();
                    if (cell.indexOf(filter) > -1) {
                        match = true;
                        break;
                    }
                }
                row.style.display = match ? '' : 'none';
            });
        });
    }

    var editButtons = document.querySelectorAll('.edit-button');
    editButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            var id = this.getAttribute('data-id');
            var nombre = this.getAttribute('data-nombre');
            var marca = this.getAttribute('data-marca');
            var modelo = this.getAttribute('data-modelo');
            var cantidad = this.getAttribute('data-cantidad');
            var ubicacion = this.getAttribute('data-ubicacion'); // Cambiado de 'data-calidad' a 'data-ubicacion'

            document.getElementById('edit_id').value = id;
            document.getElementById('edit_nombre').value = nombre;
            document.getElementById('edit_marca').value = marca;
            document.getElementById('edit_modelo').value = modelo;
            document.getElementById('edit_cantidad').value = cantidad;
            document.getElementById('edit_ubicacion').value = ubicacion; // Cambiado de 'edit_calidad' a 'edit_ubicacion'

            $('#editProductModal').modal('show');
        });
    });

    var deleteButtons = document.querySelectorAll('.delete-button');
    deleteButtons.forEach(function (button) {
        button.addEventListener('click', function () {
            var id = this.getAttribute('data-id');
            if (confirm('¿Estás seguro de que quieres eliminar este producto?')) {
                window.location.href = 'delete_product.php?id=' + id;
            }
        });
    });
});
