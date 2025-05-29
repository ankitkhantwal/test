// API endpoints
const API = {
    users: '/api/users',
    user: (id) => `/api/users/${id}`
};

// Load users on page load
document.addEventListener('DOMContentLoaded', loadUsers);

// Load all users
async function loadUsers() {
    try {
        const response = await fetch(API.users);
        const data = await response.json();
        
        if (data.status === 'success') {
            renderUsers(data.data);
        }
    } catch (error) {
        showAlert('Error loading users', 'danger');
    }
}

// Render users in the UI
function renderUsers(users) {
    const container = document.getElementById('usersList');
    const template = document.getElementById('userCardTemplate');
    
    container.innerHTML = '';
    
    users.forEach(user => {
        const clone = template.content.cloneNode(true);
        
        clone.querySelector('.user-name').textContent = user.name;
        clone.querySelector('.user-email').textContent = user.email;
        clone.querySelector('.user-date').textContent = new Date(user.created_at).toLocaleDateString();
        
        // Add event listeners
        clone.querySelector('.edit-user').addEventListener('click', () => openEditModal(user));
        clone.querySelector('.delete-user').addEventListener('click', () => deleteUser(user.id));
        
        container.appendChild(clone);
    });
}

// Add new user
async function addUser() {
    const form = document.getElementById('addUserForm');
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());
    
    try {
        const response = await fetch(API.users, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.status === 'success') {
            showAlert('User added successfully', 'success');
            form.reset();
            bootstrap.Modal.getInstance(document.getElementById('addUserModal')).hide();
            loadUsers();
        }
    } catch (error) {
        showAlert('Error adding user', 'danger');
    }
}

// Open edit modal
function openEditModal(user) {
    const form = document.getElementById('editUserForm');
    form.elements.id.value = user.id;
    form.elements.name.value = user.name;
    form.elements.email.value = user.email;
    
    new bootstrap.Modal(document.getElementById('editUserModal')).show();
}

// Update user
async function updateUser() {
    const form = document.getElementById('editUserForm');
    const id = form.elements.id.value;
    const data = {
        name: form.elements.name.value,
        email: form.elements.email.value
    };
    
    try {
        const response = await fetch(API.user(id), {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.status === 'success') {
            showAlert('User updated successfully', 'success');
            bootstrap.Modal.getInstance(document.getElementById('editUserModal')).hide();
            loadUsers();
        }
    } catch (error) {
        showAlert('Error updating user', 'danger');
    }
}

// Delete user
async function deleteUser(id) {
    if (!confirm('Are you sure you want to delete this user?')) {
        return;
    }
    
    try {
        const response = await fetch(API.user(id), {
            method: 'DELETE'
        });
        
        const result = await response.json();
        
        if (result.status === 'success') {
            showAlert('User deleted successfully', 'success');
            loadUsers();
        }
    } catch (error) {
        showAlert('Error deleting user', 'danger');
    }
}

// Show alert message
function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.querySelector('.container').insertAdjacentElement('afterbegin', alertDiv);
    
    setTimeout(() => {
        alertDiv.remove();
    }, 5000);
} 