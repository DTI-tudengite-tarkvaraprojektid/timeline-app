<?php
require_once(__DIR__ . '/../includes/base.php');
if(!isset($_GET['id'])) {
  $response = ResponseHelper::getErrorResponse('Event not specified');
  ResponseHelper::sendResponse($response);
}

$stmt = $db->prepare('DELETE FROM event WHERE id=?');
$stmt->execute([$_GET['id']]);

$response = ResponseHelper::getResponse(['message' => 'Event deleted!']);
ResponseHelper::sendResponse($response);
