// js/register.js
document.getElementById('registerForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);

    const response = await fetch('register.php', {
        method: 'POST',
        body: formData,
    });

    const result = await response.json();

    if (result.success) {
        // Redirect to the dashboard or show a success message
    } else {
        // Show an error message
        // Show an error message
        alert(result.message);
    }
});

// js/login.js
document.getElementById('loginForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);

    const response = await fetch('login.php', {
        method: 'POST',
        body: formData,
    });

    const result = await response.json();

    if (result.success) {
        // Redirect to the dashboard or show a success message
        window.location.href = 'dashboard.php';
    } else {
        // Show an error message
        alert(result.message);
    }
});