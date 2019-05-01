<?php

// get json payload
$rawString = file_get_contents("php://input");
$put_vars = json_decode($rawString, true);

// Still need to test for required params
if (isset($put_vars["gift_title"])
    && isset($put_vars["person_id"])
    && isset($put_vars["gift_url"])
    && isset($put_vars["gift_price"])
    && isset($put_vars["gift_store"])) {
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
    $sql = "UPDATE `gifts` SET `gift_title`=?,`person_id`=?,`gift_url`=?, `gift_price`=?, `gift_store`=? WHERE gift_id = " . "$param";

    try {
        $rs = $pdo_link->prepare($sql);
        $rs->execute(array(
                $put_vars["gift_title"],
            $put_vars["person_id"],
            $put_vars["gift_url"],
            $put_vars["gift_price"],
            $put_vars["gift_store"]
        ));

        // prep the data to return to the requester/client/consumer
        $data = array(
            "gift_id"=>$param,
            "gift_title"=>$put_vars["gift_title"],
            "person_id"=>$put_vars["person_id"],
            "gift_url"=>$put_vars["gift_url"],
            "gift_price"=>$put_vars["gift_price"],
            "gift_store"=>$put_vars["gift_store"]
        );

        $resp = new APIResponse(200, $data, "Gift Updated Successfully");

    } catch (Exception $e) {
        $resp = new APIResponse(500, $e , "Internal database error");
    }

} else {
    $resp = new APIResponse(400, array(), "Required Parameters Missing");
}