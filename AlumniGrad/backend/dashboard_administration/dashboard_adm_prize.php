<?php
    require_once "../database_files/DB.php";
    require_once "../database_files/processInput.php";
    
    session_start();
    header("Content-type: application/json");

    if ($_POST) {

        $data = json_decode($_POST["data"], true);

        $fn2 = isset($data[0]) ? testInput($data[0]) : "";
        $prize1 = isset($data[1]) ? testInput($data[1]) : "";

        $db = new Database();
        $errors = [];
        $response = [];

        $data = [
            "fn" => $fn2,
            "prize" => $prize1
        ];

        if (!$errors) {
            $res = $db->checkParticipation($fn2);

            if ($res["success"] == false) {
                $errors[] = $res["error"];
            }
            else {
                if ($res["participate"] == true) {
                    $res1 = $db->giveAward($fn2, $prize1);

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