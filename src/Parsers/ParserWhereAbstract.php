<?php

namespace Zcwilt\Api\Parsers;

use Zcwilt\Api\Exceptions\InvalidParserException;

abstract class ParserWhereAbstract extends ParserAbstract
{
    /**
     * @var array
     */
    protected $operatorMap = ['eq' => '=', 'noteq' => '!=', 'lte' => '<=', 'gte' => '>=', 'gt' => '>', 'lt' => '<'];

    public function tokenizeParameters(string $parameters)
    {
        if (trim($parameters) === '') {
            throw new InvalidParserException("where parser - invalid parameters");
        }
        $parameters = array_map('trim', explode(':', $parameters));
        if (count($parameters) !== 3) {
            throw new InvalidParserException("where parser - invalid parameters");
        }
        if (!array_key_exists($parameters[1], $this->operatorMap)) {
            throw new InvalidParserException("where parser - invalid parameters");
        }
        $this->tokenized = $parameters;
    }
}
