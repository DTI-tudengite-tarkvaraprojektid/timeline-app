<?php

namespace App\Controller;

use App\Model\Event;
use Slim\Http\Request;
use Slim\Http\Response;
use Awurth\Slim\Helper\Controller\Controller;
use App\Model\Timeline;
use Respect\Validation\Validator as V;

class EventController extends Controller
{

    public function addEvent(Request $request, Response $response)
    {
        $timelineId = $request->getParam('timeline_id');
        $timeline = Timeline::find($timelineId);
        
        if (!$timeline) {
            throw $this->notFoundException($request, $response);
        }

        // Validate input:
        $this->validator->request($request, [
            'title' => V::length(1, null),
            'time' => V::date('Y-m-d')
        ]);

        if (!$this->validator->isValid()) {
            if ($this->validator->getFirstError('title')) {
                $this->flash('danger', 'Kontrolli pealkirja');
            }
            else if ($this->validator->getFirstError('time')) {
                $this->flash('danger', 'Kontrolli kuupäeva');
            }
            
            return $response->withRedirect($this->path('timeline', [
                'id' => $timeline->id
            ]));
        }

        $event = new Event(); 
        $event->user()->associate($this->auth->getUser());
        $event->title= $request->getParam('title');
        $event->time = $request->getParam('time');
        
        $timeline->events()->save($event);
        $this->flash('success', 'Sündmus lisatud edukalt');

        return $response->withRedirect($this->path('timeline', [
            'id' => $timeline->id
        ]));
    }

    public function events(Request $request, Response $response, $id = null)
    {
        // var_dump($args);
        if ($id) {
            $timeline = Timeline::with('events')->findOrFail($id);
            return $response->withJson($timeline->events()->orderBy('time')->get());
        } else {
            return $response->withJson(Event::orderBy('time')->get());
        }
    }

    public function delete(Request $request, Response $response, $args)
    {
        Event::destroy($args['id']);
        return $response->withJson(['message' => 'Event deleted!']);
    }
}
