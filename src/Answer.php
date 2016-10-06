<?php

namespace A2Design\AIML;

use SimpleXMLElement;

class Answer {

    protected $_rawAnswer = null;
    protected $answer = "";

    public function __construct(SimpleXMLElement $rawAnswer)
    {
        $this->_rawAnswer = $rawAnswer;
        $this->answer = strval($rawAnswer);
    }

    public function getContent()
    {
        return $this->answer;
    }

    public function replaceUserVars($vars)
    {
        $this->_replaceVars('get', $vars);
        return $this;
    }

    public function replaceChatVars($vars)
    {
        $this->_replaceVars('bot', $vars);
        return $this;
    }

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
