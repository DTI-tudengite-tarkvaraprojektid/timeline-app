<?php

require_once(__DIR__ . '/Response.php');

class ResponseHelper {

    /**
     * Creates a response for sending an error message
     * 
     * @param string $message Error text sent as "message"
     * @param int $status HTTP Status code
     * @return \Response Response that can be sent using ResponseHelper::sendResponse();
     */
    public static function getErrorResponse($message, $status = 400)
    {
        return new Response($status, ['message' => $message]);
    }

    /**
     * Creates an HTTP 200 response
     * 
     * @param array $data Response data that will be sent as JSON
     * @return \Response Response that can be sent using ResponseHelper::sendResponse();
     */
    public static function getResponse($data)
    {
        return new Response(200, $data);
    }

    
    /**
     * Sends out the response and terminates the script.
     * 
     * @param \Response $response
     */
    public static function sendResponse(Response $response)
    {
        header('Content-Type: application/json');
        http_response_code($response->getStatus());
        die(json_encode($response->getData()));
    }
}