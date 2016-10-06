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

    public function setAIML($aimlContent)
    {
        $content = file_get_contents($aimlContent);
        $this->content = new SimpleXMLElement($content);
    }

    public function getAIML()
    {
        return $this->content;
    }

    public function prepareAIML($content)
    {
        return $content;
    }

    protected function _searchPattern($pattern)
    {
        $this->parseAIML($this->content, $pattern);
        $rawAnswer = $this->aimlParser->getAnswer($pattern);
        return $rawAnswer;
    }

    protected function _searchAnswer($question)
    {
        $categories = $this->searchCategory($question);
        return $categories;
    }

    protected function _searchCategory($question)
    {
        $aiml = $this->parseAIML($this->content, $question);
        return $aiml;
    }
}
