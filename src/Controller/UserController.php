<?php

namespace App\Controller;

use App\Model\User;
use Slim\Http\Request;
use Slim\Http\Response;
use Awurth\Slim\Helper\Controller\Controller;
use Respect\Validation\Validator as V;

class UserController extends Controller 
{
    public function register(Request $request, Response $response, $id)
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

    public function showUsers(Request $request, Response $response)
    {

        $users = User::all();
        return $this->render($response, 'app/userlist.twig', [
            'users' => $users
        ]);
    }

    public function users(Request $request, Response $response)
    {
        return $this->render($response, 'app/userlist.twig');
    }

    public function registration(Request $request, Response $response)
    {
        return $this->render($response, 'app/register.twig');
    }

    public function delete2(Request $request, Response $response, $id)
    {
        User::destroy($id);
        $this->flash('success', 'Ajajoon kustutati');
        return $response->withRedirect($this->path('users'));
    }
    public function settings(Request $request, Response $response)
    {
        return $this->render($response, 'app/settings.twig');
    }
    public function addUser(Request $request, Response $response)
    {
        // Validate input:
        $this->validator->request($request, [
            'email' => V::length(1, null),
            'password' => V::length(1, null)
        ]);

        if (!$this->validator->isValid()) {
            if ($this->validator->getFirstError('email')) {
                $this->flash('danger', 'Kontrolli Emaili');
            }
            else if ($this->validator->getFirstError('password')) {
                $this->flash('danger', 'Kontrolli salasõna');
            }

            return $response->withRedirect($this->path('userlist'));
        }

        $role = $this->auth->findRoleByName('User');

        $user = $this->auth->registerAndActivate([
            'firstname' => $request->getParam('firstname'),
            'lastname' => $request->getParam('lastname'),
            'email' => $request->getParam('email'),
            'password' => $request->getParam('password'),
            'permissions' => [
                'user.delete' => 0
            ]
        ]);

        $role->users()->attach($user);

        $this->flash('success', 'Toimetaja konto loodud');

        return $response->withRedirect($this->path('userlist'));
    }
}
