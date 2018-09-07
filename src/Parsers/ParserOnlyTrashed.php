<?php

namespace Zcwilt\Api\Parsers;

use Illuminate\Database\Eloquent\Builder;

class ParserOnlyTrashed extends ParserAbstract
{
    public function tokenizeParameters(string $parameters)
    {
        $this->tokenized[] = '';
    }

    public function prepareQuery(Builder $eloquentBuilder): Builder
    {
        $eloquentBuilder = $eloquentBuilder->onlyTrashed();
        return $eloquentBuilder;
    }
}
