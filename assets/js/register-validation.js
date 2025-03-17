class FormValidator {
    constructor(form) {
        this.form = form;
        this.errors = new Map();
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

    async validateEmail() {
        const email = this.form.querySelector('[name="email"]');
        if (!email || !email.value.trim()) return;
        
        try {
            const response = await fetch('/public/validation/validate_field.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `field=email&value=${encodeURIComponent(email.value)}`
            });
            
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }

            const result = await response.json();
            if (!result.valid) {
                this.showError(email, result.message);
                return;
            }

            // Check email availability
            const availabilityResponse = await fetch('/public/validation/check_email.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `email=${encodeURIComponent(email.value)}`
            });
            
            if (!availabilityResponse.ok) {
                throw new Error('Network response was not ok');
            }

            const availabilityResult = await availabilityResponse.json();
            if (!availabilityResult.available) {
                this.showError(email, availabilityResult.message);
            } else {
                this.removeError(email);
            }
        } catch (error) {
            console.error('Email validation error:', error);
            this.showError(email, 'Error validating email. Please try again.');
        }
    }

    async validateMobile() {
        const mobile = this.form.querySelector('[name="mobile"]');
        if (!mobile || !mobile.value.trim()) return;
        
        try {
            const response = await fetch('/public/validation/validate_field.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `field=mobile&value=${encodeURIComponent(mobile.value)}`
            });
            
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }

            const result = await response.json();
            if (!result.valid) {
                this.showError(mobile, result.message);
            } else {
                this.removeError(mobile);
            }
        } catch (error) {
            console.error('Mobile validation error:', error);
            this.showError(mobile, 'Error validating mobile number. Please try again.');
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

    // Initialize form validation
    const forms = document.querySelectorAll('.registration-form');
    forms.forEach(form => {
        const validator = new FormValidator(form);
        
        // Add input event listeners for real-time validation
        form.querySelectorAll('input').forEach(field => {
            let timeout;
            field.addEventListener('input', function() {
                clearTimeout(timeout);
                timeout = setTimeout(async () => {
                    if (field.name === 'email') {
                        await validator.validateEmail();
                    } else if (field.name === 'mobile') {
                        await validator.validateMobile();
                    }
                }, 500); // Debounce validation
            });
        });
    });
}); 