<?php
    require_once "../database_files/DB.php";
    require_once "../database_files/processInput.php";
    
    session_start();
    header("Content-type: application/json");

    if ($_POST) {

        $data = json_decode($_POST["data"], true);

        $fn1 = isset($data[0]) ? testInput($data[0]) : "";

        $db = new Database();
        $errors = [];
        $response = [];

        $data = [
            "fn" => $fn1
        ];

        if (!$errors) {
            $res = $db->checkParticipation($fn1);

            if ($res["success"] == false) {
                $errors[] = $res["error"];
            }
            else {
                if ($res["participate"] == true) {
                    $res1 = $db->markTogaAsReturned($fn1);

                    if ($res1["success"] == false) {
                        $errors[] = $res1["error"];
                    } 
                }
                else {
                    $errors[] = $res["message"];
                }
            }
        }

        if ($errors) {
            $response = ["success" => false, "error" => $errors];
        }
        else {
            $response = ["success" => true, "message" => "Промените са записани."];
        }

       echo json_encode($response);
    }
?>