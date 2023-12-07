<?php

require "vendor/autoload.php";

// load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();

// safely get current url
if(isset($_SERVER["HTTP"]) && $_SERVER["HTTPS"] === "on")   
    $url = "https://";   
else  
    $url = "http://";

$url .= $_SERVER["HTTP_HOST"]; 

// get API key & base URL
$api_key = $_ENV["CANVAS_KEY"];
$base_url = $_ENV["CANVAS_BASE_URL"];

// API request
$api_link = $url . "/scraper/scraper.py?url=" . $base_url . "&auth=" . $api_key;
// data stores
$api_data = file_get_contents($api_link);

// return data
echo $api_data;