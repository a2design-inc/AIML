<?php

namespace A2Design\AIML;

use A2Design\AIML\Adapters\FileAdapter;

class AdapterFactory {

    public function getAdapter($aiml)
    {
        if (file_exists($aiml)) {
            $adapter = new FileAdapter;
            $adapter->setAIML($aiml);
            return $adapter;
        }
    }
}
