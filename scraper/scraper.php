<?php

$api_link = "http://127.0.0.1/scraper/scraper.py?name=John";
$api_data = file_get_contents($api_link);

echo $api_data;
