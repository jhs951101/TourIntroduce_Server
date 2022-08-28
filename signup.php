<?php
    include "components/mysql_connect.php";

    $data = json_decode(file_get_contents("php://input"), true);
    $userID = $data["username"];
    $userPassword = $data["password"];
    $userName = $data["name"];

    //

    $query = "SELECT * FROM user WHERE username = ?";
    $statement = mysqli_prepare($con, $query);

    if($statement === false){
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit();
    }

    $bind = mysqli_stmt_bind_param($statement, "s", $userID);

    if($bind === false){
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit();
    }

    $exec = mysqli_stmt_execute($statement);

    if($exec === false){
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit();
    }

    $result = mysqli_stmt_get_result($statement);

    if($result){
        if($row = mysqli_fetch_assoc($result)){
            $response["errmsg"] = "duplicateId";
            echo json_encode($response, JSON_UNESCAPED_UNICODE);
            exit();
        } 
    }
    else{
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit();
    }

    //

    $query = "INSERT INTO user(username,password,name) VALUES (?,?,?)";
    $statement = mysqli_prepare($con, $query);

    if($statement === false){
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit();
    }

    $bind = mysqli_stmt_bind_param($statement, "sss", $userID, password_hash($userPassword, PASSWORD_BCRYPT), $userName);

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