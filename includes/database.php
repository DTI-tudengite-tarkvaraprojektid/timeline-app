<?php

require_once(__DIR__ . '/../config/config.php');

$db = new PDO(
    "mysql:host=" . $config['DB_HOST'] . ";dbname=" . $config['DB_NAME'],
    $config['DB_USER'],
    $config['DB_PASSWORD']
);