@extends("admin.layouts.layout")

@section("content")
    <section class="section">
        <div class="section-header">
            <div class="section-header-back">
                <a href="{{ route("admin.employee.index") }}" class="btn btn-icon"><i class="fas fa-arrow-left"></i></a>
            </div>
            <h1>Employee</h1>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Create Employee</h4>
                        </div>
                        <div class="card-body">

                            <form action="{{ route("admin.employee.store") }}" enctype="multipart/form-data"
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
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">First Name</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}">
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Middle Name</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="text" name="middle_name" class="form-control" value="{{ old('middle_name') }}">
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Last Name</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}">
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Gender</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="text" name="gender" class="form-control" value="{{ old('gender') }}">
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Date Of Birth</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="date" name="date_of_birth" class="form-control" value="{{ old('date_of_birth') }}">
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
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Email</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Phone</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}">
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Address</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="text" name="address" class="form-control" value="{{ old('address') }}">
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Suburb</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="text" name="suburb" class="form-control" value="{{ old('suburb') }}">
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">State</label>
                                    <div class="col-sm-12 col-md-7">
                                        <select class="form-control selectric" name="state" id="stateSelect">
                                            <option id="default-state-select" disabled>Select Country to select State</option>
                                        </select>
                                    </div>
                                </div>
                                <input value="{{ old('state') }}" id="old-selected-state" disabled hidden>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Post
                                        Code</label>
                                    <div class="col-sm-12 col-md-7">
                                        <input type="text" name="postal_code" class="form-control" value="{{ old('postal_code') }}">
                                    </div>
                                </div>

                                <div class="form-group row mb-4">
                                    <label class="col-form-label text-md-right col-12 col-md-3 col-lg-3">Country</label>
                                    <div class="col-sm-12 col-md-7">
                                        <select class="form-control selectric" name="country" id="countrySelect">
                                            <option>Select</option>
                                            @foreach($countries as $country)
                                                <option value="{{ $country }}"
                                                        @if($country === (old('country')))
                                                            selected
                                                    @endif
                                                >
                                                    {{ $country }}
                                                </option>
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

            let countrySelect = $('#countrySelect');
            let oldSelectedState = $('#old-selected-state').val();
            let stateFetchFunction = function() {
                let country = countrySelect.val();
                let stateSelect = $('#stateSelect');

                if (country) {
                    $.ajax({
                        type: 'GET',
                        url: '{{ route("admin.ajax-state.create", ":country") }}'.replace(':country', country),
                        dataType: 'json',
                        success: function(data) {
                            console.log('Response Data:', data);

                            // Clear the current department dropdown options
                            stateSelect.empty();

                            // Check if the response was successful and if department data exists
                            if (data.success && data.data && data.data.states && data.data.states.length > 0) {
                                let states = data.data.states;

                                // Add default "Select" option at the top of the dropdown
                                stateSelect.append('<option value="">Select</option>');

                                // Loop through the department data and append each as an option
                                $.each(states, function(key, state) {
                                    let selected = '';

                                    if (oldSelectedState && (state == oldSelectedState)) {
                                        selected = 'selected';
                                    }

                                    stateSelect.append('<option value="' + state + '" ' + selected + '>' + state + '</option>');
                                });

                                console.log('States populated successfully:', states);
                            } else {
                                // Log an error message if no departments were found
                                console.log('No states found or error in response.');

                                // Reset the dropdown with a message indicating no departments are available
                                stateSelect.append('<option value="">No states available</option>');
                            }

                            // Refresh the selectric dropdown to reflect new options
                            stateSelect.selectric('refresh');
                        },
                        error: function(xhr, status, error) {
                            console.log('States fetch error: ' + error);

                            // Reset the dropdown in case of error
                            stateSelect.empty().append('<option value="">No states available</option>').selectric('refresh');
                        }
                    });
                } else {
                    console.log('No country selected.');

                    // Reset the dropdown if no facility is selected
                    stateSelect.empty().append('<option value="">Select Country to select State</option>').selectric('refresh');
                }
            }
            if (countrySelect.val() && !(countrySelect.val() === 'Select')) {
                console.log(countrySelect.val())
                stateFetchFunction();
            }
            if (oldSelectedState) {
                stateFetchFunction();
            }
            $(countrySelect).on('change', function() {
                stateFetchFunction();
            });

        });
    </script>
@endpush
