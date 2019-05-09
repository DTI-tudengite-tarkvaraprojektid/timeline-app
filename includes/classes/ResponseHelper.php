<?php

require_once(__DIR__ . '/Response.php');

class ResponseHelper {
    public static function getErrorResponse(string $message, int $status = 400) : Response
    {
        return new Response($status, ['message' => $message]);
    }

    public static function getResponse(array $data) : Response
    {
        return new Response(200, $data);
    }

    // This will stop the script
    public static function sendResponse(Response $response) : Response
    {
        header('Content-Type: application/json');
        http_response_code($response->getStatus());
        die(json_encode($response->getData()));
    }
}