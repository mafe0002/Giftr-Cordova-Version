<?php

$sqlGetPeopleAll = "SELECT * FROM people";
$sqlGetPersonByID = "SELECT * FROM people WHERE person_id =". $param;

// check for params/record id and set the associated sql statement
if ($param) {
    $sql = $sqlGetPersonByID;
} else {
    $sql = $sqlGetPeopleAll;
}

//Create and initialize an empty array for the recordset
$data = array();

// get data from the db
try {
    $rs = $pdo_link->prepare($sql);
    $rs->execute();

        while ($row = $rs->fetch(PDO::FETCH_ASSOC)) {
            // pushing each record from the database into the data array
            $data[] = $row;
        }

    // if no database error but no data returned then no matching records were found.
    if (!$data) {
        $resp = new APIResponse(200, $data, "No record found");
    } else {
        $resp = new APIResponse(200, $data, "OK");
    }

} catch (Exception $e) {

    // put some error handling here and build an error response.
    $resp = new APIResponse(500, $e, "Internal database error");
}