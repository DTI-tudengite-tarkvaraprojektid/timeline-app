<?php

namespace App\Controller;

use App\Model\Event;
use Slim\Http\Request;
use Slim\Http\Response;
use Awurth\Slim\Helper\Controller\Controller;
use App\Model\Timeline;

class EventController extends Controller
{
    public function addEvent(Request $request, Response $response)
    {
        $timelineId = $request->getParam('timeline_id');
        $timeline = Timeline::find($timelineId);
        // TODO: Send error if timeline was not found.

        $event = new Event(); 
        $event->user()->associate($this->auth->getUser());
        $event->title= $request->getParam('title');
        $event->time = $request->getParam('time');
        
        $timeline->events()->save($event);

        return $response->withJson(['message' => 'Event Created!']);
    }

    public function events(Request $request, Response $response, $args = null)
    {
        // var_dump($args);
        if ($args) {
            $timeline = Timeline::with('events')->findOrFail($args);
            return $response->withJson($timeline->events);
        } else {
            return $response->withJson(Event::all());
        }
    }

    public function delete(Request $request, Response $response, $args)
    {
        Event::destroy($args['id']);
        return $response->withJson(['message' => 'Event deleted!']);
    }
}
