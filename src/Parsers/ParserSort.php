<?php

namespace Zcwilt\Api\Parsers;

use Zcwilt\Api\Exceptions\InvalidParserException;
use Illuminate\Database\Eloquent\Builder;

class ParserSort extends ParserAbstract
{

    public function tokenizeParameters(string $parameters)
    {
        if (trim($parameters) === '') {
            throw new InvalidParserException("sort parser - invalid parameters");
        }
        $parameters = array_map('trim', explode(',', $parameters));
        foreach ($parameters as $field) {
            $sortDirection = 'ASC';
            if (isset($field[0]) && $field[0] == '-') {
                $sortDirection = 'DESC';
                $field = substr($field, 1);
            }
            $this->tokenized[] = ['field' => $field, 'direction' => $sortDirection];
        }
    }

    public function prepareQuery(Builder $eloquentBuilder)
    {
        foreach ($this->tokenized as $parameters) {
            $eloquentBuilder = $eloquentBuilder->orderBy($parameters['field'], $parameters['direction']);
        }
        return $eloquentBuilder;
    }
}
