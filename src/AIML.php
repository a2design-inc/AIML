<?php

namespace A2Design\AIML;

use A2Design\AIML\Answer;
use A2Design\AIML\AdapterFactory;

class AIML {

    protected $dictionaries = [];
    protected $chatInfo = [];
    protected $userInfo = [];

    public function __construct()
    {
        $this->adapterFactory = new AdapterFactory();
    }

    /**
     * Updates chat info
     *
     * @param array $info chat info for <bot /> tags
     */
    public function setChatInfo($info)
    {
        $this->chatInfo = array_merge($this->chatInfo, $info);
    }


    /**
     * Updates user info
     *
     * @param array $info chat info for <get /> tags
     */
    public function setUserInfo($info)
    {
        $this->userInfo = array_merge($this->userInfo, $info);
    }


    /**
     * Returns array of chat data
     *
     * @return array
     */
    public function getChatData()
    {
        return $this->chatInfo;
    }

    /**
     * Returns array of user data
     *
     * @return array
     */
    public function getUserData()
    {
        return $this->userInfo;
    }


    /**
     * Add dictionary to chat object
     *
     * @param string $dictionary
     * @param string $type
     *
     * @return \A2Design\AIML\Contracts\Adapter
     */
    public function addDict($dictionary, $type = 'file')
    {
        return $this->_checkDictionary($dictionary, $type);
    }


    /**
     * Returns answer by provided AIML question pattern
     *
     * @param  string $question AIML question pattern
     *
     * @return string                answer content
     */
    public function getAnswer($question)
    {
        $bestMatch = $this->_searchInDicts($question);

        if (empty($bestMatch)) {
            return '...';
        }

        return $bestMatch->getContent($question, $this->getChatData(), $this->getUserData());
    }


    /**
     * Search answer in attached dictionaries
     *
     * @param  satring $question AIML question pattern
     *
     * @return Answer
     */
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


    /**
     * Converts dictionary string to Adpater object
     *
     * @param  string $dictionary dictionary string
     *
     * @return void
     */
    protected function _checkDictionary($dictionary, $type = 'file')
    {
        $this->dictionaries[$dictionary] = $this->adapterFactory
            ->getAdapter($dictionary, $type);
        return $this->dictionaries[$dictionary];
    }
}
