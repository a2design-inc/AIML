<?php

namespace A2Design\AIML\Contracts;

use SimpleXMLElement;
use A2Design\AIML\AIML;

abstract class BaseAnswer {

    protected $answerBuffer = null;
    protected $rawXMLBuffer = null;
    /**
     * Sets chat instance to Answer
     *
     * @param AIML $chat chat instance
     */
    public function setChatInstance(AIML $chat)
    {
        $this->chat = $chat;
    }


    /**
     * Adds conversation node to answer
     *
     * @param Answer $answer conversation node linked by <that>
     */
    public function addConverstionNode(BaseAnswer $answer)
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
     * Replaces <get /> tags by provided user info
     *
     * @param  array $vars associative array with user variables
     *
     * @return Answer
     */
    public function replaceUserVars($vars = [])
    {
        $data = $vars;
        if (empty($vars) && !empty($this->chat)) {
            $data = $this->chat->getUserData();
        }
        return $this->_replaceVars('get', $data);
    }


    /**
    * Replaces <bot /> tags by provided user info
    *
    * @param  array $vars associative array with chat variables
    *
    * @return Answer
     */
    public function replaceChatVars($vars = [])
    {
        $data = $vars;
        if (empty($vars) && !empty($this->chat)) {
            $data = $this->chat->getChatData();
        }
        return $this->_replaceVars('bot', $data);
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
        $rawAnswer = $this->getXMLBuffer();
        $collection = $rawAnswer->children();
        $rawXML = $rawAnswer->asXML();

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
        $this->rawXMLBuffer = new SimpleXMLElement($rawXML);
        $answer = strval($this->rawXMLBuffer);
        return $answer;
    }

    /**
     * Returns compiled answer buffer
     *
     * @return string
     */
    protected function convertBufferToString()
    {
        $buffer = $this->getXMLBuffer();
        return strval(trim($buffer));
    }

    /**
     * Clears all buffers
     *
     * @return void
     */
    public function clearBuffers()
    {
        $this->answerBuffer = null;
        $this->rawXMLBuffer = null;
    }


    /**
     * Returns XML buffer object
     *
     * @return SimpleXMLElement
     */
    protected function getXMLBuffer()
    {
        if (empty($this->rawXMLBuffer)) {
            $this->rawXMLBuffer = $this->_rawAnswer;
        }
        return $this->rawXMLBuffer;
    }

    /**
     * Returns answer buffer
     *
     * @return string
     */
    protected function getAnswerBuffer()
    {
        if (empty($this->answerBuffer)) {
            $this->answerBuffer = $this->answer;
        }
        return $this->answerBuffer;
    }

    abstract public function match($question);
    abstract public function getContent($question);
    abstract public function replaceStars($question);
}
