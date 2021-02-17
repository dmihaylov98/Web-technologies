<?php
    require_once "../database_files/DB.php";
    require_once "../database_files/processInput.php";

    session_start();
    header("Content-type: application/json");

    if ($_POST) {

        $data = json_decode($_POST["data"], true);

        $participate = isset($data[0]) ? testInput($data[0]) : "";
        $facnum = isset($data[1]) ? testInput($data[1]) : "";

        $db = new Database();
        $errors = [];
        $response = [];

        $data = [
            "participate" => $participate,
            "fn" => $facnum
        ];

        if (!$errors) {
            $res = $db->fillParticipation($_SESSION["username"], $participate);

            if ($res["success"] == false) {
                $errors[] = $res["error"];
            }
            else if ($participate == true) {
                $res1 = $db->insertParticipant($data["fn"]);

                if ($res1["success"] == false) {
                    $errors[] = $res1["error"];
                }
            }
        }

        if ($errors) {
            $response = ["success" => false, "error" => $errors];
        }
        else {
            $response = ["success" => true, "message" => "Заявката за участие е записана!"];
        }

       echo json_encode($response);
    }
?>