<?php

namespace Zcwilt\Api\Parsers;

use Illuminate\Database\Eloquent\Builder;

abstract class ParserAbstract implements ParserInterface
{
    /**
     * @var array
     */
    protected $tokenized;

    public function __construct()
    {
        $this->tokenized = array();
    }

    public function parse(string $parameters)
    {
        $this->tokenizeParameters($parameters);
    }

    public function addQuery(Builder $eloquentBuilder)
    {
        return $this->prepareQuery($eloquentBuilder);
    }

    public function getTokenized()
    {
        return $this->tokenized;
    }
}
