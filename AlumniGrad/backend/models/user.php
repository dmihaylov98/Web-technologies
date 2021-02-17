<?php
    class User {
        protected $username;
        protected $firstname;
        protected $familyname;
        protected $email;
        protected $password;
        protected $errors = [];

        public function __construct($username, $firstname, $familyname, $email, $password) {
            $this->username = $username;
            $this->firstname = $firstname;
            $this->familyname = $familyname;
            $this->email = $email;
            $this->password = $password;
        }
    
        public function getUsername() {
            return $this->username;
        }
    
        public function getFirstName() {
            return $this->firstname;
        }
   
        public function getFamilyName() {
            return $this->familyname;
        }
    
        public function getEmail() {
            return $this->email;
        }
    
        public function getErrors() {
            return $this->errors;
        }
    
        public function isValidUsername() {
            if (empty($this->username)) {
                $this->errors[] = "Потребителското име е задължително поле.";
            }
            elseif (mb_strlen($this->username, "utf-8") < 3 || mb_strlen($this->username, "utf-8") > 50) {
                $this->errors[] = "Потребителското име трябва да е между 3 и 50 символа.";
            }
        }
    
        public function isValidFirstName() {
            if (empty($this->firstname)) {
                $this->errors[] = "Името е задължтелно поле.";
            }
            elseif (mb_strlen($this->firstname, "utf-8") > 50) {
                $this->errors[] = "Дължината на името трябва да е до 50 символа.";
            }
        }
    
        public function isValidFamilyName() {
            if (empty($this->familyname)) {
                $this->errors[] = "Фамилията е задължително поле.";
            }
            elseif (mb_strlen($this->familyname, "utf-8") > 50) {
                $this->errors[] = "Дължината на фамилията трябва да е до 50 символа.";
            }
        }
    
        public function isValidEmail() {
            if (empty($this->email)) {
                $this->errors[] = "Имейлът е задължително поле.";
            }
            elseif (mb_strlen($this->email, "utf-8") > 50) {
                $this->errors[] = "Дължината на имейла трябва да е до 50 символа.";
            }

            elseif (!preg_match('/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD', $this->email)) {
                $this->errors[] = "Невалиден имейл!";
            }
        }
    
        public function isValidPassword() {
            if (empty($this->password)) {
                $this->errors[] = "Паролата е задължително поле.";
            }
            elseif (mb_strlen($this->password, "utf-8") < 8) {
                $this->errors[] = "Дължината на паролата трябва да е поне 8 символа.";
            }
            elseif (mb_strlen($this->password, "utf-8") > 30) {
                $this->errors[] = "Дължината на паролата трябва да е до 30 символа.";
            }

            // regex is taken from https://stackoverflow.com/questions/19605150/regex-for-password-must-contain-at-least-eight-characters-at-least-one-number-a
	        // Minimum eight characters, at least one uppercase letter, one lowercase letter and one number, maximum length is 30 characters
            elseif (!preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[a-zA-Z0-9!@#\$%\^\&*\)\(+=._-]{8,30}$/', $this->password)) {
                $this->errors[] = "Невалидна парола, паролата трябва да съдържа поне една главна буква, поне една малка буква и поне една цифра.";
            }
        }
    }
?>