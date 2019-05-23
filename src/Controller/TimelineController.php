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
  public function delete(Request $request, Response $response, $args)
  {
      Timeline::destroy($args['id']);
      return $response->withJson(['message' => 'Timeline deleted!']);
  }
}
