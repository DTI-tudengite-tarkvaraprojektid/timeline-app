<?php

$app->get('/', 'controller.app:home')->setName('home');

$app->group('', function () {
    $this->map(['GET', 'POST'], '/login', 'controller.auth:login')->setName('login');
    $this->map(['GET', 'POST'], '/register', 'controller.auth:register')->setName('register');
})->add($container['middleware.guest']);

$app->group('/events', function () {
    $this->map(['GET'], '/', 'controller.event:events')->setName('get-events');
    $this->map(['DELETE'], '/{id:[0-9]+}', 'controller.event:delete')->setName('delete-event');
});

$app->get('/logout', 'controller.auth:logout')
    ->add($container['middleware.auth']())
    ->setName('logout');

$app->group('/timelines', function () {
    $this->map(['GET'], '/', 'controller.timeline:timelines')->setName('get-timelines');
});
