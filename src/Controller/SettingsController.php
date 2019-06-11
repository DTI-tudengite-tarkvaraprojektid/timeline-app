<?php
namespace App\Controller;

use Awurth\Slim\Helper\Controller\Controller;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use Respect\Validation\Validator as V;
use Slim\Http\Request;
use Slim\Http\Response;


class SettingsController extends Controller
{
    public function settings(Request $request, Response $response, $id = null)
    {
        return $this->render($response, 'app/settings.twig');
    }
    public function submit(Request $request, Response $response, $id = null)
    {
       
        $user = $this->auth->getUser();
        $user->email = $request->getParam('email');   
        $user->firstname = $request->getParam('firstname'); 
        $user->lastname = $request->getParam('lastname');  
        $user->password = $request->getParam('password');
        $array = ['password' =>'$password'];
        if ($this->auth->findById(['id' => $id]== null)) {
            $user= $this->auth->update($user, $array); 
        }
        $user= $this->auth->update($user, $array);  
        $this->flash('success', 'Kasutaja muudetud edukalt');
        return $response->withRedirect($this->path('settings'));
    }
}