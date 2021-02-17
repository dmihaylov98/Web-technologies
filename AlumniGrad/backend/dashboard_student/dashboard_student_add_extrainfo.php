<?php
    require_once "../database_files/DB.php";
    require_once "../database_files/processInput.php";

    session_start();
    header("Content-type: application/json");

    if ($_POST) {

        $data = json_decode($_POST["data"], true);

        $facnum = isset($data[0]) ? testInput($data[0]) : "";
        $toga = isset($data[1]) ? testInput($data[1]) : "";
        $hat = isset($data[2]) ? testInput($data[2]) : "";
        $places = isset($data[3]) ? testInput($data[3]) : "";
        $speech = isset($data[4]) ? testInput($data[4]) : "";

        $db = new Database();
        $errors = [];
        $response = [];

        $data = [
            "fn" => $facnum,
            "toga" => $toga,
            "hat" => $hat,
            "places" => $places,
            "speech" => $speech
        ];

        if (!$errors) {
            $res = $db->insertExtraInfo($data);

            if ($res["success"] == false) {
                $errors[] = $res["error"];
            }   
        }

        if ($errors) {
            $response = ["success" => false, "error" => $errors];
        }
        else {
            $response = ["success" => true, "message" => "Информацията е попълнена!"];
        }

       echo json_encode($response);
    }
?>