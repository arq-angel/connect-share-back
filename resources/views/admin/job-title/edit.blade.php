@extends("admin.layouts.layout")

@section("content")
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ route("admin.job-title.index") }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h1>Job Title</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Update Job Title</h4>
                        </div>
                        <div class="card-body">

                            <form action="{{ route("admin.job-title.update", $jobTitle->id) }}" enctype="multipart/form-data"
                                  method="POST">
                                @csrf
                                @method('PUT')

                                @if(isset($jobTitle) && $jobTitle->image)
                                    <div class="form-group row mb-4">
                                        <label
                                            class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Preview</label>
                                        <div class="col-sm-12 col-md-7">
                                            <img class="w-25" src="{{asset($jobTitle->image)}}" alt="">
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
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Title</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="text" name="title" class="form-control" value="{{ old('title', $jobTitle->title ?? '') }}">
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Short Title</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="text" name="short_title" class="form-control" value="{{ old('short_title', $jobTitle->short_title ?? '') }}">
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Manager</label>
                                    <div class="col-sm-12 col-md-7">
                                        <select class="form-control selectric" name="manager_id">
                                            <option value="">Select</option>
                                            @foreach($jobTitles as $title) {{-- Here, i shoudl be carefule not to use $jobTitle as variable otherwise laravel will
                                                                                interpret all $jobTitle from here on out from the selected dropdown as new $jobTitle
                                                                                and use Eloquent ORM for all the rest of page --}}
                                                <option value="{{$title->id}}"
                                                        @if($title->id == old('manager_id', $managerId ?? ''))
                                                            selected
                                                    @endif
                                                >
                                                    {{$title->title}}
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
                                                        @if($status == old('status', $jobTitle->status))
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
                                                    @if(old('directory_flag', $jobTitle->directory_flag) == 0) selected @endif>
                                                No
                                            </option>
                                            <option value="1"
                                                    @if(old('directory_flag', $jobTitle->directory_flag) == 1) selected @endif>
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
