<?php

namespace App\Traits;

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