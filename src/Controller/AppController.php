<?php

namespace App\Controller;

use Slim\Http\Request;
use App\Model\Timeline;
use Slim\Http\Response;
use Awurth\Slim\Helper\Controller\Controller;

class AppController extends Controller
{
    public function home(Request $request, Response $response)
    {
        // TODO: Make default timeline configurable
        $timeline = Timeline::withCount('events')->where('default', 1)->first();
        if ($timeline == null) {
            $timeline = Timeline::withCount('events')->first();
        }
        if ($timeline == null) {
            return $response->withRedirect($this->path('timelines'));
        }
        return $this->render($response, 'app/home.twig', [
            'timeline' => $timeline
        ]);

    }
}
