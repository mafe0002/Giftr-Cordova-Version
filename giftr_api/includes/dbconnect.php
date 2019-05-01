<?php

//const DB_NAME ="liu00415_giftr";
//const DB_HOST = "localhost:3306";
//const DB_USER = "root";
//const DB_PASSWORD = "root";

const DB_NAME ="finalDatabase";
const DB_HOST = "localhost";
const DB_USER = "root";
const DB_PASSWORD = "root";

const DSN = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME;

try {
    $pdo_link = new PDO(DSN, DB_USER, DB_PASSWORD);

    // The following statements will display PDO error messages 
    // turn off in prod 
    $pdo_link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo_link->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    

} catch (PDOException $exception) {
    echo $exception->getMessage();
}
