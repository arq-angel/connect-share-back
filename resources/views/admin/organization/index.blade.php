@extends("admin.layouts.layout")

@section("content")
    <section class="section">
        <div class="section-header">
            <h1>Organizational Charts</h1>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-success">
                        <i class="fas fa-building"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Company Wide Organisational Chart</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-md-12 mb-3">
                                    <a href="{{ route("admin.organization.company") }}" class="btn btn-success">Company
                                        <i class="fas fa-project-diagram"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                        <i class="fas fa-hospital"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Facility Organizational Chart</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-md-4 mb-3">
                                    <select class="form-control selectric" name="country" id="facilitySelect">
                                        <option value="">Select Facility</option>
                                        @foreach($facilities as $facility)
                                            <option value="{{ $facility->id }}">
                                                {{ $facility->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-md-12 mb-3">
                                    <a href="{{ route("admin.organization.company.facility", 0) }}"
                                       class="btn btn-primary" id="facilityChartLink">
                                        Facility
                                        <i class="fas fa-project-diagram"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-danger">
                        <i class="fas fa-sitemap"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Department Organizational Chart</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-md-4 mb-3">
                                    <select class="form-control selectric" name="country" id="departmentFacilitySelect">
                                        <option value="">Select Facility</option>
                                        @foreach($facilities as $facility)
                                            <option value="{{ $facility->id }}">
                                                {{ $facility->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-md-4 mb-3">
                                    <select class="form-control selectric" name="country" id="departmentDepartmentSelect">
                                        <option value="">Select Department</option>

                                    </select>
                                </div>
                                <div class="col-12 col-md-12 mb-3">
                                    <a href="{{ route('admin.organization.company.facility.department', ['facility' => 0, 'department' => 0]) }}"
                                       class="btn btn-danger" id="departmentChartLink">
                                        Department <i class="fas fa-project-diagram"></i>
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-warning">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Job Title Organizational Chart</h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-md-4 mb-3">
                                    <select class="form-control selectric" name="country" id="jobTitleFacilitySelect">
                                        <option value="">Select Facility</option>
                                        @foreach($facilities as $facility)
                                            <option value="{{ $facility->id }}">
                                                {{ $facility->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-md-4 mb-3">
                                    <select class="form-control selectric" name="country" id="jobTitleDepartmentSelect">
                                        <option value="">Select Department</option>

                                    </select>
                                </div>
                                <div class="col-12 col-md-4 mb-3">
                                    <select class="form-control selectric" name="country" id="jobTitleJobTitleSelect">
                                        <option value="">Select Job Title</option>

                                    </select>
                                </div>
                                <div class="col-12 col-md-12 mb-3">
                                    <a href="{{ route('admin.organization.company.facility.department.job-title', ['facility' => 0, 'department' => 0, 'jobTitle' => 0]) }}"
                                       class="btn btn-warning" id="jobTitleChartLink">
                                        Job Title <i class="fas fa-project-diagram"></i>
                                    </a>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
@endsection

@push("scripts")
    <script>
        $(document).ready(function() {

            let facilityFacility = $('#facilitySelect');
            let facilityChartLink = $('#facilityChartLink');
            facilityChartLink.on('click', function (e) {
                e.preventDefault();

                console.log("Facility Chart Link clicked");
                console.log("Facility Select Id:", facilityFacility.val());

                if (!facilityFacility.val()) {
                    toastr.error("Please select a Facility before proceeding to Facility Chart.");
                } else {
                    // Construct the URL with the selected facility ID
                    let facilityId = facilityFacility.val();
                    let newUrl = `{{ route("admin.organization.company.facility", "") }}/${facilityId}`;

                    // Redirect to the new URL
                    window.location.href = newUrl;
                }
            })

            let departmentFacilitySelect = $('#departmentFacilitySelect');
            let departmentDepartmentFetchFunction = function () {
                let departmentFacilityId = departmentFacilitySelect.val();
                let departmentDepartmentSelect = $('#departmentDepartmentSelect');

                if (departmentFacilityId) {
                    $.ajax({
                        type: 'GET',
                        url: '{{ route("admin.ajax-department.create", ":id") }}'.replace(':id', departmentFacilityId),
                        dataType: 'json',
                        success: function (data) {
                            console.log('Response Data:', data);

                            // Clear the current department dropdown options
                            departmentDepartmentSelect.empty();

                            // Check if the response was successful and if department data exists
                            if (data.success && data.data && data.data.departments && data.data.departments.length > 0) {
                                let departments = data.data.departments;

                                // Add default "Select" option at the top of the dropdown
                                departmentDepartmentSelect.append('<option value="">Select Department</option>');

                                // Loop through the department data and append each as an option
                                $.each(departments, function (key, department) {

                                    departmentDepartmentSelect.append('<option value="' + department.id + '">' + department.name + '</option>');
                                });

                                console.log('Departments populated successfully:', departments);
                            } else {
                                // Log an error message if no departments were found
                                console.log('No departments found or error in response.');

                                // Reset the dropdown with a message indicating no departments are available
                                departmentDepartmentSelect.append('<option value="">No departments available</option>');
                            }

                            // Refresh the selectric dropdown to reflect new options
                            departmentDepartmentSelect.selectric('refresh');
                        },
                        error: function (xhr, status, error) {
                            console.log('Departments fetch error: ' + error);

                            // Reset the dropdown in case of error
                            departmentDepartmentSelect.empty().append('<option value="">No departments available</option>').selectric('refresh');
                        }
                    });
                } else {
                    console.log('No facility ID selected.');

                    // Reset the dropdown if no facility is selected
                    departmentDepartmentSelect.empty().append('<option value="">Select Facility to select Department</option>').selectric('refresh');
                }
            };
            departmentFacilitySelect.on('change', function () {
                departmentDepartmentFetchFunction();
            });

            let departmentDepartmentSelect = $('#departmentDepartmentSelect');
            let departmentChartLink = $('#departmentChartLink');

            departmentChartLink.on('click', function (e) {
                e.preventDefault();

                console.log("Department Chart Link clicked");
                console.log("Facility Select Id:", departmentFacilitySelect.val());
                console.log("Department Select Id:", departmentDepartmentSelect.val());

                if (!departmentFacilitySelect.val() || !departmentDepartmentSelect.val()) {
                    toastr.error("Please select Facility and Department before proceeding to Department Chart.");
                } else {
                    // Construct the URL with the selected facility ID
                    let departmentFacilityId = departmentFacilitySelect.val();
                    let departmentDepartmentId = departmentDepartmentSelect.val();

                    // Construct the new URL with the selected facility and department IDs
                    let newUrl = `{{ route('admin.organization.company.facility.department', ['facility' => 'FACILITY_ID', 'department' => 'DEPARTMENT_ID']) }}`;
                    newUrl = newUrl.replace('FACILITY_ID', departmentFacilityId).replace('DEPARTMENT_ID', departmentDepartmentId);

                    // Redirect to the new URL
                    window.location.href = newUrl;
                }

            })


            // I could reuse the department fetch function but i dont want to deal with the complexity now
            let jobTitleFacilitySelect = $('#jobTitleFacilitySelect');
            let jobTitleDepartmentFetchFunction = function () {
                let jobTitleFacilityId = jobTitleFacilitySelect.val();
                let jobTitleDepartmentSelect = $('#jobTitleDepartmentSelect');

                if (jobTitleFacilityId) {
                    $.ajax({
                        type: 'GET',
                        url: '{{ route("admin.ajax-department.create", ":id") }}'.replace(':id', jobTitleFacilityId),
                        dataType: 'json',
                        success: function (data) {
                            console.log('Response Data:', data);

                            // Clear the current department dropdown options
                            jobTitleDepartmentSelect.empty();

                            // Check if the response was successful and if department data exists
                            if (data.success && data.data && data.data.departments && data.data.departments.length > 0) {
                                let departments = data.data.departments;

                                // Add default "Select" option at the top of the dropdown
                                jobTitleDepartmentSelect.append('<option value="">Select</option>');

                                // Loop through the department data and append each as an option
                                $.each(departments, function (key, department) {

                                    jobTitleDepartmentSelect.append('<option value="' + department.id + '">' + department.name + '</option>');
                                });

                                console.log('Departments populated successfully:', departments);
                            } else {
                                // Log an error message if no departments were found
                                console.log('No departments found or error in response.');

                                // Reset the dropdown with a message indicating no departments are available
                                jobTitleDepartmentSelect.append('<option value="">No departments available</option>');
                            }

                            // Refresh the selectric dropdown to reflect new options
                            jobTitleDepartmentSelect.selectric('refresh');
                        },
                        error: function (xhr, status, error) {
                            console.log('Departments fetch error: ' + error);

                            // Reset the dropdown in case of error
                            jobTitleDepartmentSelect.empty().append('<option value="">No departments available</option>').selectric('refresh');
                        }
                    });
                } else {
                    console.log('No facility ID selected.');

                    // Reset the dropdown if no facility is selected
                    jobTitleDepartmentSelect.empty().append('<option value="">Select Facility to select Department</option>').selectric('refresh');
                }
            };
            jobTitleFacilitySelect.on('change', function () {
                jobTitleDepartmentFetchFunction();
            });

            let jobTitleDepartmentSelect = $('#jobTitleDepartmentSelect');
            let jobTitleJobTitleFetchFunction = function () {
                let jobTitleDepartmentId = jobTitleDepartmentSelect.val();
                let jobTitleJobTitleSelect = $('#jobTitleJobTitleSelect');

                if (jobTitleDepartmentId) {
                    $.ajax({
                        type: 'GET',
                        url: '{{ route("admin.ajax-job-title.create", ":id") }}'.replace(':id', jobTitleDepartmentId)  + '?chart=true', // add flag with request to only get jobTitles in the contacts list
                        dataType: 'json',
                        success: function (data) {
                            console.log('Response Data:', data);

                            // Clear the current department dropdown options
                            jobTitleJobTitleSelect.empty();

                            // Check if the response was successful and if department data exists
                            if (data.success && data.data && data.data.jobTitles && data.data.jobTitles.length > 0) {
                                let jobTitles = data.data.jobTitles;

                                // Add default "Select" option at the top of the dropdown
                                jobTitleJobTitleSelect.append('<option value="">Select</option>');

                                // Loop through the department data and append each as an option
                                $.each(jobTitles, function (key, jobTitle) {

                                    jobTitleJobTitleSelect.append('<option value="' + jobTitle.id + '">' + jobTitle.title + '</option>');
                                });

                                console.log('Departments populated successfully:', jobTitles);
                            } else {
                                // Log an error message if no departments were found
                                console.log('No departments found or error in response.');

                                // Reset the dropdown with a message indicating no departments are available
                                jobTitleJobTitleSelect.append('<option value="">No job titles available</option>');
                            }

                            // Refresh the selectric dropdown to reflect new options
                            jobTitleJobTitleSelect.selectric('refresh');
                        },
                        error: function (xhr, status, error) {
                            console.log('Job Titles fetch error: ' + error);

                            // Reset the dropdown in case of error
                            jobTitleJobTitleSelect.empty().append('<option value="">No job titles available</option>').selectric('refresh');
                        }
                    });
                } else {
                    console.log('No Department ID selected.');

                    // Reset the dropdown if no facility is selected
                    jobTitleSelect.empty().append('<option value="">Select Department to select Job Title</option>').selectric('refresh');
                }
            }
            jobTitleDepartmentSelect.on('change', function () {
                jobTitleJobTitleFetchFunction();
            });

            let jobTitleJobTitleSelect = $('#jobTitleJobTitleSelect');
            let jobTitleChartLink = $('#jobTitleChartLink');

            jobTitleChartLink.on('click', function (e) {
                e.preventDefault();

                console.log("Department Chart Link clicked");
                console.log("Facility Select Id:", jobTitleFacilitySelect.val());
                console.log("Department Select Id:", jobTitleDepartmentSelect.val());
                console.log("Job Title Select Id:", jobTitleJobTitleSelect.val());

                if (!jobTitleFacilitySelect.val() || !jobTitleDepartmentSelect.val() || !jobTitleJobTitleSelect.val()) {
                    toastr.error("Please select Facility, Department and Job Title before proceeding to Job Title Chart.");
                } else {
                    // Construct the URL with the selected facility ID
                    let jobTitleFacilityId = jobTitleFacilitySelect.val();
                    let jobTitleDepartmentId = jobTitleDepartmentSelect.val();
                    let jobTitleJobTitleId = jobTitleJobTitleSelect.val();

                    // Construct the new URL with the selected facility and department IDs
                    let newUrl = `{{ route('admin.organization.company.facility.department.job-title', ['facility' => 'FACILITY_ID', 'department' => 'DEPARTMENT_ID', 'jobTitle' => 'JOBTITLE_ID']) }}`;
                    newUrl = newUrl.replace('FACILITY_ID', jobTitleFacilityId).replace('DEPARTMENT_ID', jobTitleDepartmentId).replace('JOBTITLE_ID', jobTitleJobTitleId);

                    // Redirect to the new URL
                    window.location.href = newUrl;
                }

            })

        });
    </script>
@endpush
