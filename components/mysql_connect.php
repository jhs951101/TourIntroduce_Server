<?php
    $isProduction = true;

    if($isProduction == true){
        $user = "tails1101";
    }
    else{
        $user = "know";
    }

    $con = mysqli_connect("127.0.0.1", $user, "jhs951101", $user);

    $response = array();
    $response["success"] = false;

    if(!$con){
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit();
    }

    mysqli_query($con, 'SET NAMES utf8');
?>