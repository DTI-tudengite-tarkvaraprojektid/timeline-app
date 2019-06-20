<?php

$app->get('/', 'controller.timeline:timelines')->setName('home');

$app->group('/timeline' , function() use ($container) {
    $this->get('/{id:[0-9]+}', 'controller.timeline:timeline')->setName('timeline');
    $this->get('/{id:[0-9]+}/embeddable' , 'controller.timeline:embeddedTimeline')->setName('embeddable');
    $this->get('/share', 'controller.timeline:shareTimeline')->setName('share');
    $this->get('/{id:[0-9]+}/export', 'controller.event:exportEvents')->setName('exportEvents');
    $this->get('/{id:[0-9]+}/delete', 'controller.timeline:delete')->setName('delete-timeline')->add($container['middleware.auth']());
});

$app->group('', function () {
    $this->map(['GET', 'POST'], '/login', 'controller.auth:login')->setName('login');
})->add($container['middleware.guest']);


$app->group('/events', function () use ($container) {
    $this->get('/search/{id:[0-9]+}[/{query}]', 'controller.event:events')->setName('get-events');
    $this->get('/delete/{id:[0-9]+}', 'controller.event:delete')->setName('delete-event')->add($container['middleware.auth']());
    $this->post('/', 'controller.event:addEvent')->setName('add-event')->add($container['middleware.auth']());
    $this->post('/edit', 'controller.event:editEvent')->setName('edit-event')->add($container['middleware.auth']());
    $this->get('/{page:[0-9]+}', 'controller.event:showEvents')->setName('showEvents');
});

$app->get('/logout', 'controller.auth:logout')
    ->add($container['middleware.auth']())
    ->setName('logout');

$app->group('/event/{id:[0-9]+}/content', function () use ($container) {
    $this->get('/', 'controller.content:get')->setName('get-content');
    $this->get('/html', 'controller.content:getHtml')->setName('get-content-html');
    $this->post('/', 'controller.content:save')->setName('save-content');
    $this->get('/image/{image:[0-9]+}', 'controller.content:getImage')->setName('get-image');
    $this->get('/thumb/{image:[0-9]+}', 'controller.content:getThumb')->setName('get-thumb');
    $this->post('/image', 'controller.content:uploadImage')->setName('save-image');
    $this->post('/file', 'controller.content:uploadFile')->setName('save-file');
});

$app->group('/user', function () use ($container) {
    $this->post('/add', 'controller.user:addUser')->setName('add-user')->add($container['middleware.auth']('admin'));
    $this->post('/edit', 'controller.user:editUser')->setName('edit-user')->add($container['middleware.auth']());
    $this->map(['GET', 'POST'], '/profile', 'controller.user:profile')->setName('profile')->add($container['middleware.auth']());
    $this->map(['GET', 'POST'], '/{id}/change-password', 'controller.user:password')->setName('change-password');
    $this->get('/{id:[0-9]+}/delete', 'controller.user:delete')->setName('delete-user')->add($container['middleware.auth']('admin'));
});

$app->get('/users', 'controller.user:users')->setName('users')->add($container['middleware.auth']('admin'));

$app->group('/timelines', function () use ($container) {
    $this->get('/[{query:.*}]', 'controller.timeline:timelines')->setName('timelines');
    //$this->get('/{id:[0-9]+}/embeddable', 'controller.timeline:embeddedTimeline')->setName('embeddable')->add($container['middleware.auth']());
    $this->post('/', 'controller.timeline:addTimeline')->setName('add-timeline')->add($container['middleware.auth']());
    $this->post('/edit', 'controller.timeline:editTimeline')->setName('edit-timeline')->add($container['middleware.auth']());
});

$app->group('/gallery', function (){
  $this->get('/{page:[0-9]+}', 'controller.gallery:gallery')->setName('gallery');
});

$app->group('/filelist', function (){
  $this->get('/{page:[0-9]+}/[{query:.*}]', 'controller.files:files')->setName('files');
  $this->get('/file/{file:[0-9]+}/', 'controller.files:getFile')->setName('get-file');
  $this->get('/filename/{file:[0-9]+}', 'controller.files:getfilename')->setName('get-filename');
});
