document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('password');
    const togglePassword = document.getElementById('togglePassword');
    const icon = togglePassword.querySelector('i');  // Select the icon inside the span

    togglePassword.addEventListener('click', function() {
        // Toggle visibility of the password
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');  // Change icon to closed eye
        } else {
            passwordInput.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');  // Revert to open eye icon
        }
    });
});
