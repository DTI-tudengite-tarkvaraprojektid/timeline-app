<?php

namespace App\Controller;

use App\Model\User;
use Slim\Http\Request;
use Slim\Http\Response;
use Awurth\Slim\Helper\Controller\Controller;

class UserController extends Controller 
{
    public function register(Request $request, Response $response)
    {
        if ($request->isPost()) {
            $username = $request->getParam('username');
            $email = $request->getParam('email');
            $password = $request->getParam('password');

            $this->validator->request($request, [
                'username' => V::length(3, 25)->alnum('_')->noWhitespace(),
                'email' => V::noWhitespace()->email(),
                'password' => [
                    'rules' => V::noWhitespace()->length(6, 25),
                    'messages' => [
                        'length' => 'The password length must be between {{minValue}} and {{maxValue}} characters'
                    ]
                ],
                'password_confirm' => [
                    'rules' => V::equals($password),
                    'messages' => [
                        'equals' => 'Passwords don\'t match'
                    ]
                ]
            ]);

            if ($this->auth->findByCredentials(['login' => $username])) {
                $this->validator->addError('username', 'This username is already used.');
            }

            if ($this->auth->findByCredentials(['login' => $email])) {
                $this->validator->addError('email', 'This email is already used.');
            }

            if ($this->validator->isValid()) {
                /** @var \Cartalyst\Sentinel\Roles\EloquentRole $role */
                $role = $this->auth->findRoleByName('User');

                $user = $this->auth->registerAndActivate([
                    'username' => $username,
                    'email' => $email,
                    'password' => $password,
                    'permissions' => [
                        'user.delete' => 0
                    ]
                ]);

                $role->users()->attach($user);

                $this->flash('success', 'Your account has been created.');

                return $this->redirect($response, 'login');
            }
        }

        return $this->render($response, 'auth/register.twig');
    }

    public function delete(Request $request, Response $response, $args)
    {
        User::destroy($args['id']);
        return $response->withJson(['message' => 'User deleted!']);
    }
}
