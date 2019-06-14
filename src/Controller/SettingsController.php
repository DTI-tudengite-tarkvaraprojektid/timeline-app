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
        if ($id == null) {
            $user = $this->auth->getUser();
        }   
        else {
            if ($this->auth->getUser()->inRole('admin')){
                $user = $this->auth->findUserById($id);                 
            } else {
                throw new \Exception('Not admin');
            }
        }
        return $this->render($response, 'app/settings.twig', [
            'user' => $user
        ]);
    }
    public function cPassword(Request $request, Response $response, $id)
    {
        $user = $this->auth->getUser();
        if ($id != $user->id) {
            if ($this->auth->getUser()->inRole('admin')){
                $user = $this->auth->findUserById($id);                 
            } else {
                throw new \Exception('Not admin');
            }
        }
        return $this->render($response, 'app/cPassword.twig', [
            'user' => $user
        ]);
    }
    public function submit(Request $request, Response $response, $id=null)
    {
        if ($id == null) {
            $user = $this->auth->getUser();
        }   
        else {
            if ($this->auth->getUser()->inRole('admin')){
                $user = $this->auth->findUserById($id);                 
            } else {
                throw new \Exception('Not admin');
            }
        }
        $array = [
            'email' =>$request->getParam('email'),
            'firstname' =>$request->getParam('firstname'),
            'lastname' =>$request->getParam('lastname')
        ];
        $user= $this->auth->update($user, $array);  
        $this->flash('success', 'Kasutaja muudetud edukalt');
        return $response->withRedirect($this->path('settings', ['id' => $user->id]));
    }
    public function submit2(Request $request, Response $response, $id)
    {   
        $user = $this->auth->getUser();
        if ($id != $user->id) {
            if ($this->auth->getUser()->inRole('admin')){
                $user = $this->auth->findUserById($id);                 
            } else {
                throw new \Exception('Not admin');
            }
        }

        $user = $this->auth->getUser();
        $password1 = $request->getParam('password');
        $password2 = $request->getParam('newpassword');
        if(empty($password1)){
            $this->flash('danger', 'Parooli väljad ei tohi olla tühjad');
        }
        elseif($password1 == $password2){
            $array = ['password' =>$request->getParam('password'),
            'newpassword' =>$request->getParam('newpassword')
            ];
            $user= $this->auth->update($user, $array);  
            $this->flash('success', 'Parool muudetud edukalt!');
         }else{
            $this->flash('danger', 'Paroolid peavad olema samad!');
         }
            
    return $response->withRedirect($this->path('cPassword', ['id' => $id]));
    }
}