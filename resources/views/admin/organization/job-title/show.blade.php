@extends("admin.layouts.layout")

@section("content")
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ route("admin.organization.index") }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h1>Organizational Charts</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Job Title Organizational Chart</h4>
                        </div>
                        <div class="card-body">

                            <div class="container">
                                <!-- Company Information -->
                                <div class="list-group">
                                    <div class="list-group-item">
                                        <h5 class="mb-1">
                                            <strong>Company:</strong> {{ $jobTitleChart['company']['name'] }}
                                        </h5>
                                        <p class="mb-1">
                                            <strong>Phone:</strong> {{ $jobTitleChart['company']['phone'] }} <br>
                                            <strong>Email:</strong> {{ $jobTitleChart['company']['email'] }}
                                        </p>

                                        <!-- Facilities -->
                                        <div class="accordion" id="facilityAccordion">
                                            @foreach ($jobTitleChart['company']['facilities'] as $facility)
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="facilityHeading{{ $loop->index }}">
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                                                data-bs-target="#facilityCollapse{{ $loop->index }}"
                                                                aria-expanded="false" aria-controls="facilityCollapse{{ $loop->index }}">
                                                            Facility: {{ $facility['name'] }}
                                                        </button>
                                                    </h2>
                                                    <div id="facilityCollapse{{ $loop->index }}" class="accordion-collapse collapse"
                                                         aria-labelledby="facilityHeading{{ $loop->index }}"
                                                         data-bs-parent="#facilityAccordion">
                                                        <div class="accordion-body">
                                                            <strong>Phone:</strong> {{ $facility['phone'] }} <br>
                                                            <strong>Email:</strong> {{ $facility['email'] }} <br>

                                                            <!-- Departments -->
                                                            <div class="accordion mt-3" id="departmentAccordion{{ $loop->index }}">
                                                                @foreach ($facility['departments'] as $department)
                                                                    <div class="accordion-item">
                                                                        <h2 class="accordion-header" id="departmentHeading{{ $loop->parent->index }}-{{ $loop->index }}">
                                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                                                                    data-bs-target="#departmentCollapse{{ $loop->parent->index }}-{{ $loop->index }}"
                                                                                    aria-expanded="false" aria-controls="departmentCollapse{{ $loop->parent->index }}-{{ $loop->index }}">
                                                                                Department: {{ $department['name'] }} ({{ $department['short_name'] }})
                                                                            </button>
                                                                        </h2>
                                                                        <div id="departmentCollapse{{ $loop->parent->index }}-{{ $loop->index }}"
                                                                             class="accordion-collapse collapse"
                                                                             aria-labelledby="departmentHeading{{ $loop->parent->index }}-{{ $loop->index }}"
                                                                             data-bs-parent="#departmentAccordion{{ $loop->parent->index }}">
                                                                            <div class="accordion-body">
                                                                                <!-- Job Titles -->
                                                                                <div class="list-group">
                                                                                    @foreach ($department['jobTitles'] as $deptJobTitle)
                                                                                        <div class="list-group-item">
                                                                                            <strong>Job Title:</strong> {{ $deptJobTitle['title'] }} ({{ $deptJobTitle['short_title'] }}) <br>

                                                                                            <!-- Employees -->
                                                                                            <ul class="list-group mt-2">
                                                                                                @foreach ($deptJobTitle['employees'] as $employee)
                                                                                                    <li class="list-group-item">
                                                                                                        {{ $employee['first_name'] }} {{ $employee['middle_name'] }} {{ $employee['last_name'] }} <br>
                                                                                                        Phone: {{ $employee['phone'] }} <br>
                                                                                                        Email: {{ $employee['email'] }}
                                                                                                    </li>
                                                                                                @endforeach
                                                                                            </ul>
                                                                                        </div>
                                                                                    @endforeach
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
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

@endpush
