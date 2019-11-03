<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Hello world!");
    return $response;
});

$app->get('/gettest', function (Request $request, Response $response, $args) {
    $allGetVars = $request->getQueryParams();
    error_log("Testing error log");
    $res = $response->getBody()->write(json_encode($allGetVars));
    $res = $response->withStatus(201);
    return $res;
});

$app->post('/posttest', function (Request $request, Response $response, $args) {
    $allPostVars = $request->getParsedBody();
    $response->getBody()->write(json_encode($allPostVars));
    return $response;
});

$app->run();