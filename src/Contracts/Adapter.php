<?php

namespace A2Design\AIML\Contracts;

use A2Design\AIML\Contracts\Interfaces\Parsable;
use A2Design\AIML\Contracts\Interfaces\Searchable;

abstract class Adapter implements Parsable, Searchable {

    protected $aimlParser = null;
    protected $content = null;

    abstract public function prepareAIML($aimlContent);
    abstract public function getAIML();
    abstract public function setAIML($aimlContent);

    abstract public function checkExists($aiml);

    abstract protected function _searchCategory($question);
    abstract protected function _searchPattern($pattern);
    abstract protected function _searchAnswer($question);

    public function parseAIML($aimlContent = null)
    {
        if (empty($aimlContent)) {
            $aimlContent = $this->content;
        }

        $content = $this->prepareAIML($aimlContent);
        return $this->aimlParser->parse($content);
    }

    public function searchCategory($question)
    {
        return $this->_searchCategory($question);
    }

    public function searchPattern($pattern)
    {
        return $this->_searchPattern($pattern);
    }

    public function searchAnswer($question)
    {
        return $this->_searchAnswer($question);
    }


}
