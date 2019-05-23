<?php

namespace App\Controller;

use App\Model\Timeline;
use Slim\Http\Request;
use Slim\Http\Response;
use Awurth\Slim\Helper\Controller\Controller;

class TimelineController extends Controller{

  public function timelines(Request $request, Response $response)
  {
      
      return $response->withJson(Timeline::all());
  }

}
