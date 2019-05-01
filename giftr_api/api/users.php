<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include "../includes/APIResponse.php";
include "../includes/dbconnect.php";

$resp = null;

$httpMethod = $_SERVER["REQUEST_METHOD"];

if (isset($_SERVER["PATH_INFO"])) {
    $param = ltrim($_SERVER["PATH_INFO"], '/');
} else {
    $param = null;
}

if(isset($_POST["device_id"]))
{
    $postParamSet = true;
} else{
    $postParamSet = false;
}

switch($httpMethod) {
    case "POST":
        include "../includes/users/registerUser.php";
        break;
    case "GET":
        include "../includes/users/getToken.php";
        break;
    default:
        $resp = new APIResponse(400, array(), "Method not Supported");
        echo "Method Not Supported";
        break;
}

$pdo_link = null;

$resp->send();