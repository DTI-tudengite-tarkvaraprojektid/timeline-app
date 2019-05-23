<?php

$app->get('/', 'controller.app:home')->setName('home');

$app->group('', function () {
    $this->map(['GET', 'POST'], '/login', 'controller.auth:login')->setName('login');
})->add($container['middleware.guest']);

$app->group('/events', function () {
    $this->map(['GET'], '/', 'controller.event:events')->setName('get-events');
    $this->map(['DELETE'], '/{id:[0-9]+}', 'controller.event:delete')->setName('delete-event');
})->add($container['middleware.guest']);

$app->get('/logout', 'controller.auth:logout')
    ->add($container['middleware.auth']())
    ->setName('logout');

$app->group('/user', function () {
    $this->map(['GET', 'POST'], '/register', 'controller.user:register')->setName('register');
    $this->map(['DELETE'], '/{id:[0-9]+}', 'controller.user:delete')->setName('delete-user');
})->add($container['middleware.guest']);