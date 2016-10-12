<?php

namespace A2Design\AIML\Utils;

use A2Design\AIML\Utils\Contracts\BaseCollection;
use A2Design\AIML\Answer;

class Collection extends BaseCollection {

    protected $linksTree = [];


    /**
     * Returns element by provided phrase
     *
     * @param  string $offset AIML question pattern
     * @return Answer
     */
    protected function findElement($offset)
    {
        $hash = md5($offset);

        if (!empty($this->items[$hash])) {
            return $this->items[$hash];
        }

        if (!empty($this->items)) {
            $match = $this->searchForPattern($offset);
            if (!empty($match)) {
                return $match;
            }
        }

        while (!empty($this->linksTree[$hash])) {
            $hash = $this->linksTree[$hash];
        }

        if (empty($this->items[$hash])) {
            return null;
        }

        return $this->items[$hash];
    }


    /**
     * Searches element by AIML search criterias
     *
     * @param  string $question AIML question pattern
     *
     * @return Answer
     */
    protected function searchForPattern($question) {
        $match = null;
        $minScore = INF;

        foreach ($this->items as $answer) {
            $score = $answer->match($question);
            if ($score == -1) {
                continue;
            }
            if ($score >= 0 && $score <= $minScore) {
                $match = $answer;
                $minScore = $score;
            }
        }

        if (!empty($match)) {
            $this->updateIndex(md5($question), $match);
            return $match;
        }
    }


    /**
     * Updates index by provied data
     *
     * @param  string $offset offset for update
     * @param  Answer $match  update value
     *
     * @return void
     */
    protected function updateIndex($offset, $match)
    {
        $this->index[md5($offset)] = $match;
    }

    /**
     * Searches answer in collection index
     *
     * @param  string $offset AIML question pattern
     * @return Answer
     */
    protected function findInIndex($offset)
    {
        $hash = md5($offset);
        return isset($this->index[$hash]) ? $this->index[$hash] : null;
    }


    /**
     * Unsets offset from index
     *
     * @param string $offset offset for unset
     */
    protected function offsetUnsetIndex($offset)
    {
        unset($this->index[$offset]);
    }
}
