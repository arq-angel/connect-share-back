@extends("admin.layouts.layout")

@section("content")
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ route("dashboard") }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h1>Company Section</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Update Company Section</h4>
                        </div>
                        <div class="card-body">

                            <form action="{{ route("admin.company.update", 1) }}" enctype="multipart/form-data"
                                  method="POST">
                                @csrf
                                @method('PUT')

                                @if(isset($company) && $company->image)
                                    <div class="form-group row mb-4">
                                        <label
                                            class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Preview</label>
                                        <div class="col-sm-12 col-md-7">
                                            <img class="w-25" src="{{asset($company->image)}}" alt="">
                                        </div>
                                    </div>
                                @endif

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Logo</label>
                                    <div class="col-sm-12 col-md-7">
                                        <div class="custom-file">
                                            <input type="file" name="image" class="custom-file-input" id="customFile">
                                            <label class="custom-file-label" for="customFile">Choose file</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Name</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="text" name="name" class="form-control" value="{{ $company->name }}">
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Address</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="text" name="address" class="form-control" value="{{ $company->address }}">
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Suburb</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="text" name="suburb" class="form-control" value="{{ $company->suburb }}">
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">State</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="text" name="state" class="form-control" value="{{ $company->state }}">
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Post Code</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="text" name="postal_code" class="form-control" value="{{ $company->postal_code }}">
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Country</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="text" name="country" class="form-control" value="{{ $company->country }}">
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Email</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="text" name="email" class="form-control" value="{{ $company->email }}">
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Phone</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="text" name="phone" class="form-control" value="{{ $company->phone }}">
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Website Url</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="text" name="website" class="form-control" value="{{ $company->website }}">
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Industry</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="text" name="industry" class="form-control" value="{{ $company->industry }}">
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Size</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="number" name="size" class="form-control" value="{{ $company->size }}">
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Established Date</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="date" name="established_date" class="form-control" value="{{ $company->established_date }}">
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
