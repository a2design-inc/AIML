<?php

namespace A2Design\AIML;

use A2Design\AIML\AdapterFactory;

class AIML {

    protected $dictionaries = [];
    protected $chatInfo = [];
    protected $userInfo = [];

    public function __construct()
    {
        $this->adapterFactory = new AdapterFactory();
    }

    public function setChatInfo($info)
    {
        $this->chatInfo = array_merge($this->chatInfo, $info);
    }

    public function setUserInfo($info)
    {
        $this->userInfo = array_merge($this->userInfo, $info);
    }

    public function addDict($dictionary)
    {
        $this->_checkDictionary($dictionary);
    }

    public function getAnswer($question)
    {
        $bestMatch = $this->_searchInDicts($question);

        if (empty($bestMatch)) {
            return '...';
        }

        $answer = $bestMatch
            ->replaceChatVars($this->chatInfo)
            ->replaceUserVars($this->userInfo);

        return $answer->getContent();
    }

    protected function _searchInDicts($question)
    {
        if (empty($this->dictionaries)) {
            return null;
        }

        foreach ($this->dictionaries as $dict)
        {
            $match = $dict->searchPattern($question);

            if (empty($match)) {
                continue;
            }

            return $match;
        }
    }

    protected function _checkDictionary($dictionary)
    {
        $this->dictionaries[$dictionary] = $this->adapterFactory
            ->getAdapter($dictionary);
    }
}
