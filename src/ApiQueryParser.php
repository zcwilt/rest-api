<?php

namespace Zcwilt\Api;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ApiQueryParser
{
    /*
     * @var ParserFactory
     *
     */
    protected $parserFactory;

    /*
     * @var array
     *
     */
    protected $queryParts;

    /*
     * @var array
     *
     */
    protected $parsedKeys;

    public function __construct(ParserFactory $parserFactory)
    {
        $this->parserFactory = $parserFactory;
        $this->queryParts = [];
    }

    public function parseRequest(Request $request): ApiQueryParser
    {
        $this->parsedKeys = [];
        if (count($request->query())) {
            $this->gatherKeys($request);
        }
        return $this;
    }

    public function buildParsers(): ApiQueryParser
    {
        $this->parseKeys();
        return $this;
    }

    public function buildQuery(Model $model): Builder
    {
        if (!isset($this->parsedKeys['columns'])) {
            $this->parsedKeys['columns'] = $model->getTable() . '.*';
        }
        $query = $model->query();
        $query = $this->buildRawQuery($query);
        return $query;
    }

    public function getQueryParts(): array
    {
        return $this->queryParts;
    }

    public function getParserFactory(): ParserFactory
    {
        return $this->parserFactory;
    }

    public function getParsedKeys(): array
    {
        return $this->parsedKeys;
    }

    protected function gatherKeys(Request $request)
    {
        foreach ($request->query() as $key => $value) {
            if ($key == 'page' || $key == 'limit') {
                continue;
            }
            $this->parsedKeys[$key] = $value;
        }
    }

    protected function parseKeys()
    {
        foreach ($this->parsedKeys as $action => $parameters) {
            $this->callParser($action, (string)$parameters);
        }
    }

    protected function callParser(string $action, string $parameters)
    {
        $parser = $this->parserFactory->getParser($action);
        $parser->parse($parameters);
        $this->queryParts[] = $parser;
    }

    protected function buildRawQuery(Builder $eloquentQB): Builder
    {
        foreach ($this->queryParts as $parser) {
            $eloquentQB = $parser->addQuery($eloquentQB);
        }
        return $eloquentQB;
    }
}
