@extends("admin.layouts.layout")

@section("content")

    <section class="section">
        <div class="section-header">
            <h1>Dashboard</h1>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                        <i class="fas fa-hospital"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Facilities</h4>
                        </div>
                        <div class="card-body">
                            {{ $facilityCount }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-danger">
                        <i class="fas fa-sitemap"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Departments</h4>
                        </div>
                        <div class="card-body">
                            {{ $departmentCount }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-warning">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Job Titles</h4>
                        </div>
                        <div class="card-body">
                            {{ $jobTitleCount }}
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-success">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Employees</h4>
                        </div>
                        <div class="card-body">
                            {{ $employeeCount }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon" style="background-color: purple;">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Assignments</h4>
                        </div>
                        <div class="card-body">
                            {{ $assignmentCount }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </section>

@endsection

