<?php

namespace Axenso\Sso\Traits;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

trait ApiResponser
{
    private function successResponse($data, $code)
    {
        return response()->json($data, $code);
    }

    protected function errorResponse($message, $code)
    {
        return response()->json(['error' => $message, 'code' => $code], $code);
    }

    protected function showAll(Collection $collection, $code = 200,$perPage = null)
    {
        if ($code == null) {$code = 200 ;}
        if ($collection->isEmpty()) {
            return $this->successResponse( $collection, $code);
        }
        if ($perPage !== null) {
            $collection = $this->paginate($collection,$perPage);
        }
        return $this->successResponse($collection, $code);
    }
    
    protected function showOne(Model $instance, $code = 200)
    {
        return $this->successResponse($instance, $code);
    }
    protected function showMessage($message, $code = 200)
    {
        return $this->successResponse(['data' => $message, 'code' => $code], $code);
    }
    protected function showResource($array,$code = 200) {
        return response()->json(['data' => $array, 'code' => $code], $code);

    }
    protected function paginate($collection,$perPage = 15) {
        $page = LengthAwarePaginator::resolveCurrentPage();
        $results = $collection->slice(($page - 1) * $perPage, $perPage)->values();
        $paginated = new LengthAwarePaginator($results,$collection->count(),$perPage,$page,[
            'path' => LengthAwarePaginator::resolveCurrentPath(),
        ]);
        $paginated->appends(request()->all());
        return $paginated;
    }
}
