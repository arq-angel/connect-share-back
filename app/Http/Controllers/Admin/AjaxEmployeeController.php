<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\AjaxEmployeeResource;
use App\Models\Employee;
use Illuminate\Http\Request;

class AjaxEmployeeController extends Controller
{

    private $returnData = [
        'success' => false,
        'message' => 'Error retrieving data',
        'data' => []
    ];

    private $returnDataStatusCode = 400;

    public function show(string $id)
    {
        try {
            $employee = Employee::findOrFail($id);
            $this->returnData =
                [
                    'success' => true,
                    'message' => 'Employee data retrieved successfully.',
                    'data' => [
                        'employee' => new AjaxEmployeeResource($employee),
                    ]
                ];
            $this->returnDataStatusCode = 200;
        } catch (\Throwable $th) {
            $this->returnDataStatusCode = 500;
        }

        return response()->json($this->returnData, $this->returnDataStatusCode);
    }
}
