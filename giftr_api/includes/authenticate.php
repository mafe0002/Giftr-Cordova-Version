<?php
// Check for auth headers
// the apache_request_headers() method will return all the request headers in an array
$headers = apache_request_headers();

// Check the the header we're looking for exists
if (isset($headers["Giftr-Token"])) {

    // Get the token
    $authToken = $headers["Giftr-Token"];
    $sql = "SELECT * FROM users WHERE token = ?";

    // Create an auth flag
    $auth = false;

    try {
        $rs = $pdo_link->prepare($sql);

        // returns results
        if ($rs->execute(array($authToken))) {

            // returns only one record
            // fair to say that if the query returns a record... we have a match
            if ($rs->rowCount() == 1) {

                //set the auth flag to true
                $auth = true;
            }
        }
    } catch (Exception $e) {
        $resp = new APIResponse(500, $e, "Internal database error");
    }
} else {
    $authToken = null;
    $auth = false;
    $resp = new APIResponse(400, array(), "Required Parameters(token) Missing");
}