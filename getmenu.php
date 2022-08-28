<?php
    include "components/mysql_connect.php";

    if(empty($_GET["name"])){
		echo json_encode($response, JSON_UNESCAPED_UNICODE);
		exit();
	}

    $resName = $_GET["name"];

    $query = "
        SELECT a.*
        FROM menu a, restaurant b
        WHERE a.resId = b.id AND b.name = ?
        ORDER BY a.importance DESC
    ";
    $statement = mysqli_prepare($con, $query);

    if($statement === false){
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit();
    }

    $bind = mysqli_stmt_bind_param($statement, "s", $resName);

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
        $menus = array();
        $count = 0;

        while($row = mysqli_fetch_assoc($result)){
            $menu = array();
            $menu["id"] = $row["id"];
            $menu["name"] = $row["name"];
            $menu["price"] = $row["price"];
            $menu["importance"] = $row["importance"];
            $menu["explanation"] = $row["explanation"];
            $menu["imgurl"] = $row["imgurl"];
            array_push($menus, $menu);
            $count += 1;
        } 
    
        $response["menus"] = $menus;
        $response["count"] = $count;
        mysqli_free_result($result);
        mysqli_stmt_close($statement);
        $response["success"] = true;
    }
    else{
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit();
    }

    echo json_encode($response, JSON_UNESCAPED_UNICODE);
?>