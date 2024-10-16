@extends("admin.layouts.layout")

@section("content")
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ route("admin.department.index") }}" class="btn btn-icon"><i
                        class="fas fa-arrow-left"></i></a>
            </div>
            <h1>Department</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Update Department</h4>
                        </div>
                        <div class="card-body">

                            <form action="{{ route("admin.department.update", $department->id) }}"
                                  enctype="multipart/form-data"
                                  method="POST">
                                @csrf
                                @method('PUT')

                                @if(isset($department) && $department->image)
                                    <div class="form-group row mb-4">
                                        <label
                                            class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Preview</label>
                                        <div class="col-sm-12 col-md-7">
                                            <img class="w-25" src="{{asset($department->image)}}" alt="">
                                        </div>
                                    </div>
                                @endif

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
                                        <input type="text" name="name" class="form-control"
                                               value="{{ old('name', $department->name ?? '') }}">
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Short
                                        Name</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="text" name="short_name" class="form-control"
                                               value="{{ old('short_name', $department->short_name ?? '') }}">
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Job
                                        Titles</label>
                                    <div class="col-sm-12 col-md-7">
                                        <select class="form-control selectric" name="job_title_id[]" multiple>
                                            <option disabled>Select</option>
                                            @foreach($jobTitles as $jobTitle)
                                                <option value="{{ $jobTitle->id }}"
                                                        @if(is_array(old('job_title_id')))
                                                            @if(in_array($jobTitle->id, old('job_title_id')))
                                                                selected
                                                        @endif
                                                        @elseif(isset($selectedJobTitles) && in_array($jobTitle->id, $selectedJobTitles))
                                                            selected
                                                    @endif
                                                >
                                                    {{ $jobTitle->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Parent Department</label>
                                    <div class="col-sm-12 col-md-7">
                                        <select class="form-control selectric" name="parent_id">
                                            <option value="">Select</option>
                                            @foreach($departments as $item) {{-- to avoid unwanted E ORM for th erest of the page where i will use $department --}}
                                                <option value="{{ $item->id }}"
                                                        @if($item->id == old('parent_id', ($parentDepartmentId ?? '')))
                                                            selected
                                                    @endif>
                                                    {{ $item->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Status</label>
                                    <div class="col-sm-12 col-md-7">
                                        <select class="form-control selectric" name="status">
                                            <option value="">Select</option>
                                            @foreach($statuses as $status)
                                                <option value="{{$status}}"
                                                        @if($status == old('status', $department->status))
                                                            selected
                                                    @endif
                                                >
                                                    {{ucfirst($status)}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Add to
                                        Contact Directory</label>
                                    <div class="col-sm-12 col-md-7">
                                        <select class="form-control selectric" name="directory_flag">
                                            <option value="0"
                                                    @if(old('directory_flag', $department->directory_flag) == 0) selected @endif>
                                                No
                                            </option>
                                            <option value="1"
                                                    @if(old('directory_flag', $department->directory_flag) == 1) selected @endif>
                                                Yes
                                            </option>

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
