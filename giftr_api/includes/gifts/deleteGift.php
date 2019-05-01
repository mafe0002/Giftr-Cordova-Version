<?php

// Create prepared SQL statement
// Append people_id in the SQL statement as opposed to passing during ->execute(). 
$sql = "DELETE FROM `gifts` WHERE gift_id =" . $param;

$data = array();

// check for params/record id and set the sql statement
if ($param) {
    try {
        $rs = $pdo_link->prepare($sql);
        $rs->execute();
        $resp = new APIResponse(200, $data, "Record Deleted");

    } catch (Exception $e) {

        // put some error handling here and build an error response.
        $resp = new APIResponse(500, $e, "Internal database error: Deletion Unsuccessful");
    }
} else {
    $resp = new APIResponse(400, $data, "Required Parameter Missing: Person id");
}