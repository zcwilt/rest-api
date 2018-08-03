<?php

namespace Zcwilt\Api\Parsers;

use Illuminate\Database\Eloquent\Builder;

Interface ParserInterface
{
    public function tokenizeParameters(string $parameters);
    public function prepareQuery(Builder $eloquentBuilder);
}
