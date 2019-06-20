<?php

use Monolog\Logger;
use App\Model\Event;
use Slim\Csrf\Guard;
use Slim\Views\Twig;
use App\Model\Content;
use nadar\quill\Lexer;
use Slim\Flash\Messages;
use Slim\Views\TwigExtension;
use Monolog\Handler\StreamHandler;
use Twig\Extension\DebugExtension;
use Monolog\Processor\UidProcessor;
use Awurth\SlimValidation\Validator;
use Illuminate\Database\Capsule\Manager;
use Awurth\Slim\Helper\Twig\CsrfExtension;
use Awurth\Slim\Helper\Twig\AssetExtension;
use Awurth\SlimValidation\ValidatorExtension;
use Cartalyst\Sentinel\Native\Facades\Sentinel;
use Cartalyst\Sentinel\Native\SentinelBootstrapper;

$container['laravel-container'] = function ($c){
    return new \Illuminate\Container\Container();
};

$container['database-event-dispatcher'] = function ($c) {
    return new \Illuminate\Events\Dispatcher($c['laravel-container']);
};

$capsule = new Manager();
$capsule->addConnection($container['settings']['eloquent']);
$capsule->setEventDispatcher($container['database-event-dispatcher']);
$capsule->setAsGlobal();
$capsule->bootEloquent();

$container['auth'] = function ($container) {
    $sentinel = new Sentinel(new SentinelBootstrapper($container['settings']['sentinel']));
    return $sentinel->getSentinel();
};

$container['flash'] = function () {
    return new Messages();
};

$container['csrf'] = function ($container) {
    $guard = new Guard();
    $guard->setFailureCallable($container['csrfFailureHandler']);

    return $guard;
};

// https://github.com/awurth/SlimValidation
$container['validator'] = function () {
    return new Validator();
};

$container['twig'] = function ($container) {
    $config = $container['settings']['twig'];

    $twig = new Twig($config['path'], $config['options']);

    $twig->addExtension(new TwigExtension($container['router'], $container['request']->getUri()));
    $twig->addExtension(new DebugExtension());
    $twig->addExtension(new AssetExtension($container['request']));
    $twig->addExtension(new CsrfExtension($container['csrf']));
    $twig->addExtension(new ValidatorExtension($container['validator']));

    $twig->getEnvironment()->addGlobal('flash', $container['flash']);
    $twig->getEnvironment()->addGlobal('auth', $container['auth']);

    return $twig;
};

$container['monolog'] = function ($container) {
    $config = $container['settings']['monolog'];

    $logger = new Logger($config['name']);
    $logger->pushProcessor(new UidProcessor());
    $logger->pushHandler(new StreamHandler($config['path'], $config['level']));

    return $logger;
};

Content::deleting(function ($content) use ($container) {
    $monolog = $container['monolog'];
    $settings = $container['settings'];

    $monolog->debug('Deleting content event');
    if ($content->type == 'IMAGE') {
        $monolog->debug('Deleting image content: ' . $content->content);
        $monolog->debug('thumbnail_path: ' . ($settings['thumbnail_path'] . '/' . $content->content));
        $monolog->debug('upload_path: ' . ($settings['upload_path'] . '/' . $content->content));
        unlink($settings['thumbnail_path'] . '/' . $content->content);
        unlink($settings['upload_path'] . '/' . $content->content);
    } else if ($content->type == 'FILE') {
        $monolog->debug('Deleting file content: ' . $content->content);
        $file = json_decode($content->content);
        $monolog->debug('file_upload_path: ' . ($settings['file_upload_path'] . '/' . $file->path));
        unlink($settings['file_upload_path'] . '/' . $file->path);
    }
});