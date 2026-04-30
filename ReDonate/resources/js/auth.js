// resources/js/auth.js
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('loginForm');
    const btn = document.getElementById('loginBtn');
    const btnText = document.querySelector('.btn-text');
    const spinner = document.getElementById('loadingSpinner');

    form.addEventListener('submit', function(e) {
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;

        // Client-side validation
        if (!email || !password) {
            e.preventDefault();
            alert('Email dan password wajib diisi!');
            return;
        }

        // Show loading
        btn.disabled = true;
        btnText.textContent = 'Memproses...';
        spinner.style.display = 'inline-block';

        // Simulate network delay for better UX
        setTimeout(() => {
            // Form will submit normally
        }, 500);
    });

    // Password visibility toggle
    const passwordField = document.getElementById('password');
    passwordField.addEventListener('input', function() {
        const error = document.getElementById('password-error');
        if (error) error.remove();
        
        passwordField.classList.remove('error');
    });

    
});