<?php
// check post params are set
// IDeally we should also be checking that dates are dates, text is text etc
// this block could be incorporated with the block below.

if (isset($_POST["person_name"])
    && isset($_POST["user_id"])
    && isset($_POST["dob"])) {

    $postParamsSet = true;
} else {
    $postParamsSet = false;
}

// process the request
if ($postParamsSet) {

    $sql = "INSERT INTO `people`( `person_name`, `user_id`, `dob`) VALUES (?,?,?)";

    try {
        $rs = $pdo_link->prepare($sql);
        $rs->execute(array(
                $_POST["person_name"],
                $_POST["user_id"],
                $_POST["dob"])
        );

        $data = array(
            "id"=>$pdo_link->lastInsertId(),
            "person_name"=>$_POST["person_name"],
            "user_id"=>$_POST["user_id"],
            "dob"=>$_POST["dob"]
        );

        $resp = new APIResponse(200, $data, "Person Added Successfully");

    } catch (Exception $e) {
        // put some error handling here and build an error response.
        $resp = new APIResponse(500, $e, "Internal database error");
    }

} else {
    $resp = new APIResponse(400, array(), "Required Parameters Missing");
}