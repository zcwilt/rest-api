<?php

namespace Zcwilt\Api\Parsers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\RelationNotFoundException;
use Zcwilt\Api\Exceptions\InvalidParserException;

class ParserIncludes extends ParserAbstract
{
    public function tokenizeParameters(string $parameters)
    {
        if (trim($parameters) === '') {
            throw new InvalidParserException("columns parser - invalid parameters");
        }
        $parameters = array_map('trim', explode(',', $parameters));
        foreach ($parameters as $field) {
            $this->tokenized[] = ['field' => $field];
        }
    }

    public function prepareQuery(Builder $eloquentBuilder): Builder
    {
        foreach ($this->tokenized as $parameters) {
            $field = $parameters['field'];
            $eloquentBuilder = $eloquentBuilder->with($field);
        }
        return $eloquentBuilder;
    }
}
