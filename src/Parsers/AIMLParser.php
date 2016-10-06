<?php

namespace A2Design\AIML\Parsers;

use SimpleXMLElement;
use A2Design\AIML\Answer;

class AIMLParser {

    protected $linksTree = [];
    protected $patterns = [];

    public function parse(SimpleXMLElement $aiml)
    {

        $categories = $aiml->xpath('/aiml/category');


        foreach ($categories as $category) {
            $hash = md5($category->pattern);
            if (!empty($category->srai)) {
                $sraiHash = md5($category->srai);
                $this->linksTree[$hash] = $sraiHash;
                continue;
            }

            $this->patterns[$hash] = new Answer($category->template);
        }
    }

    public function getAnswer($question)
    {
        $hash = md5($question);

        if (!empty($this->patterns[$hash])) {
            return $this->patterns[$hash];
        }

        while (!empty($this->linksTree[$hash])) {
            $hash = $this->linksTree[$hash];
        }

        if (empty($this->patterns[$hash])) {
            return null;
        }

        return $this->patterns[$hash];
    }
}
