<?php
    require_once "user.php";

    final class Administration extends User {
        private $position;

        public function __construct($username, $firstname, $familyname, $email, $password, $position) {
            parent::__construct($username, $firstname, $familyname, $email, $password);
            $this->position = $position;
        }

        public function getPosition() {
            return $this->position;
        }

        public function isValidPosition() {
            if (empty($this->position)) {
                $this->errors[] = "Длъжността е задължително поле.";
            }
            if (mb_strlen($this->position, "utf-8") > 50) {
                $this->errors[] = "Дължината на полето длъжност трябва да е до 50 символа.";
            }
        }

        public function isValidAdministrationUser() {
            $this->isValidUsername();
            $this->isValidFirstName();
            $this->isValidFamilyName();
            $this->isValidEmail();
            $this->isValidPassword();
            $this->isValidPosition();
        }
    }
?>