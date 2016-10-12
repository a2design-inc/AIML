<?php

namespace A2Design\AIML\Parsers\Partials;

use SimpleXMLElement;

class Category extends SimpleXMLElement {

    /**
     * Checks that category object has "<that>" node
     *
     * @return boolean
     */
    public function hasThat()
    {
        return !empty($this->that);
    }


    /**
     * Checks that category object has "<srai>" node
     *
     * @return boolean
     */
    public function hasSrai()
    {
        return !empty($this->srai);
    }

    /**
     * Returns cleaned XML content of <that> node
     *
     * @return string
     */
    public function getThat()
    {
        return $this->getElementContent($this->that);
    }


    /**
     * Returns cleaned XML content of <template> node
     *
     * @return string
     */
    public function getTemplate()
    {
        return $this->getElementContent($this->template);
    }

    /**
     * Returns cleaned XML content of provided category child node
     *
     * @param  SimpleXMLElement $element
     *
     * @access protected
     *
     * @return string
     */
    protected function getElementContent($element)
    {
        $tagName = $element->getName();
        $withoutTags = str_replace("<{$tagName}>", '', str_replace("</{$tagName}>", '', trim($element->asXML())));
        return trim($withoutTags);
    }
}
