<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\V1\StoreEmployeeRequest;
use App\Http\Requests\V1\UpdateEmployeeRequest;
use App\Http\Resources\V1\EmployeeCollection;
use App\Models\Employee;
use App\Traits\ControllerTraits;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{

    use ControllerTraits;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate([
            'search' => ['string'],
            'perPage' => ['integer'],
        ]);

        $returnMessage = [];

        try {
            $query = Employee::query();

            if ($request->has('search')) {
                $query->where('first_name', 'like', '%' . $request->search . '%')
                    ->orWhere('middle_name', 'like', '%' . $request->search . '%')
                    ->orWhere('last_name', 'like', '%' . $request->search . '%')
                    ->orWhere('middle_name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('phone', 'like', '%' . $request->search . '%')
                    ->orWhere('job_title', 'like', '%' . $request->search . '%')
                    ->orWhere('department', 'like', '%' . $request->search . '%')
                    ->orWhere('designation', 'like', '%' . $request->search . '%');
            }

            $employees = $query->paginate($request->perPage ?? $this->perPageLimit());

            if (!$employees) {
                throw new \Exception('No employees found.');
            }
            $employeeCollection = new EmployeeCollection($employees);

            $returnMessage = [
                'success' => true,
                'message' => 'Employees retrieved successfully.',
                'data' => [
                    'requests' => $employeeCollection,
                    'pagination' => [
                        'currentPage' => $employees->currentPage(),
                        'perPage' => $employees->perPage(),
                        'total' => $employees->total(),
                        'lastPage' => $employees->lastPage(),
                        'nextPageUrl' => $employees->nextPageUrl(),
                        'prevPageUrl' => $employees->previousPageUrl(),
                    ]
                ],
            ];
        } catch (\Throwable $throwable) {
            $returnMessage = [
                'success' => false,
                'message' => 'Error occurred while fetching employees',
                'data' => ''
            ];

            if ($this->debuggable()) {
                $returnMessage['debug'] = $throwable->getMessage();
            }
        }

        return response()->json($returnMessage, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEmployeeRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        //
    }
}
