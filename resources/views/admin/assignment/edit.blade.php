@extends("admin.layouts.layout")

@section("content")
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ route("admin.assignment.index") }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h1>Employee Assignment</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Update Employee Assignment</h4>
                        </div>
                        <div class="card-body">

                            <form action="{{ route("admin.assignment.update", $assignment->id) }}" enctype="multipart/form-data"
                                  method="POST">
                                @csrf
                                @method('PUT')

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Company</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input class="form-control" value="{{$company->name}}" disabled>
                                        <input type="hidden" name="company_id" value="{{ $company->id }}">
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Facility</label>
                                    <div class="col-sm-12 col-md-7">
                                        <select class="form-control selectric" name="facility_id" id="facilitySelect">
                                            <option>Select</option>
                                            @foreach($facilities as $facility)
                                                <option value="{{ $facility->id }}"
                                                        @if($facility->id == $assignment->facility_id) selected @endif>
                                                    {{ $facility->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                    </div>
                                </div>



                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Department</label>
                                    <div class="col-sm-12 col-md-7">
                                        <select class="form-control selectric" name="department_id" id="departmentSelect">
                                            <option>Select Facility to select Department</option>
                                        </select>
                                    </div>
                                </div>


                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Job Title</label>
                                    <div class="col-sm-12 col-md-7">
                                        <select class="form-control selectric" name="job_title_id">
                                            <option>Select</option>
                                            @foreach($jobTitles as $jobTitle)
                                                <option value="{{$jobTitle->id}}">{{$jobTitle->title}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Employee</label>
                                    <div class="col-sm-12 col-md-7">
                                        <select class="form-control selectric" name="employee_id" id="employeeSelect">
                                            <option>Select</option>
                                            @foreach($employees as $employee)
                                                <option value="{{$employee->id}}">{{$employee->first_name.' '.$employee->middle_name.' '.$employee->last_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="d-none" id="employee-info-block">
                                    <div class="form-group row mb-4">
                                        <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Employee Data</label>
                                        <div class="col-sm-12 col-md-7" id="employee-info">

                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Start Date</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="date" name="start_date" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">End Date</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="date" name="end_date" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Contract Type</label>
                                    <div class="col-sm-12 col-md-7">
                                        <select class="form-control selectric" name="contract_type">
                                            <option>Select</option>
                                            @foreach($contractTypes as $type)
                                                <option value="{{ $type }}">{{ ucfirst($type) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3"></label>
                                    <div class="col-sm-12 col-md-7">
                                        <button class="btn btn-primary">Update</button>
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
        console.log("script loaded");
        $(document).ready(function() {

            console.log('Facility Selected: ', $('#facilitySelect').val());


            $('#facilitySelect').on('change', function() {
                let facilityId = $(this).val();
                if (facilityId) {
                    $.ajax({
                        type: 'GET',
                        url: '{{ route("admin.ajax-department.show", ":id") }}'.replace(':id', facilityId),
                        dataType: 'json',
                        success: function(data) {
                            console.log('Response Data:', data);

                            // Check if the response was successful and if department data exists
                            if (data.success && data.data && data.data.departments && data.data.departments.length > 0) {
                                let departments = data.data.departments;
                                let departmentSelect = $('#departmentSelect');

                                // Clear the current department dropdown options
                                departmentSelect.empty();

                                // Add default "Select" option at the top of the dropdown
                                departmentSelect.append('<option value="">Select</option>');

                                // Loop through the department data and append each as an option
                                $.each(departments, function(key, department) {
                                    departmentSelect.append('<option value="' + department.id + '">' + department.name + '</option>');
                                });

                                // Refresh the selectric dropdown to reflect new options
                                departmentSelect.selectric('refresh');

                                console.log('Departments populated successfully:', departments);
                            } else {
                                // Log an error message if no departments were found
                                console.log('No departments found or error in response.');

                                // Optionally, reset the dropdown if no data was found
                                $('#departmentSelect').empty().append('<option value="">No departments available</option>');
                            }
                        },
                        error: function(xhr, status, error) {
                            // alert('Unable to fetch departments. Please try again.');
                            console.log('Departments fetch error: ' + error)
                        }
                    })
                } else {
                    console.log('Cannot get the facility ID')
                }
            });

            $('#employeeSelect').on('change', function() {
                let employeeId = $(this).val();
                $('#employee-info-block').addClass('d-none');
                if (employeeId) {
                    $.ajax({
                        type: 'GET',
                        url: '{{ route("admin.ajax-employee.show", ":id") }}'.replace(':id', employeeId),
                        dataType: 'json',
                        success: function(data) {
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
            });
        });
    </script>
@endpush
