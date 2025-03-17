<?php
class Validator {
    private $errors = [];
    private $data;
    
    public function __construct($data) {
        $this->data = $data;
    }
    
    public function validate() {
        $this->validateRequired();
        $this->validateEmail();
        $this->validateMobile();
        $this->validateFiles();
        
        return empty($this->errors);
    }
    
    public function getErrors() {
        return $this->errors;
    }
    
    private function validateRequired() {
        $requiredFields = [
            'name' => 'Name',
            'email' => 'Email',
            'mobile' => 'Mobile Number'
        ];
        
        foreach ($requiredFields as $field => $label) {
            if (empty($this->data[$field])) {
                $this->errors[$field] = "$label is required";
            }
        }
    }
    
    private function validateEmail() {
        if (!empty($this->data['email'])) {
            if (!filter_var($this->data['email'], FILTER_VALIDATE_EMAIL)) {
                $this->errors['email'] = "Invalid email format";
            }
        }
    }
    
    private function validateMobile() {
        if (!empty($this->data['mobile'])) {
            $mobile = preg_replace('/[^0-9]/', '', $this->data['mobile']);
            if (strlen($mobile) < 10 || strlen($mobile) > 15) {
                $this->errors['mobile'] = "Mobile number must be between 10 and 15 digits";
            }
        }
    }
    
    private function validateFiles() {
        $allowedTypes = ['pdf', 'jpg', 'jpeg', 'png'];
        $maxSize = 5 * 1024 * 1024; // 5MB
        
        foreach ($_FILES as $field => $file) {
            if ($file['error'] === 4) continue; // No file uploaded
            
            if ($file['error'] !== 0) {
                $this->errors[$field] = "Error uploading file";
                continue;
            }
            
            $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, $allowedTypes)) {
                $this->errors[$field] = "Invalid file type. Allowed types: " . implode(', ', $allowedTypes);
            }
            
            if ($file['size'] > $maxSize) {
                $this->errors[$field] = "File size must be less than 5MB";
            }
        }
    }
} 