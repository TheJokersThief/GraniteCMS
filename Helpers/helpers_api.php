<?php

// Define some standard response messages/codes
define('SUCCESS', '200 OK');
define('CREATED', '201 CREATED');
define('ACCEPTED', '202 ACCEPTED');
define('NO_CONTENT', '204 NO CONTENT');
define('BAD_REQUEST', '400 BAD REQUEST');
define('UNAUTHORIZED', '401 UNAUTHORIZED');
define('FORBIDDEN', '403 FORBIDDEN');
define('NOT_FOUND', '404 NOT_FOUND');
define('SERVER_ERROR', '500 INTERNAL SERVER ERROR');

function apiResponse($status, $data = [])
{
    return response()->json(['status' => $status, 'data' => $data]);
}
