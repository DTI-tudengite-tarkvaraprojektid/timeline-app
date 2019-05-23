<?php

namespace App\Controller;

use Awurth\Slim\Helper\Controller\Controller;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use Respect\Validation\Validator as V;
use Slim\Http\Request;
use Slim\Http\Response;


/**
 * @property \Awurth\SlimValidation\Validator validator
 * @property \Cartalyst\Sentinel\Sentinel     auth
 */
class ContentController extends Controller
{
    public function addText(Request $request, Response $response)
    {
      $something=  $_POST['post_body'];
      $sql = "INSERT contents (event_id, type, content)";
        

    }


}
