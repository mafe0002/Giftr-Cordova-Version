<?php

class APIResponse
{
    public $code;
    public $date;
    public $message;
    
    private function setHeaders() {
        if($_SERVER['REQUEST_METHOD']=="OPTIONS"){
            header("Access-Control-Allow-Origin: *");
            header("Allow: GET,DELETE,PUT,POST");
            header("Access-Control-Allow-Headers: Giftr-Token");
            header("Content-type: application/json");
        }else{
            header("Content-type: application/json");
            header("Access-Control-Allow-Origin: *");
            header("Access-Control-Allow-Headers: Giftr-Token");
        }
        
        
        switch ($this->code){
            case 200:
                header("HTTP/1.1 200 OK");
                break;
            case 201:
                header("HTTP/1.1 201 Created");
                break;
            case 202:
                header("HTTP/1.1 202 Accepted");
                break;
            case 400:
                header("HTTP/1.1 400 Bad Request");
                break;
            case 401:
                header("HTTP/1.1 401 Unauthorized");
                break;
            case 403:
                header("HTTP/1.1 403 Forbidden");
                break;
            case 404:
                header("HTTP/1.1 404 Not Found");
                break;
            default:
                header("HTTP/1.1 405 Not sure what this is");
                break;
        }
    }
    
    public function __construct ($HTTPStatusCode, $payload, $message){
        $this->code = $HTTPStatusCode;
        $this->data = $payload;
        $this->message = $message;
    }
    
    
    public function send() {
        $this->setHeaders();
        echo json_encode($this);
    }
}
