<?php
    require_once "../database_files/DB.php";
    require_once "../database_files/processInput.php";
    require_once "../models/administration_user.php";

    header("Content-type: application/json");

    function registerAdministration() {
        if ($_POST) {

            $data = json_decode($_POST["data"], true);

            $username = isset($data[0]) ? testInput($data[0]) : "";
            $firstname = isset($data[1]) ? testInput($data[1]) : "";
            $familyname = isset($data[2]) ? testInput($data[2]) : "";
            $email = isset($data[3]) ? testInput($data[3]) : "";
            $password = isset($data[4]) ? testInput($data[4]) : "";
            $position = isset($data[5]) ? testInput($data[5]) : "";
            
            $db = new Database();

            $administrationUser = new Administration($username, $firstname, $familyname, $email, $password, $position);
            $administrationUser->isValidAdministrationUser();
            $errors = $administrationUser->getErrors();
            $response = [];

            $data = [
                "username" => $username,
                "name" => $firstname,
                "surname" => $familyname,
                "email" => $email,
                "password" => $password,
                "position" => $position
            ];

            // no errors
            if (!$errors) {
                $res = $db->insertAdministration($data);

                if ($res["success"] == false) {
                    $errors[] = $res["error"];
                }
            }

            if ($errors) {
                $response = ["success" => false, "error" => $errors];
            }
            else {
                $response = ["success" => true, "message" => "Регистрацията е успешна!"];
            }
            
            echo json_encode($response);
        }
    }

    registerAdministration();
?>