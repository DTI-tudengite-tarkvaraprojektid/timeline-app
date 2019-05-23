<?php

namespace App\Controller;

use App\Model\Content;
use Slim\Http\Request;
use Slim\Http\Response;
use Respect\Validation\Validator as V;
use Awurth\Slim\Helper\Controller\Controller;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;


/**
 * @property \Awurth\SlimValidation\Validator validator
 * @property \Cartalyst\Sentinel\Sentinel     auth
 */
class ContentController extends Controller
{
    public function addText(Request $request, Response $response)
    {
        $content = new Content;
		$content->event_id = $request->getParam('event_id');
		$content->type = 'TEXT';
        $content->content = $request->getParam('content');      
        $content->save();
    }
    public function addImage(Request $request, Response $response)
    {
        $content = new Content;
		$content->event_id = $request->getParam('event_id');
		$content->type = 'IMAGE';
        $content->content = $request->getParam('content');      
        $content->save();
    }
    public function addVideo(Request $request, Response $response)
    {
        $content = new Content;
		$content->event_id = $request->getParam('event_id');
		$content->type = 'VIDEO';
        $content->content = $request->getParam('content');      
        $content->save();
    }
    public function addAudio(Request $request, Response $response)
    {
        $content = new Content;
		$content->event_id = $request->getParam('event_id');
		$content->type = 'AUDIO';
        $content->content = $request->getParam('content');      
        $content->save();
    }
    public function addUrl(Request $request, Response $response)
    {
        $content = new Content;
		$content->event_id = $request->getParam('event_id');
		$content->type = 'URL';
        $content->content = $request->getParam('content');      
        $content->save();
    }
    public function addFile(Request $request, Response $response)
    {
        $content = new Content;
		$content->event_id = $request->getParam('event_id');
		$content->type = 'FILE';
        $content->content = $request->getParam('content');      
        $content->save();
    }


}
