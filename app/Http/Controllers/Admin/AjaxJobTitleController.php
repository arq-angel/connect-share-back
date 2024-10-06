<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\AjaxJobTitleCollection;
use App\Models\Department;
use App\Models\JobTitle;
use Illuminate\Http\Request;

class AjaxJobTitleController extends Controller
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
            $department = Department::findOrFail($id);

            $this->returnData =
                [
                    'success' => true,
                    'message' => 'Departments retrieved successfully.',
                    'data' => [
                        'jobTitles' => new AjaxJobTitleCollection($department->jobTitles),
                    ]
                ];
            $this->returnDataStatusCode = 200;
        } catch (\Throwable $th) {
            $this->returnDataStatusCode = 500;
        }

        return response()->json($this->returnData, $this->returnDataStatusCode);
    }
}
