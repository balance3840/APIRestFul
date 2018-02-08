<?php

namespace App\Traits;

use Illuminate\Support\Facades\Validator;
use Illuminate\Pagination\LengthAwarePaginator;

Use Illuminate\Support\Collection;
Use Illuminate\Database\Eloquent\Model;

trait ApiResponser
{
	private function successReponse($data, $code)
	{
       return response()->json($data, $code);
	}

	protected function errorResponse($message, $code, $headers = ['Content-Type' => 'application/json; charset=UTF-8',
            'charset' => 'utf-8'], $options = JSON_UNESCAPED_UNICODE)
	{
		return response()->json(['error' => $message, 'code' => $code], $code, $headers, $options);
	}

	protected function showAll(Collection $collection, $code = 200)
	{

		if ($collection->isEmpty()) {
			return $this->successReponse(['data' => $collection], $code);
		}

	   $transformer = $collection->first()->transformer;

	   $collection = $this->filterData($collection, $transformer);
	   $collection = $this->sortData($collection, $transformer);
	   $collection = $this->paginate($collection);
	   $collection = $this->transformData($collection, $transformer);
       return $this->successReponse($collection, $code);
	}

	protected function showOne(Model $instance, $code = 200)
	{
	   $transformer = $instance->transformer;	
	   $collection = $this->transformData($instance, $transformer);
       return $this->successReponse($collection, $code);
	}

	protected function showMessage($message, $code = 200)
	{
       return $this->successReponse(['data' => $message], $code);
	}

	protected function paginate(Collection $collection)
	{
		$rules = [
			'per_page' => 'integer|min:2|max:50'
		];

		Validator::validate(request()->all(), $rules);

		$page = LengthAwarePaginator::resolveCurrentPage();

		$perPage = 15;

		if (request()->has('per_page')) {
			$perPage = (int) request()->per_page;
		}

		$results = $collection->slice(($page - 1) * $perPage, $perPage)->values();

		$paginated = new LengthAwarePaginator($results, $collection->count(), $perPage, $page,  [
			'path' => LengthAwarePaginator::resolveCurrentPath(),
		]);

		$paginated->appends(request()->all());

		return $paginated;
	}

	protected function filterData(Collection $collection, $transformer)
	{
		foreach (request()->query() as $query => $value) {
			$attribute = $transformer::originalAttribute($query);

			if (isset($attribute, $value)) {
				$collection = $collection->where($attribute, $value);				
			}
		}

		return $collection;
	}

	protected function sortData(Collection $collection, $transformer)
	{
		if (request()->has('sort_by')){
			$attribute = $transformer::originalAttribute(request()->sort_by);
			$collection = $collection->sortBy->{$attribute};
		}
		return $collection;
	}

	protected function transformData($data, $transformer)
	{
		$transformation = fractal($data, new $transformer);

		return $transformation->toArray();

	}
}