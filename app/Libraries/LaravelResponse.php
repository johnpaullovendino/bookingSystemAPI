<?php

namespace App\Libraries;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Yajra\DataTables\DataTables;

class LaravelResponse
{
    public function json(array $data): JsonResponse
    {
        return response()->json($data);
    }

    public function dataTable(Collection $data)
    {
        return DataTables::of($data)->toJson();
    }
}
