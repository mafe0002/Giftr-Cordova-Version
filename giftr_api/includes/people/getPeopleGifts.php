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
            $row['gifts'] = array();
            $data[] = $row;
        }

    // if no database error but no data returned then no matching records were found.
    if (!$data) {
        $resp = new APIResponse(200, $data, "No record found");
    } else {
        $sqlGetPeopleGifts = "SELECT * FROM gifts";
        try{
            $rsGifts = $pdo_link->prepare($sqlGetPeopleGifts);
            $rsGifts->execute();
            
            while ($gift = $rsGifts->fetch(PDO::FETCH_ASSOC)) {
            foreach($data as $key => $value){
                if($gift['person_id'] == $value['person_id']){
                    $data[$key]['gifts'][] = $gift;
                }
            }
        }
        } catch (Exception $e) {

    // put some error handling here and build an error response.
    $resp = new APIResponse(500, $e, "Internal database error Gifts");
}
        $resp = new APIResponse(200, $data, "OK");
    }

} catch (Exception $e) {

    // put some error handling here and build an error response.
    $resp = new APIResponse(500, $e, "Internal database error People");
}
