<?php

use PHPUnit\Framework\TestCase;

class ResponseServiceTest extends TestCase
{
    private $responseService;

    function setUp() : void
    {
        $this->responseService = new ResponseService();
    }

    function testEmptyPayload()
    {
        $result = $this->responseService->call();
        $this->assertEquals($this->responseService::STATUS_CODES['OK'], $result->getStatusCode());
        $this->assertEquals('[]', $result->getContent());
    }

    function testNotEmptyPayload()
    {
        $result = $this->responseService->call(['id' => 1, 'message' => "created"]);
        $this->assertEquals($this->responseService::STATUS_CODES['OK'], $result->getStatusCode());
        $this->assertEquals('{"id":1,"message":"created"}', $result->getContent());
    }

    function testSetStatusCode()
    {
        $result = $this->responseService->call([], 201);
        $this->assertEquals($this->responseService::STATUS_CODES['CREATED'], $result->getStatusCode());
        $this->assertEquals('[]', $result->getContent());
    }

    function testNoContent()
    {
        $result = $this->responseService->call(['id' => 1, 'message' => "created"], 204);
        $this->assertEquals($this->responseService::STATUS_CODES['NO_CONTENT'], $result->getStatusCode());
        $this->assertEmpty($result->getContent());
    }

    function testCallWithNonStandardStatusCode()
    {
        $this->expectException('Phalcon\Http\Response\Exception');
        $this->responseService->call([], 123);
    }

    function testCallWithNotArrayPayload()
    {
        $this->expectException('InvalidArgumentException');
        $this->responseService->call(["testing \xff"]);
    }
}
