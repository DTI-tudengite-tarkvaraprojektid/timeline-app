<?php

namespace App\Controller;

use App\Model\Event;
use Slim\Http\Request;
use Slim\Http\Response;
use Awurth\Slim\Helper\Controller\Controller;

class EventController extends Controller
{
    public function events(Request $request, Response $response)
    {
        return $response->withJson(Event::all());
    }

    public function delete(Request $request, Response $response, $args)
    {
        Event::destroy($args['id']);
        return $response->withJson(['message' => 'Event deleted!']);
    }
}
