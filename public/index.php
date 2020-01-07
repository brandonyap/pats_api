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
        $this->beacon_group_controller = new Controllers\BeaconGroupController();
        $this->sensor_controller = new Controllers\SensorController();
        $this->patient_controller = new Controllers\PatientController();

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
            $this->patientRoutes($api);
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

        // api/beacons/{beacon_id}/location
        // Note: Requires group id in body
        $api->group('/beacons/{id}/location', function (RouteCollectorProxy $beacons) {
            // GET api/beacons/{id}/location
            
            // POST api/beacons/{id}/location

            // PUT api/beacons/{id}/location

            // DELETE api/beacons/{id}/location
        });

        // api/beacons/group
        $api->group('/beacons/group', function (RouteCollectorProxy $beacons) {
            // GET api/beacons/group/all
            $beacons->get('/all', function (Request $request, Response $response, $args) {
                list($result, $status) = $this->beacon_group_controller->get_index();
                return $this->route_helper->response($response, $result, $status);
            });

            // POST api/beacons/group
            $beacons->post('', function (Request $request, Response $response, $args) {
                $data = $this->route_helper->post($request);
                list($result, $status) = $this->beacon_group_controller->post_index($data);
                return $this->route_helper->response($response, $result, $status);
            });
        });

        // api/beacons/group/{group_id}
        $api->group('/beacons/group/{id}', function (RouteCollectorProxy $beacons) {
            // GET api/beacons/group/{id}
            $beacons->get('', function (Request $request, Response $response, $args) {
                list($result, $status) = $this->beacon_group_controller->get_byId($args);
                return $this->route_helper->response($response, $result, $status);
            });

            // PUT api/beacons/group/{id}
            $beacons->put('', function (Request $request, Response $response, $args) {
                $data = $this->route_helper->put($request);
                list($result, $status) = $this->beacon_group_controller->put_id($args, $data);
                return $this->route_helper->response($response, $result, $status);
            });

            // DELETE api/beacons/group/{id}
            $beacons->delete('', function (Request $request, Response $response, $args) {
                list($result, $status) = $this->beacon_group_controller->delete_id($args);
                return $this->route_helper->response($response, $result, $status);
            });
        });

        // api/beacons/group/{group_id}/location
        $api->group('/beacons/group/{id}/location', function (RouteCollectorProxy $beacons) {
            // GET api/beacons/group/{id}/location/all
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
            $sensors->get('/all', function (Request $request, Response $response, $args) {
                $data = $this->route_helper->get($request);
                list($result, $status) = $this->sensor_controller->get_index($data);
                return $this->route_helper->response($response, $result, $status);
            });

            // POST api/sensors
            $sensors->post('', function (Request $request, Response $response, $args) {
                $data = $this->route_helper->post($request);
                list($result, $status) = $this->sensor_controller->post_index($data);
                return $this->route_helper->response($response, $result, $status);
            });
        });

        // api/sensors/{id}
        $api->group('/sensors/{id}', function (RouteCollectorProxy $sensors) {
            // GET api/sensors/{id}
            $sensors->get('', function (Request $request, Response $response, $args) {
                list($result, $status) = $this->sensor_controller->get_byId($args);
                return $this->route_helper->response($response, $result, $status);
            });

            // PUT api/sensors/{id}
            $sensors->put('', function (Request $request, Response $response, $args) {
                $data = $this->route_helper->put($request);
                list($result, $status) = $this->sensor_controller->put_id($args, $data);
                return $this->route_helper->response($response, $result, $status);
            });

            // DELETE api/sensors/{id}
            $sensors->delete('', function (Request $request, Response $response, $args) {
                list($result, $status) = $this->sensor_controller->delete_id($args);
                return $this->route_helper->response($response, $result, $status);
            });
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
            $patients->get('/all', function (Request $request, Response $response, $args) {
                list($result, $status) = $this->patient_controller->get_index();
                return $this->route_helper->response($response, $result, $status);
            });

            // GET api/patients/locations/all

            // POST api/patients
            $patients->post('', function (Request $request, Response $response, $args) {
                $data = $this->route_helper->post($request);
                list($result, $status) = $this->patient_controller->post_index($data);
                return $this->route_helper->response($response, $result, $status);
            });
        });

        // api/patients/{id}
        $api->group('/patients/{id}', function (RouteCollectorProxy $patients) {
            // GET api/patients/{id}
            $patients->get('', function (Request $request, Response $response, $args) {
                list($result, $status) = $this->patient_controller->get_byId($args);
                return $this->route_helper->response($response, $result, $status);
            });

            // GET api/patients/{id}/location

            // GET api/patients/{id}/location/history

            // POST api/patients/{id}/restricted

            // PUT api/patients/{id}/restricted

            // PUT api/patients/{id}
            $patients->put('', function (Request $request, Response $response, $args) {
                $data = $this->route_helper->put($request);
                list($result, $status) = $this->patient_controller->put_id($args, $data);
                return $this->route_helper->response($response, $result, $status);
            });

            // DELETE api/patients/{id}
            $patients->delete('', function (Request $request, Response $response, $args) {
                list($result, $status) = $this->patient_controller->delete_id($args);
                return $this->route_helper->response($response, $result, $status);
            });
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
}

// This runs the app
$app = new Router();
