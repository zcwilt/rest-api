<?php

namespace Zcwilt\Api\Parsers;

use Zcwilt\Api\Exceptions\InvalidParserException;

abstract class ParserWhereInAbstract extends ParserAbstract
{
    public function tokenizeParameters(string $parameters)
    {
        if (trim($parameters) === '') {
            throw new InvalidParserException("whereIn parser - invalid parameters");
        }
        $parameters = array_map('trim', explode(':', $parameters));
        if (count($parameters) !== 2) {
            throw new InvalidParserException("whereIn parser - invalid parameters");
        }
        $this->tokenized['col'] = $parameters[0];
        $this->tokenized['in'] = array_map('trim', explode(',', str_replace(['(', ')'], '', $parameters[1])));
    }
}
