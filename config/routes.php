<?php

$app->get('/', 'controller.app:home')->setName('home');

$app->get('/timeline/{id:[0-9]+}', 'controller.timeline:timeline')->setName('timeline');

$app->group('', function () {
    $this->map(['GET', 'POST'], '/login', 'controller.auth:login')->setName('login');
})->add($container['middleware.guest']);


$app->group('/events', function () use ($container) {
    $this->get('/[{id}]', 'controller.event:events')->setName('get-events');
    $this->delete('/{id:[0-9]+}', 'controller.event:delete')->setName('delete-event')->add($container['middleware.auth']());
    $this->post('/', 'controller.event:addEvent')->setName('add-event')->add($container['middleware.auth']());
});

$app->get('/logout', 'controller.auth:logout')
    ->add($container['middleware.auth']())
    ->setName('logout');

$app->group('/content', function () {
    $this->map(['POST'], '/', 'controller.content:addText')->setName('add-text');
});
$app->group('/user', function () {
    $this->map(['GET', 'POST'], '/register', 'controller.user:register')->setName('register');
    $this->map(['DELETE'], '/{id:[0-9]+}', 'controller.user:delete')->setName('delete-user');
})->add($container['middleware.guest']);

$app->group('/timelines', function () use ($container) {
    $this->map(['GET'], '/', 'controller.timeline:timelines')->setName('timelines');
    $this->map(['DELETE'], '/{id:[0-9]+}', 'controller.timeline:delete')->setName('delete-timeline')->add($container['middleware.auth']());
    $this->map(['POST'], '/', 'controller.timeline:addTimeline')->setName('add-timeline')->add($container['middleware.auth']());
});
