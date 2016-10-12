<?php

namespace A2Design\AIML\Adapters;

use A2Design\AIML\Contracts\Adapter;
use A2Design\AIML\Parsers\AIMLParser;

use SimpleXMLElement;

class FileAdapter extends Adapter {

    public function __construct()
    {
        $this->aimlParser = new AIMLParser();
    }

    /**
     * Checks AIML content existance
     *
     * @param  string $aiml AIML content
     *
     * @return boolean
     */
    public function checkExists($aiml)
    {
        return file_exists($aiml);
    }


    /**
     * Saves AIML content to cache property
     *
     * @param string $aimlContent AIML Content
     */
    public function setAIML($aimlContent)
    {
        $content = file_get_contents($aimlContent);
        $this->content = new SimpleXMLElement($content);
        $this->parseAIML($this->content, '');
    }


    /**
     * Returns AIML content
     *
     * @return SimpleXMLElement
     */
    public function getAIML()
    {
        return $this->content;
    }


    /**
     * Prepare AIML content for parser
     *
     * @param  SimpleXMLElement $content AIML XML object
     *
     * @return SimpleXMLElement
     */
    public function prepareAIML($content)
    {
        return $content;
    }


    /**
     * Search pattern in AIML
     *
     * @param  string $pattern AIML question pattern
     *
     * @return Answer
     */
    protected function _searchPattern($pattern)
    {
        $rawAnswer = $this->aimlParser->getAnswer($pattern);
        return $rawAnswer;
    }


    /**
     * Seatch answer by provided question
     *
     * @param  string $question AIML question pattern
     *
     * @return mixed
     */
    protected function _searchAnswer($question)
    {
        $categories = $this->searchCategory($question);
        return $categories;
    }


    /**
     * Search category by question
     *
     * @param  string $question AIML question pattern
     * @return null
     */
    protected function _searchCategory($question)
    {
        $aiml = $this->parseAIML($this->content, $question);
        return $aiml;
    }
}
