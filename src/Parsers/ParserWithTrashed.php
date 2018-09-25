<?php

namespace Zcwilt\Api\Parsers;

use Illuminate\Database\Eloquent\Builder;
use Zcwilt\Api\Exceptions\ApiException;

class ParserWithTrashed extends ParserAbstract
{
    public function tokenizeParameters(string $parameters)
    {
        $this->tokenized[] = '';
    }

    public function prepareQuery(Builder $eloquentBuilder): Builder
    {
        try {
            $eloquentBuilder = $eloquentBuilder->withTrashed();
        } catch (\BadMethodCallException $e) {
            throw new ApiException('Model does not support soft deletes');
        }
        return $eloquentBuilder;
    }
}
