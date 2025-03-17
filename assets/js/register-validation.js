class FormValidator {
    constructor(form) {
        this.form = form;
        this.errors = [];
    }

    validate() {
        this.errors = [];
        this.validateRequired();
        this.validateEmail();
        this.validateMobile();
        this.validateFileTypes();
        this.validateFileSize();
        
        return this.errors.length === 0;
    }

    validateRequired() {
        this.form.querySelectorAll('[required]').forEach(element => {
            if (!element.value.trim()) {
                this.errors.push(`${element.getAttribute('name')} is required`);
                this.showError(element, 'This field is required');
            } else {
                this.removeError(element);
            }
        });
    }

    validateEmail() {
        const emailInput = this.form.querySelector('input[type="email"]');
        if (emailInput && emailInput.value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(emailInput.value)) {
                this.errors.push('Invalid email format');
                this.showError(emailInput, 'Please enter a valid email address');
            } else {
                this.removeError(emailInput);
            }
        }
    }

    validateMobile() {
        const mobileInput = this.form.querySelector('input[name="mobile"]');
        if (mobileInput && mobileInput.value) {
            const mobileRegex = /^[0-9]{10,15}$/;
            if (!mobileRegex.test(mobileInput.value.replace(/[- ]/g, ''))) {
                this.errors.push('Invalid mobile number');
                this.showError(mobileInput, 'Please enter a valid mobile number');
            } else {
                this.removeError(mobileInput);
            }
        }
    }

    validateFileTypes() {
        this.form.querySelectorAll('input[type="file"]').forEach(fileInput => {
            if (fileInput.files.length > 0) {
                const allowedTypes = fileInput.accept.split(',');
                const file = fileInput.files[0];
                const fileType = '.' + file.name.split('.').pop().toLowerCase();
                
                if (!allowedTypes.includes(fileType)) {
                    this.errors.push(`Invalid file type for ${fileInput.name}`);
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
                    this.errors.push(`File ${file.name} is too large`);
                    this.showError(fileInput, 'File size should be less than 5MB');
                } else {
                    this.removeError(fileInput);
                }
            }
        });
    }

    showError(element, message) {
        let errorDiv = element.nextElementSibling;
        if (!errorDiv || !errorDiv.classList.contains('error-message')) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'error-message';
            element.parentNode.insertBefore(errorDiv, element.nextSibling);
        }
        errorDiv.textContent = message;
        element.classList.add('error');
    }

    removeError(element) {
        const errorDiv = element.nextElementSibling;
        if (errorDiv && errorDiv.classList.contains('error-message')) {
            errorDiv.remove();
        }
        element.classList.remove('error');
    }
}

// Add form submission handlers
document.querySelectorAll('.registration-form').forEach(form => {
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const validator = new FormValidator(this);
        if (validator.validate()) {
            // If validation passes, submit the form
            submitRegistrationForm(this);
        }
    });
});

async function submitRegistrationForm(form) {
    const formData = new FormData(form);
    
    try {
        const response = await fetch('register_handler.php', {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            window.location.href = 'registration_success.php';
        } else {
            showFormError(form, result.message);
        }
    } catch (error) {
        showFormError(form, 'An error occurred. Please try again.');
    }
}

function showFormError(form, message) {
    let errorDiv = form.querySelector('.form-error');
    if (!errorDiv) {
        errorDiv = document.createElement('div');
        errorDiv.className = 'form-error alert alert-danger';
        form.insertBefore(errorDiv, form.firstChild);
    }
    errorDiv.textContent = message;
}

// Real-time validation
document.querySelectorAll('.registration-form input, .registration-form select').forEach(input => {
    input.addEventListener('blur', async function() {
        const field = this.name;
        const value = this.value;
        
        // Skip empty optional fields
        if (!this.hasAttribute('required') && !value) return;
        
        try {
            const response = await fetch('validate_field.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ field, value })
            });
            
            const result = await response.json();
            
            if (!result.valid) {
                showError(this, result.message);
            } else {
                removeError(this);
            }
        } catch (error) {
            console.error('Validation error:', error);
        }
    });
});

// Add email availability check
document.querySelectorAll('input[type="email"]').forEach(input => {
    let timeout;
    
    input.addEventListener('input', function() {
        clearTimeout(timeout);
        
        timeout = setTimeout(async () => {
            if (this.value) {
                try {
                    const response = await fetch('check_email.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ email: this.value })
                    });
                    
                    const result = await response.json();
                    
                    if (!result.available) {
                        showError(this, 'This email is already registered');
                    } else {
                        removeError(this);
                    }
                } catch (error) {
                    console.error('Email check error:', error);
                }
            }
        }, 500);
    });
});

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
            
            if (!validateForm(this)) {
                return;
            }

            try {
                const formData = new FormData(this);
                const response = await fetch('../controllers/register.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();
                
                if (result.success) {
                    showMessage('success', 'Registration successful! Please check your email for verification.');
                    setTimeout(() => {
                        window.location.href = '../login.php';
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

    // Form validation function
    function validateForm(form) {
        let isValid = true;
        
        // Clear previous error messages
        form.querySelectorAll('.error-message').forEach(msg => msg.remove());
        
        // Validate required fields
        form.querySelectorAll('[required]').forEach(field => {
            if (!field.value.trim()) {
                showFieldError(field, 'This field is required');
                isValid = false;
            }
        });
        
        // Validate email format
        const emailField = form.querySelector('input[type="email"]');
        if (emailField && emailField.value.trim() && !isValidEmail(emailField.value)) {
            showFieldError(emailField, 'Please enter a valid email address');
            isValid = false;
        }
        
        // Validate mobile number format
        const mobileField = form.querySelector('input[name="mobile"]');
        if (mobileField && mobileField.value.trim() && !isValidMobile(mobileField.value)) {
            showFieldError(mobileField, 'Please enter a valid mobile number');
            isValid = false;
        }
        
        // Validate file uploads
        form.querySelectorAll('input[type="file"]').forEach(fileField => {
            if (fileField.required && !fileField.files.length) {
                showFieldError(fileField, 'Please select a file');
                isValid = false;
            } else if (fileField.files.length) {
                const file = fileField.files[0];
                if (!isValidFileType(file, fileField.accept)) {
                    showFieldError(fileField, 'Invalid file type');
                    isValid = false;
                }
                if (file.size > 5 * 1024 * 1024) { // 5MB limit
                    showFieldError(fileField, 'File size should not exceed 5MB');
                    isValid = false;
                }
            }
        });
        
        return isValid;
    }

    // Helper functions
    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    function isValidMobile(mobile) {
        return /^\+?[\d\s-]{10,}$/.test(mobile);
    }

    function isValidFileType(file, acceptedTypes) {
        const acceptedExtensions = acceptedTypes.split(',').map(type => 
            type.trim().toLowerCase().replace('*', '')
        );
        const fileExtension = '.' + file.name.split('.').pop().toLowerCase();
        return acceptedExtensions.includes(fileExtension);
    }

    function showFieldError(field, message) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message text-danger';
        errorDiv.textContent = message;
        field.parentNode.appendChild(errorDiv);
    }

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