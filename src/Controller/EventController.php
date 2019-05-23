<?php

namespace App\Controller;

use App\Model\Event;
use Slim\Http\Request;
use Slim\Http\Response;
use Awurth\Slim\Helper\Controller\Controller;

class EventController extends Controller
{
    public function addEvent(Request $request, Response $response)
    {
        
        $event = new Event(); 
        $event->user_id = $this->auth->getUser();
        $event->timeline_id = $request->getParam('timeline_id');
        $event->title= $request->getParam('title');
        $event->time = $request->getParam('time');
        $event->save();
        return $response->withJson(['message' => 'Event Created!']);
    }

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
