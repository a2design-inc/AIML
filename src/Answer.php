<?php

namespace A2Design\AIML;

use SimpleXMLElement;
use A2Design\AIML\Utils\Collection;
use A2Design\AIML\AIML;
use A2Design\AIML\Contracts\BaseAnswer;

class Answer extends BaseAnswer {

    protected $_rawAnswer = null;
    protected $answer = "";
    protected $pattern = "";
    protected $patternTokens = [];
    protected $conversationNodes = null;
    protected $chat = null;

    public function __construct(SimpleXMLElement $rawAnswer, $pattern)
    {
        $this->_rawAnswer = $rawAnswer;
        $this->answer = strval(trim($rawAnswer));
        $this->pattern = strval($pattern);
        $this->patternTokens = explode(' ', $pattern);
        $this->conversationNodes = new Collection();
    }

    /**
     * Check that AIML pattern match provided question and calculates
     * match score
     *
     * @param  string  $question question pattern for match checking
     *
     * @return integer           match score
     */
    public function match($question)
    {
        $questionTokens = explode(' ', $question);
        $score = -1;

        if ($question === $this->pattern) {
            return 0;
        }

        if (count($this->patternTokens) !== count($questionTokens)) {
            return $score;
        }


        foreach ($questionTokens as $tokenIndex => $token) {
            $patternToken = $this->patternTokens[$tokenIndex];

            if ($patternToken === $token) {
                continue;
            }

            if ($patternToken === '*') {
                $score += pow(2, -($tokenIndex + 1));
            }
        }

        if ($score !== -1) {
            $score += 1;
        }

        return $score;
    }


    /**
     * Returns string content of answer
     *
     * @return string
     */
    public function getContent($question, $chatVars = [], $userVars = [])
    {
        $this->replaceChatVars($chatVars);
        $this->replaceUserVars($userVars);
        $this->replaceStars($question);
        $answer = $this->convertBufferToString();
        $this->clearBuffers();
        return $answer;
    }

    /**
    * Replaces <star /> tags by variables provied in question
    *
    * @param  array $vars associative array with user variables
    *
    * @return Answer
     */
    public function replaceStars($question)
    {
        $questionPatterns = explode(' ', $question);
        $rawAnswer = $this->getXMLBuffer();

        $stars = [];

        foreach ($this->patternTokens as $key => $token) {
            if ($token === '*' && !empty($questionPatterns[$key])) {
                $stars[] = $questionPatterns[$key];
            }
        }

        $rawXML = $rawAnswer->asXML();
        $collection = $rawAnswer->children();

        foreach ($collection as $key => $node) {
            if ($node->getName() !== 'star') {
                continue;
            }

            $starIndex = $key;
            foreach ($node->attributes() as $attribute => $value) {
                if ($attribute !== 'index') {
                    continue;
                }

                $starIndex = $value - 1;
            }

            $nodeRawXML = $node->asXML();
            if (!empty($stars[$starIndex])) {
                $rawXML = str_replace($nodeRawXML, $stars[$starIndex], $rawXML);                
            }
        }
        $this->rawXMLBuffer = new SimpleXMLElement($rawXML);
        return trim(strval($this->rawXMLBuffer));
    }
}
