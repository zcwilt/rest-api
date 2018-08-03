<?php

namespace Zcwilt\Api;

use Zcwilt\Api\Exceptions\InvalidParserException;

class ParserFactory
{
    public function getParser(string $method)
    {
        $classname = __NAMESPACE__ . '\\Parsers\\' . 'Parser' . ucfirst($method);
        if (!class_exists($classname)) {
            throw new InvalidParserException("Can't find parser class");
        }
        $class = new $classname();
        return $class;
    }
}