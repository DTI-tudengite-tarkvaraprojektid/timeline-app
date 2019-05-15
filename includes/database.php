<?php

require_once(__DIR__ . '/../config/config.php');

try {
    $db = new PDO(
        "mysql:host=" . $config['DB_HOST'] . ";dbname=" . $config['DB_NAME'],
        $config['DB_USER'],
        $config['DB_PASSWORD']
    );

    // $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)
{
    die("<h4>Failed to connect to database: Webpage unavailable.</h4>");
}