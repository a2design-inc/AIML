<?php

use A2Design\AIML\AIML;

class AIMLTest extends PHPUnit_Framework_TestCase {

    public function setUp()
    {
        $this->chat = new AIML();
        $basicTest = realpath(__DIR__ . '/../storage/basic.aiml');
        $this->chat->addDict($basicTest);
    }

    /**
    * @exceptedExceptionCode 404
    * @expectedException \A2Design\AIML\Error\Dictionary\NotFoundException
     */
    public function testThrowNotFound()
    {
        $this->chat->addDict('test');
    }

    /**
     * @beforeClass
     *
     */
    public function testAddDict()
    {
        $aimlFile = realpath(__DIR__.'/../storage/simple.aiml');
        $result = $this->chat->addDict($aimlFile);
        $this->assertNotNull($result);
    }

    /**
     * @depends testAddDict
     */
    public function testAnswerNotFound()
    {
        $answer = $this->chat->getAnswer('i don\'t know what you need');
        $this->assertEquals($answer, '...');
    }

    /**
     * @depends testAnswerNotFound
     */
    public function testBotName()
    {
        $this->chat->setChatInfo(['name' => 'AIML Bot Test']);
        $answer = $this->chat->getAnswer('hi bot');
        $this->assertEquals($answer, 'Hello, my name is AIML Bot Test');
    }
}
