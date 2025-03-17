class FormValidator {
    constructor(form) {
        this.form = form;
        this.errors = new Map();
    }

    validate() {
        this.errors.clear();
        this.validateRequired();
        this.validateEmail();
        this.validateMobile();
        this.validatePasswords();
        this.validateFileTypes();
        this.validateFileSize();
        
        return this.errors.size === 0;
    }

    validateRequired() {
        this.form.querySelectorAll('[required]').forEach(element => {
            if (!element.value.trim()) {
                this.showError(element, 'This field is required');
            } else {
                this.removeError(element);
            }
        });
    }

    validateEmail() {
        const email = this.form.querySelector('[name="email"]');
        if (!email || !email.value.trim()) return;
        
        if (!this.isValidEmail(email.value)) {
            this.showError(email, 'Please enter a valid email address');
        } else {
            this.removeError(email);
        }
    }

    validateMobile() {
        const mobile = this.form.querySelector('[name="mobile"]');
        if (!mobile || !mobile.value.trim()) return;
        
        if (!this.isValidMobile(mobile.value)) {
            this.showError(mobile, 'Please enter a valid mobile number');
        } else {
            this.removeError(mobile);
        }
    }

    validatePasswords() {
        const password = this.form.querySelector('[name="password"]');
        const confirmPassword = this.form.querySelector('[name="confirm_password"]');
        
        if (!password || !confirmPassword) return;
        
        if (password.value.length < 8) {
            this.showError(password, 'Password must be at least 8 characters long');
        } else if (password.value !== confirmPassword.value) {
            this.showError(confirmPassword, 'Passwords do not match');
        } else {
            this.removeError(password);
            this.removeError(confirmPassword);
        }
    }

    validateFileTypes() {
        this.form.querySelectorAll('input[type="file"]').forEach(fileInput => {
            if (fileInput.files.length > 0) {
                const allowedTypes = fileInput.accept.split(',');
                const file = fileInput.files[0];
                const fileType = '.' + file.name.split('.').pop().toLowerCase();
                
                if (!allowedTypes.includes(fileType)) {
                    this.showError(fileInput, 'Please upload a valid file type');
                } else {
                    this.removeError(fileInput);
                }
            }
        });
    }

    validateFileSize() {
        const maxSize = 5 * 1024 * 1024; // 5MB
        this.form.querySelectorAll('input[type="file"]').forEach(fileInput => {
            if (fileInput.files.length > 0) {
                const file = fileInput.files[0];
                if (file.size > maxSize) {
                    this.showError(fileInput, 'File size should be less than 5MB');
                } else {
                    this.removeError(fileInput);
                }
            }
        });
    }

    isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    isValidMobile(mobile) {
        return /^\+?[\d\s-]{10,}$/.test(mobile);
    }

    showError(element, message) {
        this.errors.set(element, message);
        
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message';
        errorDiv.textContent = message;
        
        const existingError = element.parentNode.querySelector('.error-message');
        if (existingError) {
            existingError.remove();
        }
        
        element.classList.add('error');
        element.parentNode.appendChild(errorDiv);
    }

    removeError(element) {
        this.errors.delete(element);
        element.classList.remove('error');
        
        const errorDiv = element.parentNode.querySelector('.error-message');
        if (errorDiv) {
            errorDiv.remove();
        }
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Function to show registration form based on user type
    window.showRegistrationForm = function(userType) {
        document.querySelectorAll('.registration-form').forEach(form => {
            form.style.display = 'none';
        });
        
        document.getElementById('userTypeForm').style.display = 'none';
        document.getElementById(userType + 'Form').style.display = 'block';
    };

    // Function to show user type selection
    window.showUserTypeSelection = function() {
        document.querySelectorAll('.registration-form').forEach(form => {
            form.style.display = 'none';
        });
        
        document.getElementById('userTypeForm').style.display = 'block';
    };

    // Company type toggle for tenant registration
    const companyTypeSelect = document.querySelector('select[name="company_type"]');
    if (companyTypeSelect) {
        companyTypeSelect.addEventListener('change', function() {
            const nonExpoFields = document.getElementById('nonExpoFields');
            const expoFields = document.getElementById('expoFields');
            
            if (this.value === 'expo') {
                nonExpoFields.style.display = 'none';
                expoFields.style.display = 'block';
            } else {
                nonExpoFields.style.display = 'block';
                expoFields.style.display = 'none';
            }
        });
    }

    // Form validation and submission
    document.querySelectorAll('.registration-form').forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const validator = new FormValidator(this);
            if (!validator.validate()) {
                return;
            }

            try {
                const formData = new FormData(this);
                formData.append('formId', this.id);
                
                const response = await fetch('../controllers/register.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();
                
                if (result.success) {
                    showMessage('success', 'Registration successful! Please check your email for verification.');
                    setTimeout(() => {
                        window.location.href = '../public/login.php';
                    }, 3000);
                } else {
                    showMessage('error', result.message || 'Registration failed. Please try again.');
                }
            } catch (error) {
                console.error('Error:', error);
                showMessage('error', 'An error occurred. Please try again later.');
            }
        });
    });

    function showMessage(type, message) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'} mt-3`;
        alertDiv.textContent = message;
        
        const container = document.querySelector('.register-box');
        container.insertBefore(alertDiv, container.firstChild);
        
        setTimeout(() => {
            alertDiv.remove();
        }, 5000);
    }
}); 