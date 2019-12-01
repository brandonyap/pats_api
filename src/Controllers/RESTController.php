<?php

namespace pats\Controllers;

class RESTController
{
    /**
     * This formats the response to be sent back to the router.
     * 
     * @param bool $success is whether or not the request was successful or not.
     * @param array $data is the data to be sent back for the user to see.
     * @param int $status is the response code sent back for the user.
     * @return array containing the response and the status code. 
     */
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
