<?php

namespace A2Design\AIML;

use A2Design\AIML\Adapters\FileAdapter;
use A2Design\AIML\Error\Dictionary\NotFoundException;
use A2Design\AIML\Error\AdapterNotFoundException;
use A2Design\AIML\Contracts\Adapter;

class AdapterFactory {



    static protected $providers = [
        'file' => '\A2Design\AIML\Adapters\FileAdapter',
    ];

    /**
     * Returns adapter object by provided string
     *
     * @param  string $aiml dictionary string
     *
     * @throws NotFoundException
     *
     * @return Adapter
     */
    public function getAdapter($aiml, $type = 'file')
    {
        $adapter = $this->getByType($type);

        if (!$adapter->checkExists($aiml)) {
            throw new NotFoundException;
        }

        $adapter->setAIML($aiml);
        return $adapter;
    }


    /**
     * Returns new class instance by provided type
     *
     * @param  string $type internal adapter type
     *
     * @throws AdapterNotFoundException
     *
     * @return Adapter
     */
    protected function getByType($type)
    {
        if (!isset(self::$providers[$type])) {
            throw new AdapterNotFoundException;
        }

        return new self::$providers[$type];
    }
}
