<?php
require_once "../database_files/DB.php";
require_once "../database_files/processInput.php";

session_start();

$db = new Database();
$res1 = $db->participStat();
$res2 = $db->totalAttendance();
$res = ["res1" => $res1, "res2" => $res2];
echo json_encode($res);

?>