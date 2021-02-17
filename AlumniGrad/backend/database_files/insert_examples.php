<?php
    require_once "DB.php";
    require_once "processInput.php";

    $db = new Database();

    $data1 = ["username" => "gesh", "name" => "Георги", "surname" => "Георгиев", "email" => "gesh@abv.bg",
     "password" => "123456Ab", "fn" => "80000", "major" => "КН", "degree" => "бакалавър", "mobile" => "0888765432"];

    $data2 = ["username" => "nasko", "name" => "Атанас", "surname" => "Атанасов", "email" => "nasko@abv.bg",
    "password" => "123456Ab", "fn" => "70000", "major" => "КН", "degree" => "бакалавър", "mobile" => "0888234567"];

    $data3 = ["username" => "ivancho", "name" => "Иван", "surname" => "Иванов", "email" => "ivan@abv.bg",
    "password" => "123456Ab", "fn" => "60000", "major" => "КН", "degree" => "бакалавър", "mobile" => "0888165342"];

    $data4 = ["username" => "ivana", "name" => "Ивана", "surname" => "Иванова", "email" => "ivana@abv.bg",
    "password" => "123456Ab", "fn" => "50000", "major" => "СИ", "degree" => "бакалавър", "mobile" => "0888645312"];

    $data5 = ["username" => "krasi", "name" => "Красимир", "surname" => "Красимиров", "email" => "krasi@abv.bg",
    "password" => "123456Ab", "fn" => "98765", "major" => "ИИ", "degree" => "магистър", "mobile" => "0888846531"];

    $data6 = ["username" => "acho", "name" => "Ангел", "surname" => "Ангелов", "email" => "angel@abv.bg",
    "password" => "123456Ab", "fn" => "12345", "major" => "ИИ", "degree" => "магистър", "mobile" => "0899124365"];

    $data7 = ["username" => "gena", "name" => "Евгени", "surname" => "Евгениев", "email" => "evgeni@abv.bg",
    "password" => "123456Ab", "fn" => "54321", "major" => "ИИ", "degree" => "магистър", "mobile" => "0899231256"];

    $data8 = ["username" => "stan", "name" => "Станимир", "surname" => "Станимиров", "email" => "stan@abv.bg",
    "password" => "123456Ab", "fn" => "51423", "major" => "ДУ", "degree" => "докторант", "mobile" => "0897342356"];

    $data9 = ["username" => "pesho", "name" => "Петър", "surname" => "Петров", "email" => "petio@abv.bg",
    "password" => "123456Ab", "fn" => "34567", "major" => "ДУ", "degree" => "докторант", "mobile" => "0878343124"];

    $data10 = ["username" => "mitko", "name" => "Димитър", "surname" => "Димитров", "email" => "mitko@abv.bg",
    "password" => "123456Ab", "fn" => "76543", "major" => "ДУ", "degree" => "докторант", "mobile" => "0888905432"];

    $data11 = ["username" =>  "reni", "name" => "Ренета", "surname" => "Иванова", "email" => "reni@abv.bg",
    "password" => "123456Ab", "position" => "инспектор"];

    $db->insertStudentInDB($data1);
    $db->insertStudentInDB($data2);
    $db->insertStudentInDB($data3);
    $db->insertStudentInDB($data4);
    $db->insertStudentInDB($data5);
    $db->insertStudentInDB($data6);
    $db->insertStudentInDB($data7);
    $db->insertStudentInDB($data8);
    $db->insertStudentInDB($data9);
    $db->insertStudentInDB($data10);
    $db->insertAdministration($data11);
?>