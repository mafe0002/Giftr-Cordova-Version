<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$httpMethod = $_SERVER["REQUEST_METHOD"];
if (isset($_SERVER["PATH_INFO"])) {
    $param = ltrim($_SERVER["PATH_INFO"], '/');
} else {
    $param = null;
}

include "../includes/APIResponse.php";
include "../includes/dbconnect.php";
include "../includes/authenticate.php";


if($auth) {
    switch ($httpMethod) {
        case "GET":
            include "../includes/gifts/getGifts.php";
            break;
            
        case "POST":
            include "../includes/gifts/postGift.php";
            break;
            
        case "PUT":
            include "../includes/gifts/editGift.php";
            break;
            
        case "DELETE":
            include "../includes/gifts/deleteGift.php";
            break;
            
        default:
            $resp = new APIResponse(400, array(), "Method not Supported");
            
            
    }
} 

$pdo_link = null;

$resp->send();