<?php

use robledocampos\api_response\services\ResponseService;

use PHPUnit\Framework\TestCase;

class ResponseServiceTest extends TestCase
{
    private ResponseService $responseService;

    function setUp() : void
    {
        $this->responseService = new ResponseService();
    }

    function testNoArgsArrayPayload()
    {
        $result = $this->responseService->buildFromArray();
        $this->assertEquals(200, $result->getStatusCode());
        $this->assertEquals("[]", $result->getContent());
    }

    function testNoArgsJsonPayload()
    {
        $result = $this->responseService->buildFromJson();
        $this->assertEquals(200, $result->getStatusCode());
        $this->assertEquals("", $result->getContent());
    }

    function testArrayPayload()
    {
        $result = $this->responseService->buildFromArray(['id' => 1, 'message' => "created"]);
        $this->assertEquals(200, $result->getStatusCode());
        $this->assertEquals('{"id":1,"message":"created"}', $result->getContent());
    }

    function testJsonPayload()
    {
        $result = $this->responseService->buildFromJson('{"id":1,"message":"created"}');
        $this->assertEquals(200, $result->getStatusCode());
        $this->assertEquals('{"id":1,"message":"created"}', $result->getContent());
    }

    function testExceptionPayload()
    {
        $exception = new Exception(
            "Exception test msg 1|Exception test msg 2",
            $this->responseService::STATUS_CODES['INTERNAL_SERVER_ERROR']
        );
        $result = $this->responseService->buildFromException($exception);
        $this->assertEquals(500, $result->getStatusCode());
        $this->assertEquals('{"message":["Exception test msg 1","Exception test msg 2"]}', $result->getContent());
    }

    function testArrayPayloadSetStatusCode()
    {
        $result = $this->responseService->buildFromArray([], ResponseService::STATUS_CODES['CREATED']);
        $this->assertEquals(201, $result->getStatusCode());
        $this->assertEquals("[]", $result->getContent());
    }

    function testJsonPayloadSetStatusCode()
    {
        $result = $this->responseService->buildFromJson("", ResponseService::STATUS_CODES['CREATED']);
        $this->assertEquals(201, $result->getStatusCode());
        $this->assertEquals("", $result->getContent());
    }

    function testArrayPayloadNoContent()
    {
        $result = $this->responseService->buildFromArray(
            ['id' => 1, 'message' => "created"],
            ResponseService::STATUS_CODES['NO_CONTENT']
        );
        $this->assertEquals(204, $result->getStatusCode());
        $this->assertEmpty($result->getContent());
    }

    function testJsonPayloadNoContent()
    {
        $result = $this->responseService->buildFromJson(
            '{"id":1,"message":"created"}',
            ResponseService::STATUS_CODES['NO_CONTENT']
        );
        $this->assertEquals(204, $result->getStatusCode());
        $this->assertEmpty($result->getContent());
    }

    function testArrayPayloadWithNonStandardStatusCode()
    {
        $this->expectException('Phalcon\Http\Response\Exception');
        $this->responseService->buildFromArray([], 123);
    }

    function testJsonPayloadWithNonStandardStatusCode()
    {
        $this->expectException('Phalcon\Http\Response\Exception');
        $this->responseService->buildFromJson("", 123);
    }

    function testArrayPayloadWithNonUTF8Payload()
    {
        $this->expectException('JsonEncodeException');
        $this->responseService->buildFromArray(["testing \xff"]);
    }


}
