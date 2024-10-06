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
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Facility</label>
                                    <div class="col-sm-12 col-md-7">
                                        <select class="form-control selectric" name="facility_id" id="facilitySelect">
                                            <option>Select</option>
                                            @foreach($facilities as $facility)
                                                <option value="{{ $facility->id }}">{{ $facility->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Department</label>
                                    <div class="col-sm-12 col-md-7">
                                        <select class="form-control selectric" name="department_id" id="departmentSelect">
                                            <option id="default-department-select">Select Facility to select Department</option>
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
        $(document).ready(function() {


            $('#facilitySelect').on('change', function() {
                let facilityId = $(this).val();
                let departmentSelect = $('#departmentSelect');

                if (facilityId) {
                    $.ajax({
                        type: 'GET',
                        url: '{{ route("admin.ajax-department.create", ":id") }}'.replace(':id', facilityId),
                        dataType: 'json',
                        success: function(data) {
                            console.log('Response Data:', data);

                            // Clear the current department dropdown options
                            departmentSelect.empty();

                            // Check if the response was successful and if department data exists
                            if (data.success && data.data && data.data.departments && data.data.departments.length > 0) {
                                let departments = data.data.departments;

                                // Add default "Select" option at the top of the dropdown
                                departmentSelect.append('<option value="">Select</option>');

                                // Loop through the department data and append each as an option
                                $.each(departments, function(key, department) {
                                    departmentSelect.append('<option value="' + department.id + '">' + department.name + '</option>');
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
                        error: function(xhr, status, error) {
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
            });


            $('#employeeSelect').on('change', function() {
               let employeeId = $(this).val();
               $('#employee-info-block').addClass('d-none');
               if (employeeId) {
                   $.ajax({
                       type: 'GET',
                       url: '{{ route("admin.ajax-employee.create", ":id") }}'.replace(':id', employeeId),
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
