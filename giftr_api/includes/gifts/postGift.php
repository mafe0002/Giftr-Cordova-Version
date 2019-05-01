<?php
// check post params are set
// IDeally we should also be checking that dates are dates, text is text etc
// this block could be incorporated with the block below.

if (isset($_POST["gift_title"])
    && isset($_POST["person_id"])
    && isset($_POST["gift_url"])
    && isset($_POST["gift_price"])
    && isset($_POST["gift_store"])) {

    $postParamsSet = true;
} else {
    $postParamsSet = false;
}

// process the request
if ($postParamsSet) {

    $sql = "INSERT INTO `gifts`( `gift_title`, `person_id`, `gift_url`, `gift_price`, `gift_store`) VALUES (?,?,?,?,?)";

    try {
        $rs = $pdo_link->prepare($sql);
        $rs->execute(array(
                $_POST["gift_title"],
                $_POST["person_id"],
                $_POST["gift_url"],
                $_POST["gift_price"],
                $_POST["gift_store"]
        )
        );

        $data = array(
            "id"=>$pdo_link->lastInsertId(),
            "gift_title"=>$_POST["gift_title"],
            "person_id"=>$_POST["person_id"],
            "gift_url"=>$_POST["gift_url"],
            "gift_price"=>$_POST["gift_price"],
            "gift_store"=>$_POST["gift_store"]
        );

        $resp = new APIResponse(200, $data, "Gift Added Successfully");

    } catch (Exception $e) {
        // put some error handling here and build an error response.
        $resp = new APIResponse(500, $e, "Internal database error");
    }

} else {
    $resp = new APIResponse(400, array(), "Required Parameters Missing");
}