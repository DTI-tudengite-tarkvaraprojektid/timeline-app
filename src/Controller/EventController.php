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
            $events[$i]['path_save_file'] = $this->path('save-file', ['id' => $events[$i]['id']]);
            $events[$i]['path_save_image'] = $this->path('save-image', ['id' => $events[$i]['id']]);
            $events[$i]['path_delete'] = $this->path('delete-event', ['id' => $events[$i]['id']]);
        }
        return $response->withJson($events);
    }

    public function delete(Request $request, Response $response, $id)
    {
        $event = Event::find($id);
        $timelineId = $event->timeline->id;

        $query = $event->content();
        foreach ($event->content as $row) {
            $this->deleteContentFile($row);
        }

        $event->content()->delete();

        Event::destroy($id);

        $this->flash('success', 'Sündmus kustutatud');
        return $response->withRedirect($this->path('timeline', [
            'id' => $timelineId
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

    public function showEvents(Request $request, Response $response, $id =null)
    {
        if(!$this->auth->check()){
            $events = Event::where('private', 0)->get();
        } else {
            $events = Event::all();
        }
        return $this->render($response, 'app/events.twig', [
            'events' => $events
        ]);
    }

    protected function deleteContentFile($content) {
        if ($content->type == 'IMAGE') {
            unlink($this->settings['thumbnail_path'] . '/' . $content->content);
            unlink($this->settings['upload_path'] . '/' . $content->content);
        } else if ($content->type == 'FILE') {
            $file = json_decode($content->content);
            unlink($this->settings['file_upload_path'] . '/' . $file->path);
        }
    }

    public function exportEvents(Request $request, Response $response, $id)
    {
        $timeline = Timeline::with('events')->findOrFail($id);
        $events = $timeline->events;
        /*$output ="Subject,Start Date,Start Time,End Date,End Time,All Day Event,Description,Location,Private,\n";
        for ($i=0; $i < count($events); $i++) {
            $event = $events[$i];
            $output .= '"' . addslashes($event->title) . '",';
            $output .= date('d/m/Y', strtotime($event->time));
            $output .= ",,,,,";
            if($event->private==1){
                $output .= "True,";
            } else {
                $output .= "0,";
            }     
            $output .= "\n";
        */

        $output="BEGIN:VCALENDAR\n";
        $output.="VERSION:2.0\n";
        $output.="PRODID:TLU TIMELINE APP\n";
        for ($i=0; $i < count($events); $i++) {
            $event = $events[$i];
            $output .="BEGIN:VEVENT\n";
            $output .="DTSTART;VALUE=DATE:";
            $output .=date('Y' ,strtotime($event->time));
            $output .=date('m' ,strtotime($event->time));
            $output .=date('d' ,strtotime($event->time));
            $output .="\n";
            $output .="SUMMARY:";
            $output .= $event->title;
            $output .="\n";
            $output .="CLASS:";
            if($event->private==1){
                $output .= "PRIVATE";
            } else {
                $output .= "PUBLIC";
            }   
            $output .="\n";  
            $output .="END:VEVENT\n";
        }

        $output.="END:VCALENDAR";

        return $response
            ->withHeader('Content-Type', 'text/csv')
            ->withHeader('Content-Disposition', 'attachment; filename="tlu-timeline-events.ics"')
            ->write($output);       
    }
}
