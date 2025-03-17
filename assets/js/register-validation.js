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