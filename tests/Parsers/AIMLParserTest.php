<?php

use A2Design\AIML\Parsers\AIMLParser;

class AIMLParserTest extends PHPUnit_Framework_TestCase {

    public function setUp()
    {
        $this->parser = new AIMLParser();

        $aimlFile = realpath(__DIR__.'/../storage/simple.aiml');
        $this->aimlContent = file_get_contents($aimlFile);

        $this->parser->parse(new SimpleXMLElement($this->aimlContent));
    }

    public function testTestPatterns()
    {
        $answer = $this->parser->getAnswer('hello');
        $this->assertEquals($answer->getContent('hello'), 'hello');
    }

    public function testNotMatch()
    {
        $answer = $this->parser->getAnswer('hello');
        $this->assertEquals($answer->match('welcome'), -1);
    }

    public function testGetAnswerStarMatch()
    {
        $answer = $this->parser->getAnswer('hello jimmy');
        $this->assertEquals($answer->getPattern(), 'hello *');
    }

    public function testMatchTwoWords()
    {
        $answer = $this->parser->getAnswer('hello');
        $this->assertEquals($answer->match('hello'), 0);

        $answer1 = $this->parser->getAnswer('hello *');
        $this->assertEquals($answer1->match('hello eddy'), 0.25);

        $answer2 = $this->parser->getAnswer('* eddy');
        $this->assertEquals($answer2->match('hello eddy'), 0.5);

        $answer3 = $this->parser->getAnswer('* *');
        $this->assertEquals($answer3->match('hello eddy'), 0.75);
    }

    public function testMatchTreeWords()
    {
        $answer1 = $this->parser->getAnswer('hello dear *');
        $this->assertEquals($answer1->match('hello dear eddy'), 0.125);

        $answer2 = $this->parser->getAnswer('hello * eddy');
        $this->assertEquals($answer2->match('hello dear eddy'), 0.25);

        $answer3 = $this->parser->getAnswer('* dear eddy');
        $this->assertEquals($answer3->match('hello dear eddy'), 0.5);

        $answer4 = $this->parser->getAnswer('hello * *');
        $this->assertEquals($answer4->match('hello dear eddy'), 0.375);

        $answer5 = $this->parser->getAnswer('* dear *');
        $this->assertEquals($answer5->match('hello dear eddy'), 0.625);

        $answer6 = $this->parser->getAnswer('* * eddy');
        $this->assertEquals($answer6->match('hello dear eddy'), 0.75);

        $answer6 = $this->parser->getAnswer('* * *');
        $this->assertEquals($answer6->match('hello dear eddy'), 0.875);
    }

    public function testAnswerStarsReplace()
    {
        $answer = $this->parser->getAnswer('hello, i\'m john');
        $this->assertEquals($answer->replaceStars('hello, i\'m john'), 'hello john, are you fine?');
    }

    /**
     *
     */
    public function testConversation()
    {
        $this->parser->getAnswer('hello eddy');
        $this->assertNull($this->parser->getAnswer('yes'));

        $this->parser->getAnswer('hello, i\'m john');

        $answer = $this->parser->getAnswer('yes');
        $this->assertNotNull($answer);

        $this->parser->getAnswer('hello, i\'m sandy');
        $noAnswer = $this->parser->getAnswer('no');
        $this->assertNotNull($noAnswer);
        $yesAnswer = $this->parser->getAnswer('yes');
        $this->assertNotNull($yesAnswer);
        $this->assertEquals($noAnswer->getContent('no'), 'why? are you sad?');
        $this->assertEquals($yesAnswer->getContent('yes'), 'do you have a problems?');
    }
}
