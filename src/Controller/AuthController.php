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
class AuthController extends Controller
{
    public function login(Request $request, Response $response)
    {
        if ($request->isPost()) {
            $credentials = [
                'email' => $request->getParam('email'),
                'password' => $request->getParam('password')
            ];

            $remember = (bool)$request->getParam('remember');

            try {
                if ($this->auth->authenticate($credentials, $remember)) {
                    $this->flash('success', 'You are now logged in.');

                    return $this->redirect($response, 'home');
                } else {
                    $this->validator->addError('auth', 'Wrong username or password');
                }
            } catch (ThrottlingException $e) {
                $this->validator->addError('auth', 'Too many attempts!');
            }
        }

        return $this->render($response, 'auth/login.twig');
    }

    public function logout(Request $request, Response $response)
    {
        $this->auth->logout();

        return $this->redirect($response, 'home');
    }


}
