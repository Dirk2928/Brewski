// script.js

document.addEventListener('DOMContentLoaded', () => {
    
    // --- LOGIN FORM LOGIC ---
    const loginForm = document.getElementById('login-form');
    if (loginForm) {
        loginForm.addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default page reload

            const firstName = document.getElementById('first_name').value.trim();
            const password = document.getElementById('password').value;

            // Clear previous errors
            document.getElementById('first_name-error').style.display = 'none';
            document.getElementById('password-error').style.display = 'none';
            document.getElementById('login-error').style.display = 'none';

            let hasError = false;

            // Validation
            if (firstName === '') {
                showError('first_name', 'Enter your first name.');
                hasError = true;
            }

            if (password === '') {
                showError('password', 'Enter your password.');
                hasError = true;
            }

            // Simulate login
            if (!hasError) {
                const submitBtn = document.querySelector('.btn');
                submitBtn.textContent = 'Logging in...';
                submitBtn.disabled = true;

                setTimeout(() => {
                    localStorage.setItem('brewski_user', firstName);
                    window.location.href = 'index.html'; 
                }, 1000);
            }
        });
    }

    // --- SIGNUP FORM LOGIC ---
    const signupForm = document.getElementById('signup-form');
    if (signupForm) {
        signupForm.addEventListener('submit', function(event) {
            event.preventDefault();

            const firstName = document.getElementById('first_name').value.trim();
            const lastName = document.getElementById('last_name').value.trim();
            const password = document.getElementById('password').value;

            // Clear previous errors
            document.querySelectorAll('.field__error').forEach(el => el.style.display = 'none');
            document.querySelectorAll('input').forEach(el => el.removeAttribute('aria-invalid'));

            let hasError = false;

            if (firstName === '') {
                showError('first_name', 'Enter your first name.');
                hasError = true;
            }
            if (lastName === '') {
                showError('last_name', 'Enter your last name.');
                hasError = true;
            }
            if (password.length < 8) {
                showError('password', 'Use at least 8 characters.');
                hasError = true;
            }

            if (!hasError) {
                const submitBtn = document.querySelector('.btn');
                submitBtn.textContent = 'Creating account...';
                submitBtn.disabled = true;

                setTimeout(() => {
                    window.location.href = 'login.html'; 
                }, 1000);
            }
        });
    }

    // --- HELPER FUNCTION FOR ERRORS ---
    function showError(fieldId, message) {
        const errorEl = document.getElementById(fieldId + '-error');
        if (errorEl) {
            errorEl.textContent = message;
            errorEl.style.display = 'block';
            document.getElementById(fieldId).setAttribute('aria-invalid', 'true');
        }
    }
});