<?php

namespace pats\Helpers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class RouteHelper 
{
    /**
     * Formats the response.
     */
    public function response(Response $response, $data, int $status)
    {
        $data_json = json_encode($data);
        $res = $response->getBody()->write($data_json);
        $res = $response->withHeader('Content-Type', 'application/json')->withHeader('Access-Control-Allow-Origin', '*')->withStatus($status);
        return $res;
    }

    /**
     * Gets all of the params for a GET request
     */
    public function get(Request $request)
    {
        return $request->getQueryParams();
    }

    /**
     * Gets the body of the POST request
     */
    public function post(Request $request)
    {
        return $request->getParsedBody();
    }

    /**
     * Gets the body of the PUT request
     */
    public function put(Request $request)
    {
        return $request->getParsedBody();
    }

    /**
     * Gets the body of the DELETE request
     */
    public function delete(Request $request)
    {
        return $request->getParsedBody();
    }
}
