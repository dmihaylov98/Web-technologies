<?php
    require_once "../database_files/DB.php";
    require_once "../database_files/processInput.php";

    session_start();

    $db = new Database();
    $res = $db->studentSpeeches();
    echo json_encode($res);
?>