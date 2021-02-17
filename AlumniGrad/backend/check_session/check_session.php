<?php
    require_once "../database_files/DB.php";
    require_once "../database_files/processInput.php";
    
    session_start();
 
    if (isset($_SESSION["username"])) {
        echo json_encode([
          "success" => true
        ]);
      } else {
        echo json_encode([
          "success" => false
        ]);
      }
?>