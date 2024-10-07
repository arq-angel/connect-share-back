@extends("admin.layouts.layout")

@section("content")
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ route("admin.assignment.index") }}" class="btn btn-icon"><i
                        class="fas fa-arrow-left"></i></a>
            </div>
            <h1>Employee Assignment</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Create Employee Assignment</h4>
                        </div>
                        <div class="card-body">

                            <form action="{{ route("admin.assignment.store") }}" enctype="multipart/form-data"
                                  method="POST">
                                @csrf

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Company</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input class="form-control" value="{{$company->name}}" disabled>
                                        <input type="hidden" name="company_id" value="{{ $company->id }}">
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label
                                        class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Facility</label>
                                    <div class="col-sm-12 col-md-7">
                                        <select class="form-control selectric" name="facility_id" id="facilitySelect">
                                            <option>Select</option>
                                            @foreach($facilities as $facility)
                                                <option value="{{ $facility->id }}"
                                                        @if(old('facility_id') == $facility->id)
                                                            selected
                                                        @endif
                                                >
                                                    {{ $facility->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <input type="number" value="{{ old('facility_id') }}" id="old-selected-facility" disabled hidden>

                                <div class="form-group row mb-4">
                                    <label
                                        class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Department</label>
                                    <div class="col-sm-12 col-md-7">
                                        <select class="form-control selectric" name="department_id"
                                                id="departmentSelect">
                                            <option id="default-department-select" disabled>Select Facility to select
                                                Department
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <input type="number" value="{{ old('department_id') }}" id="old-selected-department" disabled hidden>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Job
                                        Title</label>
                                    <div class="col-sm-12 col-md-7">
                                        <select class="form-control selectric" name="job_title_id" id="jobTitleSelect">
                                            <option id="default-job-title-select" disabled>Select Department to select
                                                Job Title
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <input type="number" value="{{ old('job_title_id') }}" id="old-selected-jobTitle" disabled hidden>

                                <div class="form-group row mb-4">
                                    <label
                                        class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Employee</label>
                                    <div class="col-sm-12 col-md-7">
                                        <select class="form-control selectric" name="employee_id" id="employeeSelect">
                                            <option>Select</option>
                                            @foreach($employees as $employee)
                                                <option
                                                    value="{{$employee->id}}"
                                                    @if($employee->id == old('employee_id'))
                                                        selected
                                                    @endif
                                                >
                                                    {{$employee->first_name.' '.$employee->middle_name.' '.$employee->last_name}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <input type="number" value="{{ old('employee_id') }}" id="old-selected-employee" disabled hidden>


                                <div class="d-none" id="employee-info-block">
                                    <div class="form-group row mb-4">
                                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Employee
                                            Data</label>
                                        <div class="col-sm-12 col-md-7" id="employee-info">

                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Start
                                        Date</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="date" name="start_date" class="form-control" value="{{ old('start_date') }}">
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">End
                                        Date</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="date" name="end_date" class="form-control" value="{{ old('end_date') }}">
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Contract
                                        Type</label>
                                    <div class="col-sm-12 col-md-7">
                                        <select class="form-control selectric" name="contract_type">
                                            <option>Select</option>
                                            @foreach($contractTypes as $type)
                                                <option value="{{ $type }}"
                                                        @if(old('contract_type') == $type)
                                                            selected
                                                    @endif
                                                >{{ ucfirst($type) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                                    <div class="col-sm-12 col-md-7">
                                        <button class="btn btn-primary">Create</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {

            let oldSelectedFacility = $('#old-selected-facility').val();
            let oldSelectedDepartment = $('#old-selected-department').val();
            let oldSelectedJobTitle = $('#old-selected-JobTitle').val();
            let oldSelectedEmployee = $('#old-selected-employee').val();

            console.log("Old Selected Facility: ", oldSelectedFacility);
            console.log("Old Selected Department: ", oldSelectedDepartment);
            console.log("Old Selected Job Title: ", oldSelectedJobTitle);
            console.log("Old Selected Employee: ", oldSelectedEmployee);

            let facilitySelect = $('#facilitySelect');
            let departmentFetchFunction = function () {
                let facilityId = facilitySelect.val();
                let departmentSelect = $('#departmentSelect');

                if (facilityId) {
                    $.ajax({
                        type: 'GET',
                        url: '{{ route("admin.ajax-department.create", ":id") }}'.replace(':id', facilityId),
                        dataType: 'json',
                        success: function (data) {
                            console.log('Response Data:', data);

                            // Clear the current department dropdown options
                            departmentSelect.empty();

                            // Check if the response was successful and if department data exists
                            if (data.success && data.data && data.data.departments && data.data.departments.length > 0) {
                                let departments = data.data.departments;

                                // Add default "Select" option at the top of the dropdown
                                departmentSelect.append('<option value="">Select</option>');

                                // Loop through the department data and append each as an option
                                $.each(departments, function (key, department) {

                                    let selected = '';

                                    console.log(department)
                                    console.log(oldSelectedDepartment)

                                    if (oldSelectedDepartment && (department.id == oldSelectedDepartment)) {
                                        selected = 'selected';
                                    }

                                    departmentSelect.append('<option value="' + department.id + '" ' + selected + '>' + department.name + '</option>');
                                });

                                console.log('Departments populated successfully:', departments);
                            } else {
                                // Log an error message if no departments were found
                                console.log('No departments found or error in response.');

                                // Reset the dropdown with a message indicating no departments are available
                                departmentSelect.append('<option value="">No departments available</option>');
                            }

                            // Refresh the selectric dropdown to reflect new options
                            departmentSelect.selectric('refresh');
                        },
                        error: function (xhr, status, error) {
                            console.log('Departments fetch error: ' + error);

                            // Reset the dropdown in case of error
                            departmentSelect.empty().append('<option value="">No departments available</option>').selectric('refresh');
                        }
                    });
                } else {
                    console.log('No facility ID selected.');

                    // Reset the dropdown if no facility is selected
                    departmentSelect.empty().append('<option value="">Select Facility to select Department</option>').selectric('refresh');
                }
            };
            if (oldSelectedDepartment) {
                departmentFetchFunction();
            }
            facilitySelect.on('change', function () {
                departmentFetchFunction();
            });

            let departmentSelect = $('#departmentSelect');
            let jobTitleFetchFunction = function () {
                let departmentId = departmentSelect.val();
                let jobTitleSelect = $('#jobTitleSelect');

                if (departmentId) {
                    $.ajax({
                        type: 'GET',
                        url: '{{ route("admin.ajax-job-title.create", ":id") }}'.replace(':id', departmentId),
                        dataType: 'json',
                        success: function (data) {
                            console.log('Response Data:', data);

                            // Clear the current department dropdown options
                            jobTitleSelect.empty();

                            // Check if the response was successful and if department data exists
                            if (data.success && data.data && data.data.jobTitles && data.data.jobTitles.length > 0) {
                                let jobTitles = data.data.jobTitles;

                                // Add default "Select" option at the top of the dropdown
                                jobTitleSelect.append('<option value="">Select</option>');

                                // Loop through the department data and append each as an option
                                $.each(jobTitles, function (key, jobTitle) {

                                    let selected = '';

                                    if (oldSelectedJobTitle && (jobTitle.id == oldSelectedJobTitle)) {
                                        selected = 'selected';
                                    }

                                    jobTitleSelect.append('<option value="' + jobTitle.id + '" ' + selected + '>' + jobTitle.title + '</option>');
                                });

                                console.log('Departments populated successfully:', jobTitles);
                            } else {
                                // Log an error message if no departments were found
                                console.log('No departments found or error in response.');

                                // Reset the dropdown with a message indicating no departments are available
                                jobTitleSelect.append('<option value="">No job titles available</option>');
                            }

                            // Refresh the selectric dropdown to reflect new options
                            jobTitleSelect.selectric('refresh');
                        },
                        error: function (xhr, status, error) {
                            console.log('Job Titles fetch error: ' + error);

                            // Reset the dropdown in case of error
                            jobTitleSelect.empty().append('<option value="">No job titles available</option>').selectric('refresh');
                        }
                    });
                } else {
                    console.log('No Department ID selected.');

                    // Reset the dropdown if no facility is selected
                    jobTitleSelect.empty().append('<option value="">Select Department to select Job Title</option>').selectric('refresh');
                }
            }
            if (oldSelectedJobTitle) {
                jobTitleFetchFunction();
            }
            departmentSelect.on('change', function () {
                jobTitleFetchFunction();
            });

            let employeeSelect = $('#employeeSelect');
            let employeeFetchFunction = function (employeeId) {
                $('#employee-info-block').addClass('d-none');
                if (employeeId) {
                    $.ajax({
                        type: 'GET',
                        url: '{{ route("admin.ajax-employee.create", ":id") }}'.replace(':id', employeeId),
                        dataType: 'json',
                        success: function (data) {
                            console.log('Response Data:', data);

                            if (data.success && data.data.employee) {
                                $('#employee-info-block').removeClass('d-none').addClass('d-block');
                                let employee = data.data.employee;
                                let employeeInfoHtml = `
                                    <div style="display: flex; align-items: center;">
                                            <div style="flex-shrink: 0; margin-right: 15px;">
                                                <img src="${employee.imageUrl}" alt="${employee.firstName} ${employee.middleName} ${employee.lastName}" style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%;">
                                            </div>
                                            <div>
                                                <p><strong>Name:</strong> ${employee.firstName} ${employee.middleName} ${employee.lastName}</p>
                                                <p><strong>Email:</strong> ${employee.email}</p>
                                                <p><strong>Phone:</strong> ${employee.phone}</p>
                                            </div>
                                    </div>
                            `;
                                $('#employee-info').html(employeeInfoHtml);
                            } else {
                                console.log('Error displaying the employee information.')
                            }

                        },
                        error: function (xhr, status, error) {
                            console.log('Employee data fetch error: ' + error)
                        }
                    })
                } else {
                    console.log('Cannot get the employee ID')
                }
            }
            if (oldSelectedEmployee) {
                employeeFetchFunction(oldSelectedEmployee);
            }
            employeeSelect.on('change', function () {
                let employeeId = employeeSelect.val();
                employeeFetchFunction(employeeId);
            });
        });
    </script>
@endpush
