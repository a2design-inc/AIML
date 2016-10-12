<?php

namespace A2Design\AIML\Parsers;

use SimpleXMLElement;
use A2Design\AIML\Parsers\Partials\Category;
use A2Design\AIML\Answer;
use A2Design\AIML\Utils\Collection;

class AIMLParser {

    protected $linksTree = [];
    protected $patterns = null;
    protected $templates = [];

    protected $lastAnswer = null;

    protected $searchTree = [];

    public function __construct()
    {
        $this->patterns = new Collection();
    }


    /**
     * Parse provided XML element and creates search index
     *
     * @param  SimpleXMLElement $aiml provided XML content
     *
     * @return void
     */
    public function parse(SimpleXMLElement $aiml)
    {

        $categories = $aiml->xpath('/aiml/category');
        $conversations = [];

        foreach ($categories as $category) {
            $category = new Category($category->asXML());
            $pattern = trim(strval($category->pattern));
            $hash = md5($pattern);
            if ($category->hasSrai()) {
                $sraiHash = trim(strval($category->srai));
                $this->linksTree[$hash] = $sraiHash;
                continue;
            }

            $answer = new Answer($category->template, $category->pattern);
            $addedToConversations = false;

            $templateHash = md5($category->getTemplate());

            if ($category->hasThat()) {
                $key =  md5($category->getThat()) . '_' . $hash;
                $conversations[$key] = $templateHash;
                $addedToConversations = true;
            }

            if (!$addedToConversations) {
                $this->patterns[$pattern] = $answer;
            }

            $this->templates[$templateHash] = $answer;
        }

        $this->parseConversations($conversations);
    }


    /**
     * Returns answer for provided question pattern
     *
     * @param  string $question AIML question pattern
     *
     * @return Answer
     */
    public function getAnswer($question)
    {
        if (!empty($this->lastAnswer)) {
            $converstaion = $this->lastAnswer->getConversation($question);
            if (!empty($converstaion)) {
                return $this->logAnswer($converstaion);
            }
        }

        $answer = $this->patterns[$question];
        if (!empty($answer)) {
            return $this->logAnswer($answer);
        }

        $hash = md5($question);

        while (!empty($this->linksTree[$hash])) {
            $hash = $this->linksTree[$hash];
        }

        if (empty($this->patterns[$hash])) {
            return null;
        }

        return $this->logAnswer($this->patterns[$hash]);
    }

    /**
     * Parse conversation and builds conversation tree
     *
     * @param  array $conversations  list of conversation answers
     * @return void
     */
    protected function parseConversations($conversations)
    {
        if (empty($conversations)) {
            return null;
        }

        foreach ($conversations as $key => $conversation) {
            if (empty($this->templates[$conversation])) {
                continue;
            }

            $keys = explode('_', $key);
            $parentKey = $keys[0];

            $answer = $this->templates[$conversation];

            if (empty($this->templates[$parentKey])) {
                continue;
            }

            $parent = $this->templates[$parentKey];
            $parent->addConverstionNode($answer);
        }
    }

    /**
     * Updates lastAnwer property
     *
     * @param  Answer $answer last founded answer
     * 
     * @return Answer
     */
    protected function logAnswer($answer)
    {
        $this->lastAnswer = null;
        if ($answer->hasConversation()) {
            $this->lastAnswer = $answer;
        }

        return $answer;
    }
}
