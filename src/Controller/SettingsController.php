<?php
namespace App\Controller;

use Awurth\Slim\Helper\Controller\Controller;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use Respect\Validation\Validator as V;
use Slim\Http\Request;
use Slim\Http\Response;


class SettingsController extends Controller
{
    public function settings(Request $request, Response $response)
    {
        return $this->render($response, 'app/settings.twig');
    }
    public function submit(Request $request, Response $response)
    {
        $user = $this->auth->getUser();
        $user->email = $request->getParam('email');     
        $user->password = $request->getParam('password'); 
        $user->firstname = $request->getParam('firstname');  
        $user->lastname = $request->getParam('lastname');  
        $user->save();
        return $response->withRedirect($this->path('settings', [
            'id' => $user->id
            ]));

    }
}