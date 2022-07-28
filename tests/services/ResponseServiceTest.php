<?php

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
        $result = $this->responseService->buildWithArray();
        $this->assertEquals($this->responseService::STATUS_CODES['OK'], $result->getStatusCode());
        $this->assertEquals('[]', $result->getContent());
    }

    function testNoArgsJsonPayload()
    {
        $result = $this->responseService->buildWithJson();
        $this->assertEquals($this->responseService::STATUS_CODES['OK'], $result->getStatusCode());
        $this->assertEquals('[]', $result->getContent());
    }

    function testArrayPayload()
    {
        $result = $this->responseService->buildWithArray(['id' => 1, 'message' => "created"]);
        $this->assertEquals($this->responseService::STATUS_CODES['OK'], $result->getStatusCode());
        $this->assertEquals('{"id":1,"message":"created"}', $result->getContent());
    }

    function testJsonPayload()
    {
        $result = $this->responseService->buildWithJson('{"id":1,"message":"created"}');
        $this->assertEquals($this->responseService::STATUS_CODES['OK'], $result->getStatusCode());
        $this->assertEquals('{"id":1,"message":"created"}', $result->getContent());
    }

    function testArrayPayloadSetStatusCode()
    {
        $result = $this->responseService->buildWithArray([], 201);
        $this->assertEquals($this->responseService::STATUS_CODES['CREATED'], $result->getStatusCode());
        $this->assertEquals('[]', $result->getContent());
    }

    function testJsonPayloadSetStatusCode()
    {
        $result = $this->responseService->buildWithJson("", 201);
        $this->assertEquals($this->responseService::STATUS_CODES['CREATED'], $result->getStatusCode());
        $this->assertEquals('[]', $result->getContent());
    }

    function testArrayPayloadNoContent()
    {
        $result = $this->responseService->buildWithArray(['id' => 1, 'message' => "created"], 204);
        $this->assertEquals($this->responseService::STATUS_CODES['NO_CONTENT'], $result->getStatusCode());
        $this->assertEmpty($result->getContent());
    }

    function testJsonPayloadNoContent()
    {
        $result = $this->responseService->buildWithJson('{"id":1,"message":"created"}', 204);
        $this->assertEquals($this->responseService::STATUS_CODES['NO_CONTENT'], $result->getStatusCode());
        $this->assertEmpty($result->getContent());
    }

    function testArrayPayloadWithNonStandardStatusCode()
    {
        $this->expectException('Phalcon\Http\Response\Exception');
        $this->responseService->buildWithArray([], 123);
    }

    function testJsonPayloadWithNonStandardStatusCode()
    {
        $this->expectException('Phalcon\Http\Response\Exception');
        $this->responseService->buildWithJson("", 123);
    }

    function testArrayPayloadWithNonUTF8Payload()
    {
        $this->expectException('JsonEncodeException');
        $this->responseService->buildWithArray(["testing \xff"]);
    }

    function testJsonPayloadWithNonUTF8Payload()
    {
        $this->expectException('JsonEncodeException');
        $this->responseService->buildWithJson("testing \xff");
    }
}
