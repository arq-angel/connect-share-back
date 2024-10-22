<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\FacilityResource;
use App\Http\Resources\V1\FacilityShowResource;
use App\Models\Facility;
use App\Traits\ControllerTraits;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class FacilityController extends Controller
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
            $query = Facility::query();

            if ($request->has('search')) {
                $query->where('name', 'like', '%' . $request->search . '%');
            }

            $facilities = $query->orderBy('name', 'ASC')->paginate($request->perPage ?? $this->perPageLimit());

            if (!$facilities) {
                throw new \Exception('No facilities found.');
            }

            $facilityCollection = FacilityResource::collection($facilities);

            $this->returnMessage = [
                'success' => true,
                'message' => 'Facilities retrieved successfully.',
                'data' => [
                    'requests' => $facilityCollection,
                    'pagination' => [
                        'currentPage' => $facilities->currentPage(),
                        'perPage' => $facilities->perPage(),
                        'total' => $facilities->total(),
                        'lastPage' => $facilities->lastPage(),
                        'nextPage' => $facilities->currentPage() < $facilities->lastPage() ? $facilities->currentPage() + 1 : null,
                        'prevPage' => $facilities->currentPage() > 1 ? $facilities->currentPage() - 1 : null,
                    ],
                    'queryParams' => [
                        'search' => $request->query('search') ?? null,
                        'perPage' => $request->query('perPage') ?? null,
                        'page' => $facilities->currentPage(),
                    ],
                ],
            ];

            $this->returnMessageStatus = Response::HTTP_OK;

        } catch (\Throwable $throwable) {
            $this->returnMessage = [
                'success' => false,
                'message' => 'Error occurred while fetching facilities',
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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, Request $request)
    {
        $request->validate([
            'departmentId' => ['nullable', 'integer'],
            'jobTitleId' => ['nullable', 'integer'],
        ]);

        try {
            // retrieve the facility where the assignments contain the facility id as well as the requested departmentId or jobTitleId
            $facility = Facility::with([
                'assignments' => function ($query) use ($request) {
                    if ($request->has('departmentId')) {
                        $query->where('department_id', $request->departmentId);
                    }
                    if ($request->has('jobTitleId')) {
                        $query->where('job_title_id', $request->jobTitleId);
                    }
                },
            ])
                ->where('id', $id)
                ->firstOrFail();

            // Paginate the assignments after retrieving them using the facility we retrieved earlier eloquent relationship
            // but also need to filter the employee assignments for departmentId or jobTitleId
            // we only retrieve the employeeAssignments here
            $employeeAssignments = $facility->assignments()
                ->when($request->has('departmentId'), function ($query) use ($request) {
                    return $query->where('department_id', $request->departmentId);
                })
                ->when($request->has('jobTitleId'), function ($query) use ($request) {
                    return $query->where('job_title_id', $request->jobTitleId);
                })
                ->paginate($request->perPage ?? $this->perPageLimit());

            // Extract unique departments and job titles separately to populate sorting dropdown
            // commented code returns only selected department and jobTitles available in the selected department - maybe in the future implementation
            // but the current implementation suffices for now
//            $departments = $facility->assignments->pluck('department')->sortBy('name')->unique('id')->map(function ($department) {
            $departments = Facility::findOrFail($id)->assignments->pluck('department')->sortBy('name')->unique('id')->map(function ($department) {
                return [
                    'id' => $department->id,
                    'name' => $department->name,
                    'short_name' => $department->short_name,
                ];
            })->keyBy('id');
//            $jobTitles = $facility->assignments->pluck('jobTitle')->sortBy('title')->unique('id')->map(function ($jobTitle) {
            $jobTitles = Facility::findOrFail($id)->assignments->pluck('jobTitle')->sortBy('title')->unique('id')->map(function ($jobTitle) {
                return [
                    'id' => $jobTitle->id,
                    'name' => $jobTitle->title, // Assuming title is the name for job titles
                    'short_name' => $jobTitle->short_title,
                ];
            })->keyBy('id');

            // the resource will retrieve the employee, department and jobTitle details
            $employeeCollection = FacilityShowResource::collection($employeeAssignments);

            $this->returnMessage = [
                'success' => true,
                'message' => 'Employee assignments retrieved successfully.',
                'data' => [
                    'requests' => $employeeCollection,
                    'departments' => $departments,
                    'jobTitles' => $jobTitles,
                    'pagination' => [
                        'currentPage' => $employeeAssignments->currentPage(),
                        'perPage' => $employeeAssignments->perPage(),
                        'total' => $employeeAssignments->total(),
                        'lastPage' => $employeeAssignments->lastPage(),
                        'nextPage' => $employeeAssignments->currentPage() < $employeeAssignments->lastPage() ? $employeeAssignments->currentPage() + 1 : null,
                        'prevPage' => $employeeAssignments->currentPage() > 1 ? $employeeAssignments->currentPage() - 1 : null,
                    ],
                    'queryParams' => [
                        'search' => $request->query('search') ?? null,
                        'perPage' => $request->query('perPage') ?? null,
                        'page' => $employeeAssignments->currentPage(),
                    ],
                ],
            ];
            $this->returnMessageStatus = Response::HTTP_OK;
        } catch (\Throwable $throwable) {
            $this->returnMessage = [
                'success' => false,
                'message' => 'Error occurred while fetching employee assignments.',
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
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
