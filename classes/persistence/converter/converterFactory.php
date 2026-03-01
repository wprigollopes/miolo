<?php

class ConverterFactory
{
    public static $trivialConverter;

    public function converterFactory()
    {
    }

    public function getConverter($className, $properties = null)
    {
        $MIOLO = MIOLO::getInstance();

        $MIOLO->uses("persistence/converter/" . lcfirst($className) . ".php");
        $converter = new $className();
        $converter->init($properties);
        return $converter;
    }

    public function getTrivialConverter()
    {
        if (!self::$trivialConverter) {
            self::$trivialConverter = new TrivialConverter();
        }

        return self::$trivialConverter;
    }
}
