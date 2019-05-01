<?php

// get json payload
$rawString = file_get_contents("php://input");
$put_vars = json_decode($rawString, true);

// Still need to test for required params
if (isset($put_vars["person_name"])
    && isset($put_vars["user_id"])
    && isset($put_vars["dob"])) {
    $paramSet = true;
} else {
    $paramSet = false;
}

//echo $rawString;
// process the request only if passed a gift_id(param) and all required fields are present
// Since we don't know which field will be passed to us in the request, we expect all to be passed and simply re-write the
//   values to the database.

// remember that we test for the path param in the controller
if ($paramSet && $param) {
    $sql = "UPDATE `people` SET `person_name`=?,`user_id`=?,`dob`=? WHERE person_id = " . "$param";

    try {
        $rs = $pdo_link->prepare($sql);
        $rs->execute(array(
                $put_vars["person_name"],
            $put_vars["user_id"],
            $put_vars["dob"]
        ));

        // prep the data to return to the requester/client/consumer
        $data = array(
            "person_id"=>$param,
            "gift_title"=>$put_vars["person_name"],
            "user_id"=>$put_vars["user_id"],
            "dob"=>$put_vars["dob"]
        );

        $resp = new APIResponse(200, $data, "Person Updated Successfully");

    } catch (Exception $e) {
        $resp = new APIResponse(500, $e , "Internal database error");
    }

} else {
    $resp = new APIResponse(400, array(), "Required Parameters Missing");
}