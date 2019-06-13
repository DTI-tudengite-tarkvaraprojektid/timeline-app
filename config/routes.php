<?php

$app->get('/', 'controller.app:home')->setName('home');

$app->get('/timeline/{id:[0-9]+}', 'controller.timeline:timeline')->setName('timeline');

$app->group('/settings', function () {
    $this->get('/[{id}]', 'controller.settings:settings')->setName('settings');
    $this->post('/[{id}]', 'controller.settings:submit')->setName('submit');

    $this->get('/{id}/cPassword', 'controller.settings:cPassword')->setName('cPassword');
    $this->post('/{id}/cPassword', 'controller.settings:submit2')->setName('submit2');
})->add($container['middleware.auth']());

$app->group('', function () {
    $this->map(['GET', 'POST'], '/login', 'controller.auth:login')->setName('login');
})->add($container['middleware.guest']);


$app->group('/events', function () use ($container) {
    $this->get('/{id}[/{query}]', 'controller.event:events')->setName('get-events');
    $this->get('/{id:[0-9]+}/delete', 'controller.event:delete')->setName('delete-event')->add($container['middleware.auth']());
    $this->post('/', 'controller.event:addEvent')->setName('add-event')->add($container['middleware.auth']());
    $this->post('/edit', 'controller.event:editEvent')->setName('edit-event')->add($container['middleware.auth']());
});

$app->get('/logout', 'controller.auth:logout')
    ->add($container['middleware.auth']())
    ->setName('logout');

$app->group('/event/{id:[0-9]+}/content', function () {
    $this->get('/', 'controller.content:get')->setName('get-content');
    $this->post('/', 'controller.content:save')->setName('save-content');
    $this->get('/image/{image:[0-9]+}', 'controller.content:getImage')->setName('get-image');
    $this->get('/thumb/{image:[0-9]+}', 'controller.content:getThumb')->setName('get-thumb');
    $this->post('/image', 'controller.content:uploadImage')->setName('save-image');
});

$app->group('/user', function () {
    $this->map(['GET', 'POST'], '/register', 'controller.user:register')->setName('register');
    $this->map(['DELETE'], '/{id:[0-9]+}', 'controller.user:delete')->setName('delete-user');
})->add($container['middleware.guest']);

$app->group('/timelines', function () use ($container) {
    $this->map(['GET'], '/', 'controller.timeline:timelines')->setName('timelines');
    $this->map(['GET'], '/{id:[0-9]+}/delete', 'controller.timeline:delete')->setName('delete-timeline')->add($container['middleware.auth']());
    $this->map(['POST'], '/', 'controller.timeline:addTimeline')->setName('add-timeline')->add($container['middleware.auth']());
    $this->get('/search/[{query:.*}]', 'controller.timeline:searchtimeline')->setName('search-timelines');
    $this->map(['POST'], '/edit', 'controller.timeline:editTimeline')->setName('edit-timeline')->add($container['middleware.auth']());
    $this->map(['POST'], '/{id:[0-9]+}/default', 'controller.timeline:defaultTimeline')->setName('default-timeline')->add($container['middleware.auth']());
});

$app->group('/users', function () {
    $this->map(['GET'], '/', 'controller.user:showUsers')->setName('userlist')->add($container['middleware.auth']('admin'));
    $this->post('/', 'controller.user:users')->setName('users')->add($container['middleware.auth']());
   // $this->post(['/'], '/{id:[0-9]+}/delete', 'controller.user:delete2')->setName('delete-user2')->add($container['middleware.auth']());
    $this->get('/settings', 'controller.user:settings')->setName('settings')->add($container['middleware.auth']());
    $this->get('/register', 'controller.user:registration')->setName('register')->add($container['middleware.auth']('admin'));
});

$app->group('/gallery', function (){
  $this->get('/', 'controller.gallery:gallery')->setName('gallery');
});
