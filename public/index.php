<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Slim\Routing\RouteCollectorProxy;
use pats\Helpers\RouteHelper;
use pats\Controllers;

require_once(__DIR__ . '/../vendor/autoload.php');

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
            // GET api/beacons/all
            $beacons->get('/all', function (Request $request, Response $response, $args) {
                list($result, $status) = $this->beacon_controller->get_index();
                return $this->route_helper->response($response, $result, $status);
            });

            // POST api/beacons
            $beacons->post('', function (Request $request, Response $response, $args) {
                $data = $this->route_helper->post($request);
                list($result, $status) = $this->beacon_controller->post_index($data);
                return $this->route_helper->response($response, $result, $status);
            });
        });

        // api/beacons/{id}
        $api->group('/beacons/{id}', function (RouteCollectorProxy $beacons) {
            // GET api/beacons/{id}
            $beacons->get('', function (Request $request, Response $response, $args) {
                list($result, $status) = $this->beacon_controller->get_byId($args);
                return $this->route_helper->response($response, $result, $status);
            });

            // PUT api/beacons/{id}
            $beacons->put('', function (Request $request, Response $response, $args) {
                $data = $this->route_helper->put($request);
                list($result, $status) = $this->beacon_controller->put_id($args, $data);
                return $this->route_helper->response($response, $result, $status);
            });

            // DELETE api/beacons/{id}
            $beacons->delete('', function (Request $request, Response $response, $args) {
                list($result, $status) = $this->beacon_controller->delete_id($args);
                return $this->route_helper->response($response, $result, $status);
            });
        });

        // api/beacons/group
        $api->group('/beacons/group', function (RouteCollectorProxy $beacons) {
            // GET api/beacons/group/all

            // POST api/beacons/group
        });

        // api/beacons/group/{group_id}
        $api->group('/beacons/group/{id}', function (RouteCollectorProxy $beacons) {
            // GET api/beacons/group/{id}

            // PUT api/beacons/group/{id}

            // DELETE api/beacons/group/{id}
        });

        // api/beacons/group/{group_id}/location
        $api->group('/beacons/group/{id}/location', function (RouteCollectorProxy $beacons) {
            // GET api/beacons/group/{id}/location/all
            
            // POST api/beacons/group/{id}/location

            // PUT api/beacons/group/{id}/location/{beacon_id}

            // DELETE api/beacons/group/{id}/location/{beacon_id}
        });

        // api/beacons/group/{group_id}/restricted
        $api->group('/beacons/group/{id}/restricted', function (RouteCollectorProxy $beacons) {
            // GET api/beacons/group/{id}/restricted/all
            
            // POST api/beacons/group/{id}/restricted

            // PUT api/beacons/group/{id}/restricted/{restricted_id}

            // DELETE api/beacons/group/{id}/restricted/{restricted_id}
        });
    }

    // TODO.
    private function sensorRoutes($api)
    {
        // api/sensors
        $api->group('/sensors', function (RouteCollectorProxy $sensors) {
            // GET api/sensors/all(?active={bool})

            // POST api/sensors
        });

        // api/sensors/{id}
        $api->group('/sensors/{id}', function (RouteCollectorProxy $sensors) {
            // GET api/sensors/{id}

            // PUT api/sensors/{id}

            // DELETE api/sensors/{id}
        });

        // api/sensors/{id}/location
        $api->group('/sensors/{id}/location', function (RouteCollectorProxy $sensors) {
            // POST api/sensors/{id}/location
        });
    }

    // TODO.
    private function patientRoutes($api)
    {
        // api/patients
        $api->group('/patients', function (RouteCollectorProxy $patients) {
            // GET api/patients/all

            // GET api/patients/locations/all

            // POST api/patients
        });

        // api/patients/{id}
        $api->group('/patients/{id}', function (RouteCollectorProxy $patients) {
            // GET api/patients/{id}

            // GET api/patients/{id}/location

            // GET api/patients/{id}/location/history

            // POST api/patients/{id}/restricted

            // PUT api/patients/{id}/restricted

            // PUT api/patients/{id}

            // DELETE api/patients/{id}
        });
    }

    // TODO.
    private function mapRoutes($api)
    {
        // api/maps
        $api->group('/maps', function (RouteCollectorProxy $maps) {
            // GET api/maps/all

            // POST api/maps
        });

        // api/maps/{id}
        $api->group('/maps/{id}', function (RouteCollectorProxy $maps) {
            // GET api/maps/{id}

            // POST api/maps/{id}/restricted

            // PUT api/maps/{id}/restricted

            // PUT api/maps/{id}

            // DELETE api/maps/{id}
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
