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
        $event->title = $request->getParam('title');
        $event->time = $request->getParam('time');
        $event->private = ($request->getParam('private') ? true : false);

        $timeline->events()->save($event);
        $this->flash('success', 'Sündmus lisatud edukalt');

        return $response->withRedirect($this->path('timeline', [
            'id' => $timeline->id
        ]));
    }

    public function events(Request $request, Response $response, $id, $query = null)
    {

        $timeline = Timeline::with('events')->findOrFail($id);
        $events = $timeline->events();

        if ($query != null) {
            $events->search($query);
        }

        if(!$this->auth->check()){
            if ($timeline->private) {
                return $response->withJson([]);
            }
            $events = $events->where('private', 0);
        }
        
        $events = $events->orderBy('time')->get()->toArray();

        for ($i=0; $i < count($events); $i++) {
            $events[$i]['path_get_content'] = $this->path('get-content', ['id' => $events[$i]['id']]);
            $events[$i]['path_save_content'] = $this->path('save-content', ['id' => $events[$i]['id']]);
            $events[$i]['path_save_image'] = $this->path('save-image', ['id' => $events[$i]['id']]);
            $events[$i]['path_delete'] = $this->path('delete-event', ['id' => $events[$i]['id']]);
        }
        return $response->withJson($events);
    }

    public function delete(Request $request, Response $response, $id)
    {
        $timeline = Event::find($id)->timeline;
        Event::destroy($id);
        $this->flash('success', 'Sündmus kustutatud');
        return $response->withRedirect($this->path('timeline', [
            'id' => $timeline->id
        ]));
    }

    public function editEvent(Request $request, Response $response){
        $event = Event::find($request->getParam('id'));
        $event->title = $request->getParam('title');
        $event->time = $request->getParam('time');
        $event->private = $request->getParam('private');
        $event->save();
        $this->flash('success', 'Sündmus muudetud edukalt');
        return $response->withRedirect($this->path('timeline', [
            'id' => $event->timeline->id
        ]));
    }
}
