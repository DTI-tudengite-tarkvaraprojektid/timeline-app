<?php
require_once(__DIR__ . '/../includes/base.php');  

$response = ResponseHelper::getResponse(['message' => 'This is a successful response!']);
ResponseHelper::sendResponse($response);