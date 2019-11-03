<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use pats\Helpers\RouteHelper;

require __DIR__ . '/../vendor/autoload.php';

class Router
{
    public function __construct() 
    {
        $app = AppFactory::create();
        $this->route_helper = new RouteHelper();
        
        $this->defaultRoutes($app);
        $this->apiRoutes($app);
        $app->run();
    }

    private function defaultRoutes($app) 
    {
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
    }

    private function apiRoutes($app)
    {
        // API endpoints
        $app->group('/api', function (RouteCollectorProxy $api) {
            // /api/beacons
            $api->group('/beacons', function (RouteCollectorProxy $beacons) {
                $beacons->get('', function (Request $request, Response $response, $args) {
                    $get = $this->route_helper->get($request);
                    return $this->route_helper->response($response, $get, 200);
                });
            });
        });
    }
}

$app = new Router();