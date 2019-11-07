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
        // Create Instance of Slim App
        $app = AppFactory::create();
        $app->addBodyParsingMiddleware();
        
        // Add Error Middleware
        $errorMiddleware = $app->addErrorMiddleware(true, true, true);

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

    //======================================================================
    // MAIN ROUTES
    //======================================================================

    private function apiRoutes($app)
    {
        // API endpoints
        $app->group('/api', function (RouteCollectorProxy $api) {
            $this->beaconRoutes($api);
            $this->sensorRoutes($api);
        });
    }

    private function testRoutes($app) 
    {
        $app->get('/', function (Request $request, Response $response, $args) {
            $response->getBody()->write("Hello world!");
            return $response;
        });
        
        $app->get('/gettest', function (Request $request, Response $response, $args) {
            $allGetVars = $request->getQueryParams();
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

    //-----------------------------------------------------
    // API Routes
    //-----------------------------------------------------
    private function beaconRoutes($api)
    {
        // api/beacons
        $api->group('/beacons', function (RouteCollectorProxy $beacons) {
            // GET api/beacons
            $beacons->get('', function (Request $request, Response $response, $args) {
                //$data = $this->route_helper->get($request);
                list($result, $status) = $this->beacon_controller->index_get();
                return $this->route_helper->response($response, $result, $status);
            });

            // POST api/beacons
            $beacons->post('', function (Request $request, Response $response, $args) {
                $data = $this->route_helper->post($request);
                list($result, $status) = $this->beacon_controller->index_post($data);
                return $this->route_helper->response($response, $result, $status);
            });
        });
    }

    // TODO.
    private function sensorRoutes($api)
    {
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

    //======================================================================
    // HANDLERS
    //======================================================================
    private function NotFoundHandler()
    {
        $c = new \Slim\Container(); //Create Your container

        //Override the default Not Found Handler before creating App
        $c['notFoundHandler'] = function ($c) {
            return function ($request, $response) use ($c) {
                return $response->withStatus(404)
                    ->withHeader('Content-Type', 'text/html')
                    ->write('Endpoint not found');
            };
        };

        return $c;
    }
}

// This runs the app
$app = new Router();
