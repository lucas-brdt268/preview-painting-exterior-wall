<?php 
require_once "./include/config.php";

function asset($path)
{
    global $BASE_URL;
    return "$BASE_URL/assets/$path";
}

function only_post()
{
    if($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("HTTP/1.1 403 Unauthorized");
        exit;
    }
}