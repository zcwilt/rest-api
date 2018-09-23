<?php

namespace Zcwilt\Api\Parsers;

use Illuminate\Database\Eloquent\Builder;
use Zcwilt\Api\Exceptions\InvalidParserInvalidParameterException;
use Zcwilt\Api\Exceptions\InvalidParserParameterCountException;

class ParserJoin extends ParserAbstract
{
    /**
     * @var array
     */
    protected $operatorMap = [
        'inner', 'left', 'cross'
    ];

    public function tokenizeParameters(string $parameters)
    {
        $parameters = array_map('trim', explode(':', $parameters));
        if (count($parameters) !== 4) {
            throw new InvalidParserParameterCountException("join parser - requires 4 parameters only found " . count($parameters));
        }
        if (!in_array($parameters[0], $this->operatorMap)) {
            throw new InvalidParserInvalidParameterException("join parser - invalid join type " . $parameters[0]);
        }
        $this->tokenized = $parameters;
    }

    public function prepareQuery(Builder $eloquentBuilder): Builder
    {
        $tokenized = $this->tokenized;
        $eloquentBuilder = $eloquentBuilder->join($tokenized[1], $tokenized[2], '=', $tokenized[3], $tokenized[0]);
        return $eloquentBuilder;
    }
}
