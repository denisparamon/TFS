<?php

use Slim\Factory\AppFactory;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Nyholm\Psr7\Factory\Psr17Factory;
use Nyholm\Psr7Server\ServerRequestCreator;
use Nyholm\Psr7Server\ServerRequestCreatorInterface;
use Slim\Views\PhpRenderer;

require __DIR__ . '/../vendor/autoload.php';

// Create AppFactory with PSR-17 implementations
$psr17Factory = new Psr17Factory();
$responseFactory = $psr17Factory;
$streamFactory = $psr17Factory;
$serverRequestCreator = new ServerRequestCreator(
    $psr17Factory,
    $psr17Factory,
    $psr17Factory,
    $psr17Factory
);

// Create App with response factory
AppFactory::setResponseFactory($responseFactory);
AppFactory::setStreamFactory($streamFactory);
$app = AppFactory::create();

// Добавляем обработчик для маршрута /about
$app->get('/about', function (Request $request, Response $response, $args) {
    $phpView = new PhpRenderer('../templates');
    return $phpView->render($response, 'about.phtml');
});

// Define the route
$app->get('/hello/{name}', function (Request $request, Response $response, $args) {
    $name = $args['name'];
    $response->getBody()->write("Hello, $name");
    return $response;
});

// Create ServerRequest from globals
$serverRequest = $serverRequestCreator->fromGlobals();

// Run app with ServerRequest
$app->run($serverRequest);
