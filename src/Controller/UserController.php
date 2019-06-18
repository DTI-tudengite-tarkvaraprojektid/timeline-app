<?php

namespace App\Controller;

use App\Model\User;
use Awurth\Slim\Helper\Controller\Controller;
use Respect\Validation\Validator as V;
use Slim\Http\Request;
use Slim\Http\Response;

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
                throw new \Exception('Not admin');
            }
        }

        if ($request->isPost()) {
            $password1 = $request->getParam('password');
            $password2 = $request->getParam('newpassword');

            if (empty($password1)) {
                $this->flash('danger', 'Parooli väljad ei tohi olla tühjad');
            } elseif ($password1 == $password2) {
                $array = [
                    'password' => $password1,
                ];
                $user = $this->auth->update($user, $array);
                $this->flash('success', 'Parool muudetud edukalt!');
            } else {
                $this->flash('danger', 'Paroolid peavad olema samad!');
            }

            return $response->withRedirect($this->path('change-password', ['id' => $id]));
        }

        return $this->render($response, 'app/password.twig', [
            'user' => $user,
        ]);
    }

    public function addUser(Request $request, Response $response)
    {
        // Validate input:
        $this->validator->request($request, [
            'email' => V::length(1, null)->email(),
            'password' => V::length(1, null),
        ]);

        if (!$this->validator->isValid()) {
            if ($this->validator->getFirstError('email')) {
                $this->flash('danger', 'Kontrolli Emaili');
            } else if ($this->validator->getFirstError('password')) {
                $this->flash('danger', 'Kontrolli salasõna');
            }

            return $response->withRedirect($this->path('users'));
        }
        if ($this->auth->findByCredentials(['login' => $request->getParam('email')])) {
            $this->validator->addError('email', 'Email already taken.');
            $this->flash('danger', 'Sisestatud email on juba kasutusel');
            return $response->withRedirect($this->path('users'));
        }

        $role = $this->auth->findRoleByName('User');

        $user = $this->auth->registerAndActivate([
            'firstname' => $request->getParam('firstname'),
            'lastname' => $request->getParam('lastname'),
            'email' => $request->getParam('email'),
            'password' => $request->getParam('password'),
            'admin' => $request->getParam('admin'),
        ]);
        if (isset($_POST['admin'])) {
            $role = $this->auth->findRoleByName('admin');
            $this->flash('success', 'Administraatori konto loodud');
        } else {
            $role = $this->auth->findRoleByName('user');
            $this->flash('success', 'Toimetaja konto loodud');
        }

        $role->users()->attach($user);

        return $response->withRedirect($this->path('users'));
    }

    public function editUser(Request $request, Response $response)
    {

        $user = $this->auth->findUserById($request->getParam('id'));
        if ($user == null) {
            $this->flash('danger', 'Ei leidnud valitud kasutajat');
            return $response->withRedirect($this->path('users'));
        }

        if (!$this->auth->getUser()->inRole('admin')) {
            throw new \Exception('Not admin');
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
        return $response->withRedirect($this->path('users'));
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
