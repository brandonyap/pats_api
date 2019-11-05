<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use pats\Helpers\RouteHelper;
use pats\Controllers;

require __DIR__ . '/../vendor/autoload.php';

class Router
{
    public function __construct() 
    {
        $app = AppFactory::create();
        $app->addBodyParsingMiddleware();

        // Load route helper
        $this->route_helper = new RouteHelper();

        // Load Controllers
        $this->beacon_controller = new Controllers\BeaconController();

        // Load Routes
        $this->testRoutes($app);
        $this->apiRoutes($app);

        // Run Router
        $app->run();
    }

    private function testRoutes($app) 
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
            $this->beaconRoutes($api);
            $this->sensorRoutes($api);
        });
    }

    private function beaconRoutes($api)
    {
        // api/beacons
        $api->group('/beacons', function (RouteCollectorProxy $beacons) {
            $beacons->get('', function (Request $request, Response $response, $args) {
                // Get the Query Params of the request
                $data = $this->route_helper->get($request);
                return $this->route_helper->response($response, $get, 200);
            });

            $beacons->post('', function (Request $request, Response $response, $args) {
                // Get the Query Params of the request
                $data = $this->route_helper->post($request);
                list($result, $status) = $this->beacon_controller->index_post($data);
                return $this->route_helper->response($response, $result, $status);
            });
        });
    }

    private function sensorRoutes($api)
    {
        // Load Controller

        // api/sensors
        $api->group('/sensors', function (RouteCollectorProxy $sensors) {
            $sensors->get('', function (Request $request, Response $response, $args) {
                $data = $this->route_helper->get($request);
                return $this->route_helper->response($response, $data, 200);
            });

            $sensors->post('', function (Request $request, Response $response, $args) {
                $data = $this->route_helper->post($request);
                return $this->route_helper->response($response, $data, 201);
            });

            $sensors->put('', function (Request $request, Response $response, $args) {
                $data = $this->route_helper->put($request);
                return $this->route_helper->response($response, $data, 200);
            });

            $sensors->delete('', function (Request $request, Response $response, $args) {
                $data = $this->route_helper->delete($request);
                return $this->route_helper->response($response, $data, 200);
            });
        });
    }
}

// This runs the app
$app = new Router();
