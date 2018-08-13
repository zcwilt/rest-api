<?php

namespace Zcwilt\Api\Parsers;

use Zcwilt\Api\Exceptions\InvalidParserException;

abstract class ParserWhereBetweenAbstract extends ParserAbstract
{
    public function tokenizeParameters(string $parameters)
    {
        if (trim($parameters) === '') {
            throw new InvalidParserException("whereBetween parser - invalid parameters");
        }
        $parameters = array_map('trim', explode(':', $parameters));
        if (count($parameters) !== 3) {
            throw new InvalidParserException("whereBetween parser - invalid parameters");
        }
        $this->tokenized = $parameters;
    }
}
