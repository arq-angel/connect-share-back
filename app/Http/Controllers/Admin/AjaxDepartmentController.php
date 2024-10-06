<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\AjaxDepartmentCollection;
use App\Models\Department;
use App\Models\Facility;
use Illuminate\Http\Request;

class AjaxDepartmentController extends Controller
{
    private $returnData = [
        'success' => false,
        'message' => 'Error retrieving data',
        'data' => []
    ];

    private $returnDataStatusCode = 400;

    public function create(string $id)
    {
        try {
            $facility = Facility::findOrFail($id);


            $this->returnData =
                [
                    'success' => true,
                    'message' => 'Departments retrieved successfully.',
                    'data' => [
                        'departments' => new AjaxDepartmentCollection($facility->departments),
                    ]
                ];
            $this->returnDataStatusCode = 200;
        } catch (\Throwable $th) {
            $this->returnDataStatusCode = 500;
        }

        return response()->json($this->returnData, $this->returnDataStatusCode);
    }

}
