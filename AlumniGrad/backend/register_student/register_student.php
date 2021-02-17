<?php
    require_once "../database_files/DB.php";
    require_once "../database_files/processInput.php";
    require_once "../models/student_user.php";

    header("Content-type: application/json");

    function registerStudent() {
        if ($_POST) {
            $data = json_decode($_POST["data"], true);

            $username = isset($data[0]) ? testInput($data[0]) : "";
            $firstname = isset($data[1]) ? testInput($data[1]) : "";
            $familyname = isset($data[2]) ? testInput($data[2]) : "";
            $email = isset($data[3]) ? testInput($data[3]) : "";
            $password = isset($data[4]) ? testInput($data[4]) : "";
            $facultyNumber = isset($data[5]) ? testInput($data[5]) : "";
            $major = isset($data[6]) ? testInput($data[6]) : "";
            $degree = isset($data[7]) ? testInput($data[7]) : "";
            $phoneNumber = isset($data[8]) ? testInput($data[8]) : "";

            $db = new Database();

            $studentUser = new Student($username, $firstname, $familyname, $email, $password, $facultyNumber, $major, $degree, $phoneNumber);
            $studentUser->isValidStudentUser();
            $errors = $studentUser->getErrors();
            $response = [];


            $data = [
                "fn" => $facultyNumber,
                "username" => $username,
                "name" => $firstname,
                "surname" => $familyname,
                "email" => $email,
                "major" => $major,
                "degree" => $degree,
                "mobile" => $phoneNumber,
                "password" => $password
            ];

            if (!$errors) {
                $res = $db->insertStudentInDB($data);

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

    registerStudent();
?>