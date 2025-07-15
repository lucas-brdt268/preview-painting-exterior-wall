<?php 
require_once "./include/config.php";

function only_post()
{
    if($_SERVER['REQUEST_METHOD'] !== 'POST') {
        res403();
    }
}

function only_get()
{
    if($_SERVER['REQUEST_METHOD'] !== 'GET') {
        res403();
    }
}

function only_ajax()
{
    if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest') {
        res403();
    }
}

function res403()
{
    header("HTTP/1.1 403 Unauthorized");
    echo "403 Unauthorized";
    exit;
}

function ajax($data, $status = 200)
{
    http_response_code($status);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

function asset($path)
{
    global $BASE_URL;
    return "$BASE_URL/assets/$path";
}
