<?php
    include "components/mysql_connect.php";

    $data = json_decode(file_get_contents("php://input"), true);
    $userID = $data["username"];
    $userPassword = $data["password"];

    $query = "SELECT * FROM user WHERE username = ? LIMIT 1";
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
            if(password_verify($userPassword, $row["password"])){
                $response["username"] = $row["username"];
                $response["name"] = $row["name"];
                $response["success"] = true;
            }
            else{
                $response["errmsg"] = "incorrectPassword";
            }
        } 
        else{
            $response["errmsg"] = "noUsername";
        }
    }
    else{
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit();
    }

    echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>