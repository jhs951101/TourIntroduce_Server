<?php
    include "components/getdomain.php";
    include "components/mysql_connect.php";

    $query = "
        SELECT a.*, AVG(b.score) as avg, COUNT(b.score) as cnt
        FROM restaurant a LEFT OUTER JOIN scoreinfo b ON a.id = b.resId
        GROUP BY a.id
    ";
    
    $statement = mysqli_prepare($con, $query);

    if($statement === false){
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
        $restaurants = array();
        $count = 0;

        while($row = mysqli_fetch_assoc($result)){
            $restaurant = array();
            $restaurant["name"] = $row["name"];
            $restaurant["time"] = $row["time"];
            $restaurant["phone"] = $row["phone"];
            $restaurant["address"] = $row["address"];
            $restaurant["imgurl"] = $domain . "/images/restaurant/" . $row["imgurl"];
            $restaurant["avg"] = $row["avg"];
            $restaurant["cnt"] = $row["cnt"];

            if(empty($restaurant["avg"]))
                $restaurant["avg"] = 0;

            array_push($restaurants, $restaurant);
            $count += 1;
        } 
    
        $response["restaurants"] = $restaurants;
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