<?php

namespace App\Controller;

use App\Model\User;
use Slim\Http\Request;
use Slim\Http\Response;
use Respect\Validation\Validator as V;
use Awurth\Slim\Helper\Controller\Controller;
use Awurth\Slim\Helper\Exception\AccessDeniedException;

class UserController extends Controller
{

    public function users(Request $request, Response $response)
    {
        $users = User::all();
        return $this->render($response, 'app/userlist.twig', [
            'users' => $users,
        ]);
    }

    public function profile(Request $request, Response $response)
    {
        if ($request->isPost()) {

            // Email Validation
            $this->validator->request($request, [
                'email' => [
                    'rules' => V::length(1, null)->email(),
                    'message' => 'Kontrolli emaili'
                ]
            ]);
    
            if ($this->auth->getUser()->email !== $request->getParam('email') && $this->auth->findByCredentials(['login' => $request->getParam('email')])) {
                $this->validator->addError('email', 'Sisestatud email on juba kasutusel');
            }
            
            if (!$this->validator->isValid()) {
                foreach ($this->validator->getErrors() as $key => $value) {
                    if (\is_array($value)) {
                        foreach ($value as $message) {
                            $this->flash('danger', $message);
                        }
                    } else {
                        $this->flash('danger', $value);
                    }
                }
                return $response->withRedirect($this->path('profile'));
            }

            $array = [
                'email' => $request->getParam('email'),
                'firstname' => $request->getParam('firstname'),
                'lastname' => $request->getParam('lastname'),
            ];
            $this->auth->update($this->auth->getUser(), $array);
            $this->flash('success', 'Kasutajakonto muudetud edukalt');
            return $response->withRedirect($this->path('profile'));
        }

        return $this->render($response, 'app/profile.twig', [
            'user' => $this->auth->getUser(),
        ]);
    }

    public function password(Request $request, Response $response, $id)
    {
        $user = $this->auth->getUser();
        if ($id != $user->id) {
            if ($this->auth->getUser()->inRole('admin')) {
                $user = $this->auth->findUserById($id);
            } else {
                throw new AccessDeniedException($request, $response);
            }
        }

        if ($request->isPost()) {
            $password1 = $request->getParam('password');
            $password2 = $request->getParam('newpassword');

            // Validation
            $this->validator->request($request, [
                'password' => [
                    'rules' => V::length(6, null),
                    'message' => 'Parool peab olema vähemalt 6 tähemärki pikk'
                ],
                'newpassword' => [
                    'rules' => V::equals($password1),
                    'message' => 'Paroolid ei kattu'
                ],
            ]);
            
            if (!$this->validator->isValid()) {
                foreach ($this->validator->getErrors() as $key => $value) {
                    if (\is_array($value)) {
                        foreach ($value as $message) {
                            $this->flash('danger', $message);
                        }
                    } else {
                        $this->flash('danger', $value);
                    }
                }
                return $response->withRedirect($this->path('change-password', ['id' => $id]));
            }

            $user = $this->auth->update($user, ['password' => $password1]);
            $this->flash('success', 'Parool muudetud edukalt!');

            return $response->withRedirect($this->path('change-password', ['id' => $id]));
        }

        return $this->render($response, 'app/password.twig', [
            'user' => $user,
        ]);
    }

    public function addUser(Request $request, Response $response)
    {
        // Validation
        $this->validator->request($request, [
            'email' => [
                'rules' => V::length(1, null)->email(),
                'message' => 'Kontrolli emaili'
            ],
            'password' => [
                'rules' => V::length(6, null),
                'message' => 'Parool peab olema vähemalt 6 tähemärki pikk'
            ],
        ]);

        if ($this->auth->findByCredentials(['login' => $request->getParam('email')])) {
            $this->validator->addError('email', 'Sisestatud email on juba kasutusel');
        }
        
        if (!$this->validator->isValid()) {
            $messages = [];
            foreach ($this->validator->getErrors() as $key => $value) {
                if (\is_array($value)) {
                    foreach ($value as $message) {
                        $messages[] = $message;
                    }
                } else {
                    $messages[] = $value;
                }
            }
            return $response->withStatus(400)->withJson([
                'messages' => $messages
            ]);
        }

        $role = $this->auth->findRoleByName('User');

        $user = $this->auth->registerAndActivate([
            'firstname' => $request->getParam('firstname'),
            'lastname' => $request->getParam('lastname'),
            'email' => $request->getParam('email'),
            'password' => $request->getParam('password'),
        ]);
        if ($request->getParam('admin')) {
            $role = $this->auth->findRoleByName('admin');
            $this->flash('success', 'Administraatori konto loodud');
        } else {
            $role = $this->auth->findRoleByName('user');
            $this->flash('success', 'Toimetaja konto loodud');
        }

        $role->users()->attach($user);

        return $response->withJSON([
            'messages' => 'User created'
        ]);
    }

    public function editUser(Request $request, Response $response)
    {

        $user = $this->auth->findUserById($request->getParam('id'));
        if ($user == null) {
            $this->flash('danger', 'Ei leidnud valitud kasutajat');
            return $response->withRedirect($this->path('users'));
        }

        if (!$this->auth->getUser()->inRole('admin')) {
            throw new AccessDeniedException($request, $response);
        }

        // Email Validation
        $this->validator->request($request, [
            'email' => [
                'rules' => V::length(1, null)->email(),
                'message' => 'Kontrolli emaili'
            ]
        ]);

        if ($user->email !== $request->getParam('email') && $this->auth->findByCredentials(['login' => $request->getParam('email')])) {
            $this->validator->addError('email', 'Sisestatud email on juba kasutusel');
        }
        
        if (!$this->validator->isValid()) {
            $messages = [];
            foreach ($this->validator->getErrors() as $key => $value) {
                if (\is_array($value)) {
                    foreach ($value as $message) {
                        $messages[] = $message;
                    }
                } else {
                    $messages[] = $value;
                }
            }
            return $response->withStatus(400)->withJson([
                'messages' => $messages
            ]);
        }

        if ($request->getParam('admin') === null) {
            $role = $this->auth->findRoleByName('user');
        } else {
            $role = $this->auth->findRoleByName('admin');
        }

        $array = [
            'email' => $request->getParam('email'),
            'firstname' => $request->getParam('firstname'),
            'lastname' => $request->getParam('lastname'),
        ];
        $this->auth->update($user, $array);
        $user->roles()->detach();
        $role->users()->attach($user);
        $this->flash('success', 'Kasutajakonto muudetud edukalt');
        return $response->withJSON([
            'messages' => 'User changed successfully'
        ]);
    }

    public function delete(Request $request, Response $response, $id)
    {
        if ($this->auth->getUser()->id == $id) {
            $this->flash('danger', 'Enda kasutajat ei saa kustutada');
        } else {
            User::destroy($id);
            $this->flash('success', 'Kasutaja kustutatud');
        }
        return $response->withRedirect($this->path('users'));
    }
}
