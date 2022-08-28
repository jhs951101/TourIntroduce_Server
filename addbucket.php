<?php
    include "components/mysql_connect.php";

    $data = json_decode(file_get_contents("php://input"), true);
    $userID = $data["username"];
    $menuID = $data["menuid"];

    $query = "INSERT INTO bucket(username,menuId) VALUES (?,?)";
    $statement = mysqli_prepare($con, $query);

    if($statement === false){
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit();
    }

    $bind = mysqli_stmt_bind_param($statement, "si", $userID, $menuID);

    if($bind === false){
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit();
    }

    $exec = mysqli_stmt_execute($statement);

    if($exec === false){
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit();
    }

    mysqli_commit($con);

    $response["success"] = true;
    echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>