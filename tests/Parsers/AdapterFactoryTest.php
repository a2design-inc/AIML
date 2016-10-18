<?php

use A2Design\AIML\AdapterFactory;
use A2Design\AIML\Tests\AdapterFactory\WrongClass;

class AdapterFactoryTest extends PHPUnit_Framework_TestCase {

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


    /**
     * @exceptedExceptionCode 404
     * @expectedException \A2Design\AIML\Error\AdapterClassNotFoundException
     */
    public function testClassNotFound()
    {
        $this->factory->registerAdapter('someType', 'someClass');
    }

    /**
     * @exceptedExceptionCode 406
     * @expectedException \A2Design\AIML\Error\AdapterWrongInheritanceException
     */
    public function testWrongInheritance()
    {
        $this->factory->registerAdapter('someType', WrongClass::class);
    }
}
