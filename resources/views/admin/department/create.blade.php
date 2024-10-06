@extends("admin.layouts.layout")

@section("content")
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ route("admin.department.index") }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h1>Department</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Create Department</h4>
                        </div>
                        <div class="card-body">

                            <form action="{{ route("admin.department.store") }}" enctype="multipart/form-data"
                                  method="POST">
                                @csrf

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Image</label>
                                    <div class="col-sm-12 col-md-7">
                                        <div class="custom-file">
                                            <input type="file" name="image" class="custom-file-input" id="customFile">
                                            <label class="custom-file-label" for="customFile">Choose file</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Company</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input class="form-control" value="{{$company->name}}" disabled>
                                        <input type="hidden" name="company_id" value="{{ $company->id }}">
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Name</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="text" name="name" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Short Name</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="text" name="short_name" class="form-control">
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Job Titles</label>
                                    <div class="col-sm-12 col-md-7">
                                        <select class="form-control selectric" name="job_title_id[]" multiple>
                                            <option disabled>Select</option>
                                            @foreach($jobTitles as $jobTitle)
                                                <option value="{{$jobTitle->id}}">{{$jobTitle->title}}</option>
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
