<?php

namespace App\Controller;

use App\Model\Timeline;
use Slim\Http\Request;
use Slim\Http\Response;
use Awurth\Slim\Helper\Controller\Controller;
use Respect\Validation\Validator as V;

class TimelineController extends Controller
{
    public function timeline(Request $request, Response $response, $id)
    {
        $timeline = Timeline::withCount('events')->find($id);
        if ($timeline === null) {
            throw $this->notFoundException($request, $response);
        }

        if (!$this->auth->check() && $timeline->private){
           return $response->withRedirect($this->path('home'));
        }

        return $this->render($response, 'app/home.twig', [
            'timeline' => $timeline
        ]);
    }


    public function timelines(Request $request, Response $response)
    {     
        if ($this->auth->check()) {
            $timelines = Timeline::withCount('events')->get();
        } else {
            $timelines = Timeline::withCount('events')->where('private', 0)->get();
        }
        return $this->render($response, 'app/timelines.twig', [
            'timelines' => $timelines
        ]);
    }

    public function delete(Request $request, Response $response, $id)
    {
        Timeline::destroy($id);
        $this->flash('success', 'Ajajoon kustutati');
        return $response->withRedirect($this->path('timelines'));
    }
    public function addTimeline(Request $request, Response $response)
    {
        // Validate input:
        $this->validator->request($request, [
            'name' => V::length(1, null),
            'description' => V::not(V::nullType())
        ]);

        if (!$this->validator->isValid()) {
            if ($this->validator->getFirstError('name')) {
                $this->flash('danger', 'Kontrolli pealkirja');
            }
            else if ($this->validator->getFirstError('description')) {
                $this->flash('danger', 'Kontrolli kirjeldust');
            }

            return $response->withRedirect($this->path('timelines'));
        }

        $timeline = new Timeline();
        $timeline->user()->associate($this->auth->getUser());
        $timeline->name = $request->getParam('name');
        $timeline->description = $request->getParam('description');
        $timeline->private = ($request->getParam('private') ? true : false);
        $timeline->save();

        $this->flash('success', 'Ajajoon loodud');

        return $response->withRedirect($this->path('timelines'));
    }

    public function searchtimeline(Request $request, Response $response, $args) {
        $data = Timeline::Where('name', 'like' ,'%' . $args . '%')
            ->orWhere('description', 'like' , '%' . $args . '%')
            ->get();

        return $this->render($response, 'app/timelines.twig', [
            'timelines' => $data
        ]);
    }
    public function editTimeline(Request $request, Response $response){
        $timeline = Timeline::find($request->getParam('id'));
        $timeline->name = $request->getParam('name');
        $timeline->description = $request->getParam('description');
        $timeline->private = $request->getParam('private');
        $timeline->save();
        $this->flash('success', 'Ajajoon muudetud edukalt');
        return $response->withRedirect($this->path('timelines'));
    }

    public function defaultTimeline(Request $request, Response $response, $id){
        Timeline::where('default', 1)->update(['default' => 0]);
        $timeline = Timeline::find($id);
        $timeline->default = true;
        $timeline->save();

        return $response->withJson(['message' => 'Set timeline ' . $id . ' as default']);
    }
}
