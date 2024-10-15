<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreEmployeeRequest;
use App\Http\Requests\Api\V1\UpdateEmployeeRequest;
use App\Http\Resources\V1\EmployeeCollection;
use App\Http\Resources\V1\EmployeeShowResource;
use App\Models\Employee;
use App\Traits\ControllerTraits;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EmployeeController extends Controller
{

    use ControllerTraits;

    private array $returnMessage = [
        'success' => false,
        'message' => 'An error occurred',
        'data' => [],
    ];
    private int $returnMessageStatus = Response::HTTP_BAD_REQUEST;

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate([
            'search' => ['nullable', 'string'],
            'perPage' => ['nullable', 'integer'],
            'page' => ['nullable', 'integer'],
        ]);

        try {
            $query = Employee::query();

            if ($request->has('search')) {
                $query->where('first_name', 'like', '%' . $request->search . '%')
                    ->orWhere('middle_name', 'like', '%' . $request->search . '%')
                    ->orWhere('last_name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%')
                    ->orWhere('phone', 'like', '%' . $request->search . '%');
            }

            $employees = $query->orderBy('first_name', 'ASC')->paginate($request->perPage ?? $this->perPageLimit());

            if (!$employees) {
                throw new \Exception('No employees found.');
            }

            $employeeCollection = new EmployeeCollection($employees);

            $this->returnMessage = [
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
                    ],
                    'queryParams' => [
                        'search' => $request->query('search') ?? null,
                        'perPage' => $request->query('perPage') ?? null,
                        'page' => $employees->currentPage(),
                    ],
                ],
            ];

            $this->returnMessageStatus = Response::HTTP_OK;

        } catch (\Throwable $throwable) {
            $this->returnMessage = [
                'success' => false,
                'message' => 'Error occurred while fetching employees',
                'data' => []
            ];

            if ($this->debuggable()) {
                $this->returnMessage['debug'] = $throwable->getMessage();
            }

            $this->returnMessageStatus = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return response()->json($this->returnMessage, $this->returnMessageStatus);
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
    public function show(String $id)
    {
        try {
            $employee = Employee::findOrFail($id);

            $this->returnMessage = [
                'success' => true,
                'message' => 'Employee details retrieved successfully.',
                'data' => new EmployeeShowResource($employee),
            ];
            $this->returnMessageStatus = Response::HTTP_OK;
        } catch (\Throwable $throwable) {
            $this->returnMessage = [
                'success' => false,
                'message' => 'Error occurred while fetching profile details.',
                'data' => []
            ];

            if ($this->debuggable()) {
                $this->returnMessage['debug'] = $throwable->getMessage();
            }
            $this->returnMessageStatus = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return response()->json($this->returnMessage, $this->returnMessageStatus);


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
