<?php
require_once(__DIR__ . '/../includes/base.php');  

$response = ResponseHelper::getErrorResponse('This is an error.');
ResponseHelper::sendResponse($response);