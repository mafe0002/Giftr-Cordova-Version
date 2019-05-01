<?php

if($postParamSet){
    $token = sha1(time().$_POST["device_id"]);
    
    $sql = "INSERT INTO `users`(`token`, `device_id`) VALUES (?, ?)";
    //$sql = "INSERT INTO `users`(`user_name`, `password`, `token`, `device_id`) VALUES (?, ?, ?, ?)";
    
    try{
        $rs = $pdo_link->prepare($sql);
        $rs->execute(array($token, $_POST["device_id"]));
        
        $data = array("user_id"=>$pdo_link->lastInsertId(),
                     "device_id"=>$_POST["device_id"],
                     "token"=>$token);
        $resp = new APIResponse(200, $data, "User Registered");
    } catch (Exception $e) {
        $resp = new APIResponse(500, $e, "Internal database error");
    }
} else {
    $resp = new APIResponse(400, array(), "Required Parameters(device_id) Missing");
}