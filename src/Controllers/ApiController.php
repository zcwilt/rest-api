<?php

namespace Zcwilt\Api\Controllers;

use Illuminate\Http\Request;
use Zcwilt\Api\ApiQueryParser;
use Zcwilt\Api\ParserFactory;
use Illuminate\Database\Eloquent\Model;
use Zcwilt\Api\ModelMakerFactory;
use Illuminate\Http\JsonResponse;
use Validator;
use Illuminate\Database\QueryException;

class ApiController extends AbstractApiController
{
    /**
     * @var
     */
    protected $model;

    public function __construct(
        ModelMakerFactory $modelMaker
    ) {
        $this->model = $modelMaker->make($this->modelName);
    }

    public function index(Request $request): jsonResponse
    {
        try {
            $parser = new ApiQueryParser(new ParserFactory());
            $query = $parser->parseRequest($request)->buildparsers()->buildQuery($this->model);
            $result = $query->paginate();
        } catch (\Exception $e) {
            return $this->setStatusCode(400)->respondWithError($e->getMessage());
        }
        return $this->respond($result->toArray());
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->loadRules());
        if ($validator->fails()) {
            return $this->setStatusCode(400)->respondWithError($validator->errors());
        }
        try {
            $result = $this->model->create($request->all());
        } catch (QueryException $e) {
            return $this->setStatusCode(400)->respondWithError($e->getMessage());
        }
        return $this->respond([
            'data' => $result
        ]);
    }

    public function show($id): jsonResponse
    {
        $result = $this->model->find($id);
        if (!$result) {
            return $this->setStatusCode(400)->respondWithError('item does not exist');
        }

        return $this->respond([
            'data' => $result->toArray()
        ]);
    }

    public function update($id, Request $request)
    {
        $result = $this->model->find($id);
        if (!$result) {
            return $this->setStatusCode(400)->respondWithError('item does not exist');
        }
        $validator = Validator::make($request->all(), $this->loadRules($id));
        if ($validator->fails()) {
            return $this->setStatusCode(400)->respondWithError($validator->errors());
        }

        //dd($request->all());
        $result->update($request->all());

        return $this->respond([
            'data' => $result->toArray()
        ]);
    }

    public function destroy($id): jsonResponse
    {
        $result = $this->model->find($id);
        if (!$result) {
            return $this->setStatusCode(400)->respondWithError('item does not exist');
        }
        $result->delete();
        return $this->respond([
            'data' => $result
        ]);
    }

    public function destroyByQuery(Request $request): jsonResponse
    {
        try {
            $parser = new ApiQueryParser(new ParserFactory());
            $query = $parser->parseRequest($request)->buildparsers()->buildQuery($this->model);
        } catch (\Exception $e) {
            return $this->setStatusCode(400)->respondWithError($e->getMessage());
        }
        $result=$query->get();
        $query->each(function ($record) {
            $record->delete();
        });
        return $this->respond([
            'data' => $result->toArray()
        ]);
    }

    protected function loadRules($id = 0)
    {
        if (method_exists($this->model, 'rules')) {
            return $this->model->rules($id);
        }
        return [];
    }
}
