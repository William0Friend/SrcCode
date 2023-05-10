// Login form submission handler
document.getElementById('login-form').addEventListener('submit', function(event) {
    event.preventDefault();
    let formData = new FormData(event.target);
    fetch('/api/login', {
        method: 'POST',
        body: formData
    }).then(response => {
        if (response.ok) {
            return response.json();
        } else {
            throw new Error('Invalid username or password');
        }
    }).then(data => {
        localStorage.setItem('jwt', data.token);
        window.location.href = '/code-inventory';
    }).catch(error => {
        alert(error.message);
    });
});

// Logout button click handler
document.getElementById('logout-button').addEventListener('click', function() {
    localStorage.removeItem('jwt');
    window.location.href = '/login';
});