<?php
    include "components/mysql_connect.php";

    $data = json_decode(file_get_contents("php://input"), true);
    $userID = $data["username"];

    $query = "SELECT b.id, b.name, b.price, b.imgurl FROM bucket a, menu b WHERE a.menuId = b.id AND a.username = ?";
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
        $bucket = array();
        $key = 0;

        while($row = mysqli_fetch_assoc($result)){
            $menu["key"] = $key;
            $menu["mid"] = $row["id"];
            $menu["name"] = $row["name"];
            $menu["price"] = $row["price"];
            $menu["imgurl"] = $row["imgurl"];
            array_push($bucket, $menu);

            $key += 1;
        } 

        $response["bucket"] = $bucket;
        $response["success"] = true;
    }
    else{
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit();
    }

    echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>