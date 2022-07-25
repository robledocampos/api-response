<?php

use Phalcon\Http\Response;

class ResponseService
{
    const STATUS_CODES = [
        'CONTINUE' => 100,
        'SWITCHING_PROTOCOL' => 101,
        'PROCESSING' => 102,
        'EARLY_HIT' => 103,
        'OK' => 200,
        'CREATED' => 201,
        'ACCEPTED' => 202,
        'NON_AUTHORITATIVE_INFORMATION' => 203,
        'NO_CONTENT' => 204,
        'RESET_CONTENT' => 205,
        'PARTIAL_CONTENT' => 206,
        'MULTIPLE_CHOICE' => 300,
        'MOVED_PERMANENTLY' => 301,
        'FOUND' => 302,
        'SEE_OTHER' => 303,
        'NOT_MODIFIED' => 304,
        'TEMPORARY_REDIRECT' => 307,
        'PERMANENT_REDIRECT' => 308,
        'BAD_REQUEST' => 400,
        'UNAUTHORIZED' => 401,
        'FORBIDDEN' => 403,
        'NOT_FOUND' => 404,
        'METHOD_NOT_ALLOWED' => 405,
        'NOT_ACCEPTABLE' => 406,
        'PROXY_AUTHENTICATION_REQUIRED' => 407,
        'REQUEST_TIMEOUT' => 408,
        'CONFLICT' => 409,
        'GONE' => 410,
        'LENGTH_REQUIRED' => 411,
        'PRECONDITION_FAILED' => 412,
        'PAYLOAD_TOO_LARGE' => 413,
        'URI_TOO_LONG' => 414,
        'UNSUPPORTED_MEDIA_TYPE' => 415,
        'UNPROCESSABLE_ENTITY' => 422,
        'PRECONDITION_REQUIRED' => 428,
        'TOO_MANY_REQUESTS' => 429,
        'INTERNAL_SERVER_ERROR' => 500,
        'NOT_IMPLEMENTED' => 501,
        'BAD_GATEWAY' => 502,
        'SERVICE_UNAVAILABLE' => 503,
        'GATEWAY_TIMEOUT' => 504,
        'HTTP_VERSION_NOT_SUPPORTED' => 505,
        'NETWORK_AUTHENTICATION_REQUIRED' => 511
    ];

    private Response $response;

    function __construct()
    {
        $this->response = new Response();
    }

    function call(Array $payload = [], int $statusCode = self::STATUS_CODES['OK']) : Response
    {
        try {

            return $this->buildResponse($payload, $statusCode);
        } catch (Exception $exception) {

            throw $exception;
        }
    }

    private function buildResponse(Array $payload, int $statusCode) : Response
    {
        $this->response->setStatusCode($statusCode);

        if ($statusCode != self::STATUS_CODES['NO_CONTENT']) {
            $this->response->setJsonContent($payload, JSON_UNESCAPED_UNICODE);
        }

        return $this->response;
    }
}