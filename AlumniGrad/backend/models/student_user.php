<?php
    require_once "user.php";

    final class Student extends User {
        private $facultyNumber;
        private $major;
        private $degree;
        private $phoneNumber;

        public function __construct($username, $firstname, $familyname, $email, $password, $facultyNumber, $major, $degree, $phoneNumber) {
            parent::__construct($username, $firstname, $familyname, $email, $password);
            $this->facultyNumber = $facultyNumber;
            $this->major = $major;
            $this->degree = $degree;
            $this->phoneNumber = $phoneNumber;
        }
    
        public function getFacultyNumber() {
            return $this->facultyNumber;
        }
    
        public function getMajor() {
            return $this->major;
        }
    
        public function getDegree() {
            return $this->degree;
        }
    
        public function getPhoneNumber() {
            return $this->phoneNumber;
        }

        public function isValidFacultyNumber() {
            if (empty($this->facultyNumber)) {
                $this->errors[] = "Факултетният намер е задължително поле.";
            }
            elseif (mb_strlen($this->facultyNumber, "utf-8") < 5 || mb_strlen($this->facultyNumber, "utf-8") > 9) {
                $this->errors[] = "Дължината на факултетния номер трябва да е между 5 и 9 цифри.";
            }
            else {
                $arr = mb_str_split($this->facultyNumber);
           
                foreach ($arr as $fnDigit) {
                    if ($fnDigit < "0" || $fnDigit > "9") {
                         $this->errors[] = "Факултетният номер трябва да съдържа само цифри.";
                        break;
                    }
                }
            }
        }
    
        public function isValidMajor() {
            if (empty($this->major)) {
                $this->errors[] = "Специалността е задължително поле.";
            }
            elseif (mb_strlen($this->major, "utf-8") > 30) {
                $this->errors[] = "Дължината на специалността трябва да е до 30 символа.";
            }
        }
    
        public function isValidDegree() {
            if (empty($this->degree)) {
                $this->errors[] = "Степента е задължително поле.";
            }

            if (! ($this->degree === "бакалавър" || $this->degree === "магистър" || $this->degree === "докторант")) {
                $this->errors[] = "Невалидна степен.";
            }
        }
    
        public function isValidPhoneNumber() {
            if (empty($this->phoneNumber)) {
                $this->errors[] = "Въвеждането на телефонен номер е задължително.";
            }

            elseif (mb_strlen($this->phoneNumber, "utf-8") != 10) {
                $this->errors[] = "Дължината на телефонния номер трябва да бъде 10 символа.";
            }
            else {
                $arr = mb_str_split($this->phoneNumber);

                foreach($arr as $digit) {
                    if ($digit < "0" || $digit > "9") {
                        $this->errors[] = "Телефонният номер трябва да съдържа само цифри.";
                        break;
                    }   
                }
            }
        }
    
        public function isValidStudentUser() {
            $this->isValidUsername();
            $this->isValidFirstName();
            $this->isValidFamilyName();
            $this->isValidEmail();
            $this->isValidPassword();
            $this->isValidFacultyNumber();
            $this->isValidMajor();
            $this->isValidDegree();
            $this->isValidPhoneNumber();
        }
    }
?>