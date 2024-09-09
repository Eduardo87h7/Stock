
document.addEventListener('DOMContentLoaded', function() {
    // Handle the add user form submission
    const addForm = document.getElementById('addUserForm');
    addForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(addForm);
        fetch('manage_users.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            const errorMessage = document.getElementById('errorMessage');
            if (data.error) {
                errorMessage.textContent = data.error;
                errorMessage.classList.remove('d-none');
            } else {
                errorMessage.classList.add('d-none');
                $('#addUserModal').modal('hide');
                location.reload(); // Recargar la página para actualizar la tabla
            }
        });
    });

    // Handle the edit button click
    document.querySelectorAll('.btn-edit').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const username = this.getAttribute('data-username');
            const email = this.getAttribute('data-email');
            const role = this.getAttribute('data-role');

            document.getElementById('editUserId').value = id;
            document.getElementById('editUsername').value = username;
            document.getElementById('editEmail').value = email;
            document.getElementById('editRole').value = role;

            $('#editUserModal').modal('show');
        });
    });

    // Handle the edit user form submission
    const editForm = document.getElementById('editUserForm');
    editForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(editForm);
        fetch('edit_user.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            const errorMessage = document.getElementById('editErrorMessage');
            if (data.error) {
                errorMessage.textContent = data.error;
                errorMessage.classList.remove('d-none');
            } else {
                errorMessage.classList.add('d-none');
                $('#editUserModal').modal('hide');
                location.reload(); // Recargar la página para actualizar la tabla
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {
    // Handle the add user form submission
    const addForm = document.getElementById('addUserForm');
    addForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(addForm);
        fetch('manage_users.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            const errorMessage = document.getElementById('errorMessage');
            if (data.error) {
                errorMessage.textContent = data.error;
                errorMessage.classList.remove('d-none');
            } else {
                errorMessage.classList.add('d-none');
                $('#addUserModal').modal('hide');
                location.reload(); // Recargar la página para actualizar la tabla
            }
        });
    });

    // Handle the edit button click
    document.querySelectorAll('.btn-edit').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const username = this.getAttribute('data-username');
            const email = this.getAttribute('data-email');
            const role = this.getAttribute('data-role');

            document.getElementById('editUserId').value = id;
            document.getElementById('editUsername').value = username;
            document.getElementById('editEmail').value = email;
            document.getElementById('editRole').value = role;

            $('#editUserModal').modal('show');
        });
    });

    // Handle the edit user form submission
    const editForm = document.getElementById('editUserForm');
    editForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(editForm);
        fetch('edit_user.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            const errorMessage = document.getElementById('editErrorMessage');
            if (data.error) {
                errorMessage.textContent = data.error;
                errorMessage.classList.remove('d-none');
            } else {
                errorMessage.classList.add('d-none');
                $('#editUserModal').modal('hide');
                location.reload(); // Recargar la página para actualizar la tabla
            }
        });
    });

    // Handle the search input
    const searchInput = document.getElementById('searchInput');
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('#userTable tbody tr');

        rows.forEach(row => {
            const username = row.cells[1].textContent.toLowerCase();
            const email = row.cells[2].textContent.toLowerCase();

            if (username.includes(searchTerm) || email.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Handle the entries count change
    const entriesCount = document.getElementById('entriesCount');
    entriesCount.addEventListener('change', function() {
        const perPage = parseInt(this.value, 10);
        const url = new URL(window.location.href);
        url.searchParams.set('perPage', perPage);
        window.location.href = url.toString();
    });
});

document.addEventListener('DOMContentLoaded', function() {
    // Handle the delete button click
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');

            if (confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
                fetch('delete_user.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        'id': id
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        // Remove the row from the table
                        this.closest('tr').remove();
                    } else {
                        alert(data.message);
                    }
                });
            }
        });
    });

    // Handle the add user form submission
    const addForm = document.getElementById('addUserForm');
    addForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(addForm);
        fetch('manage_users.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            const errorMessage = document.getElementById('errorMessage');
            if (data.error) {
                errorMessage.textContent = data.error;
                errorMessage.classList.remove('d-none');
            } else {
                errorMessage.classList.add('d-none');
                $('#addUserModal').modal('hide');
                location.reload(); // Recargar la página para actualizar la tabla
            }
        });
    });

    // Handle the edit button click
    document.querySelectorAll('.btn-edit').forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const username = this.getAttribute('data-username');
            const email = this.getAttribute('data-email');
            const role = this.getAttribute('data-role');

            document.getElementById('editUserId').value = id;
            document.getElementById('editUsername').value = username;
            document.getElementById('editEmail').value = email;
            document.getElementById('editRole').value = role;

            $('#editUserModal').modal('show');
        });
    });

    // Handle the edit user form submission
    const editForm = document.getElementById('editUserForm');
    editForm.addEventListener('submit', function(event) {
        event.preventDefault();
        const formData = new FormData(editForm);
        fetch('edit_user.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            const errorMessage = document.getElementById('editErrorMessage');
            if (data.error) {
                errorMessage.textContent = data.error;
                errorMessage.classList.remove('d-none');
            } else {
                errorMessage.classList.add('d-none');
                $('#editUserModal').modal('hide');
                location.reload(); // Recargar la página para actualizar la tabla
            }
        });
    });

    // Handle the search input
    const searchInput = document.getElementById('searchInput');
    searchInput.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('#userTable tbody tr');

        rows.forEach(row => {
            const username = row.cells[1].textContent.toLowerCase();
            const email = row.cells[2].textContent.toLowerCase();

            if (username.includes(searchTerm) || email.includes(searchTerm)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });

    // Handle the entries count change
    const entriesCount = document.getElementById('entriesCount');
    entriesCount.addEventListener('change', function() {
        const perPage = parseInt(this.value, 10);
        const url = new URL(window.location.href);
        url.searchParams.set('perPage', perPage);
        window.location.href = url.toString();
    });
});
