<?php

namespace pats\Helpers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class RouteHelper 
{
    public function response(Response $response, $data, int $status)
    {
        $data_json = json_encode($data);
        $res = $response->getBody()->write($data_json);
        $res = $response->withStatus($status);
        return $res;
    }

    public function get(Request $request)
    {
        return $request->getQueryParams();
    }

    public function post(Request $request)
    {
        return $request->getParsedBody();
    }
}
