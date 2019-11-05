<?php

namespace pats\Controllers;

class RESTController
{
    public function response($success, $data, $status)
    {
        $response = [];
        $response['success'] = $success;

        if ($success) {
            $response['data'] = $data;
        } else {
            $response['message'] = $data;
        }

        return [$response, $status];
    }
}
