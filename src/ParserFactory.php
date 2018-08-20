<?php

namespace Zcwilt\Api;

use Zcwilt\Api\Exceptions\InvalidParserException;
use Zcwilt\Api\Parsers\ParserInterface;

class ParserFactory
{
    public function getParser(string $method): ParserInterface
    {
        $classname = __NAMESPACE__ . '\\Parsers\\' . 'Parser' . ucfirst($method);
        if (!class_exists($classname)) {
            throw new InvalidParserException("Can't find parser class");
        }
        $class = new $classname();
        return $class;
    }
}
