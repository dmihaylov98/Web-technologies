<?php
    require_once "../database_files/DB.php";
    require_once "../database_files/processInput.php";

    session_start();
    header("Content-type: application/json");

    function login() {
        if ($_POST) {
            $data = json_decode($_POST["data"], true);

            $username = isset($data[0]) ? testInput($data[0]) : "";
            $password = isset($data[1]) ? testInput($data[1]) : "";

            $db = new Database();
            $errors = [];
            $response = [];
            
            $data = [
                "username" => $username,
                "password" => $password
            ];
            
            $res = $db->checkLogin($data);

            if ($res["success"] == false) {
                $errors[] = $res["error"];
            }
            
            if ($errors) {
                $response = ["success" => false, "error" => $errors];
            }
            else {
                $response = ["success" => true, "role" => $res["role"]];

                $_SESSION["username"] = $username;
            }
            
            echo json_encode($response);
        }
    }

    login();
?>