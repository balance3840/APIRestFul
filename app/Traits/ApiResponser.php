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
			return $this->successReponse($collection);
		}

	   $transformer = $collection->first()->transformer;
	   $collection = $this->transformData($collection, $transformer);
       return $this->successReponse(['data' => $collection], $code);
	}

	protected function showOne(Model $instance, $code = 200)
	{
       return $this->successReponse(['data' => $instance], $code);
	}

	protected function showMessage($message, $code = 200)
	{
       return $this->successReponse(['data' => $message], $code);
	}

	protected function transformData($data, $transformer)
	{
		$transformation = fractal($data, new $transformer);

		return $transformation->toArray();

	}
}