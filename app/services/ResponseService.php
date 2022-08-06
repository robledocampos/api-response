<?php

namespace robledocampos\api_response\services;


use Phalcon\Http\Response;
use robledocampos\api_response\exceptions\JsonEncodeException;

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
        $this->response->setHeader('Content-Type', 'application/json');
    }

    function buildFromArray(array $payload = [], int $statusCode = self::STATUS_CODES['OK']) : Response
    {
        $jsonPayload = json_encode($payload);
        if (!$jsonPayload) {
            throw new JsonEncodeException();
        }

        return $this->buildResponse($jsonPayload, $statusCode);
    }

    function buildFromJson(string $jsonPayload = "", int $statusCode = self::STATUS_CODES['OK']) : Response
    {
        return $this->buildResponse($jsonPayload, $statusCode);
    }

    function buildFromException(\Exception $exception) : Response
    {
        $messages = explode("|", $exception->getMessage());
        $body = ['message' => null];
        if (count($messages) > 1) {
            $body['message'] = $messages;
        } else {
            $body['message'] = $messages[0];
        }
        $statusCode = in_array($exception->getCode(), ResponseService::STATUS_CODES) ?
            $exception->getCode() : ResponseService::STATUS_CODES['INTERNAL_SERVER_ERROR'];

        return $this->buildFromArray($body, $statusCode);
    }

    private function buildResponse(string $jsonPayload, int $statusCode) : Response
    {
        $this->response->setStatusCode($statusCode);
        if ($statusCode != self::STATUS_CODES['NO_CONTENT']) {
            $this->response->setContent($jsonPayload);
        }

        return $this->response;
    }
}
