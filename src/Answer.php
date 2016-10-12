<?php

namespace A2Design\AIML;

use SimpleXMLElement;
use A2Design\AIML\Utils\Collection;

class Answer {

    protected $_rawAnswer = null;
    protected $answer = "";
    protected $pattern = "";
    protected $patternTokens = [];
    protected $conversationNodes = null;

    public function __construct(SimpleXMLElement $rawAnswer, $pattern)
    {
        $this->_rawAnswer = $rawAnswer;
        $this->answer = strval(trim($rawAnswer));
        $this->pattern = strval($pattern);
        $this->patternTokens = explode(' ', $pattern);
        $this->conversationNodes = new Collection();
    }


    /**
     * Adds conversation node to answer
     *
     * @param Answer $answer conversation node linked by <that>
     */
    public function addConverstionNode(Answer $answer)
    {
        $this->conversationNodes[$answer->getPattern()] = $answer;
    }


    /**
     * Returns conversation node by provided question
     *
     * @param  string $question conversation pattern
     * @return mixed
     */
    public function getConversation($question)
    {
        return $this->conversationNodes[$question];
    }

    /**
     * Checks that answer has any conversation nodes
     *
     * @return boolean
     */
    public function hasConversation()
    {
        return count($this->conversationNodes) > 0;
    }


    /**
     * Returns answer matching pattern
     *
     * @return string
     */
    public function getPattern()
    {
        return $this->pattern;
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
    public function getContent()
    {
        return $this->answer;
    }


    /**
     * Replaces <get /> tags by provided user info
     *
     * @param  array $vars associative array with user variables
     *
     * @return Answer
     */
    public function replaceUserVars($vars)
    {
        $this->_replaceVars('get', $vars);
        return $this;
    }


    /**
    * Replaces <bot /> tags by provided user info
    *
    * @param  array $vars associative array with chat variables
    *
    * @return Answer
     */
    public function replaceChatVars($vars)
    {
        $this->_replaceVars('bot', $vars);
        return $this;
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

        $stars = [];

        foreach ($this->patternTokens as $key => $token) {
            if ($token === '*') {
                $stars[] = $questionPatterns[$key];
            }
        }

        $rawXML = $this->_rawAnswer->asXML();
        $collection = $this->_rawAnswer->children();

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
            $rawXML = str_replace($nodeRawXML, $stars[$starIndex], $rawXML);
        }
        return trim(strval(new SimpleXMLElement($rawXML)));
    }


    /**
     * Replaces provided tags by provided data
     *
     * @param  string $selector AIML elemnt name
     * @param  array  $vars     associative array of data
     * 
     * @return string
     */
    protected function _replaceVars($selector, $vars)
    {
        $collection = $this->_rawAnswer->children();
        $rawXML = $this->_rawAnswer->asXML();

        foreach ($collection as $node) {
            if ($node->getName() !== $selector) {
                continue;
            }

            foreach ($node->attributes() as $attribute => $value) {
                if ($attribute !== 'name') {
                    continue;
                }
                if (empty($vars[strval($value)])) {
                    continue;
                }

                $nodeRawXML = $node->asXML();
                $rawXML = str_replace($nodeRawXML, $vars[strval($value)], $rawXML);
            }
        }
        $this->answer = strval(new SimpleXMLElement($rawXML));
        return $this->answer;
    }
}
