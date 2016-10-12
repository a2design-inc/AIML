<?php

use PHPUnit\Framework\TestCase;
use A2Design\AIML\AdapterFactory;

class AdapterFactoryTest extends TestCase {

    public function setUp()
    {
        $this->factory = new AdapterFactory();
    }


    /**
     * @expectedException \A2Design\AIML\Error\AdapterNotFoundException
     * @exceptedExceptionCode 404
     */
    public function testAdapterNotFound()
    {
        $this->factory->getAdapter('test', 'undefined_type');
    }

    /**
     * @exceptedExceptionCode 404
     * @expectedException \A2Design\AIML\Error\Dictionary\NotFoundException
     */
    public function testDictionaryNotFound()
    {
        $this->factory->getAdapter('test', 'file');
    }
}
