<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\AjaxJobTitleCollection;
use App\Models\Department;
use App\Models\JobTitle;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AjaxJobTitleController extends Controller
{
    private $returnData = [
        'success' => false,
        'message' => 'Error retrieving data',
        'data' => []
    ];

    private $returnDataStatusCode = 400;

    public function create(string $id, Request $request)
    {
        try {
            $request->validate([
                'chart' => ['nullable', Rule::in(['true', 'false'])],
            ]);

            $department = Department::findOrFail($id);

            $activeJobTitles = $department->jobTitles()->where('status', 'active')->get();

            if ($request->has('chart') && $request->input('chart') === 'true') {
                $activeJobTitles = $department->jobTitles()
                    ->where('directory_flag', '=', true) // Filter by 'directory_flag'
                    ->where('status', 'active') // Still filter by 'active' status
                    ->get();
            }

            $this->returnData =
                [
                    'success' => true,
                    'message' => 'Job Titles retrieved successfully.',
                    'data' => [
                        'jobTitles' => new AjaxJobTitleCollection($activeJobTitles),
                    ]
                ];
            $this->returnDataStatusCode = 200;
        } catch (\Throwable $th) {
            $this->returnDataStatusCode = 500;
        }

        return response()->json($this->returnData, $this->returnDataStatusCode);
    }
}
