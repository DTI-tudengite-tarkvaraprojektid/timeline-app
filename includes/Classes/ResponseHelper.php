<?php

require_once(__DIR__ . '/Response.php');

class ResponseHelper {
    public static function getErrorResponse($message, $status = 400)
    {
        return new Response($status, ['message' => $message]);
    }

    public static function getResponse($data)
    {
        return new Response(200, $data);
    }

    // This will stop the script
    public static function sendResponse(Response $response)
    {
        header('Content-Type: application/json');
        http_response_code($response->getStatus());
        die(json_encode($response->getData()));
    }
}