<?php
require_once(__DIR__ . '/../includes/base.php');
if(!isset($_GET['timeline'])) {
  $response = ResponseHelper::getErrorResponse('Event not specified');
  ResponseHelper::sendResponse($response);
}

$stmt = $db->prepare('SELECT id,title,time FROM event WHERE timeline_id=?');
$stmt->execute([$_GET['timeline']]);

$response = ResponseHelper::getResponse($stmt->fetchAll());
ResponseHelper::sendResponse($response);
