<?php

namespace A2Design\AIML\Tests\AdapterFactory;

use A2Design\AIML\Contracts\Adapter;

class ProperAdapterClass extends Adapter {
    /**
     * Checks AIML content existance
     *
     * @param  string $aiml AIML content
     *
     * @return boolean
     */
    public function checkExists($aiml)
    {
        return true;
    }


    /**
     * Saves AIML content to cache property
     *
     * @param string $aimlContent AIML Content
     */
    public function setAIML($aimlContent)
    {
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
        return null;
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
        return null;
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
        return null;
    }


    /**
     * Search category by question
     *
     * @param  string $question AIML question pattern
     * @return null
     */
    protected function _searchCategory($question)
    {
        return null;
    }
}
