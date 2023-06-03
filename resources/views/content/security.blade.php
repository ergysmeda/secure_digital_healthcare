@extends('layouts/contentLayoutMaster')

@section('title', 'Profile')

@section('vendor-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/animate/animate.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/sweetalert2.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap5.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap5.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap5.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/rowGroup.bootstrap5.min.css')) }}">

@endsection

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/extensions/ext-component-sweet-alerts.css')) }}">
@endsection

@section('content')
    <section class="app-user-view-account">
        <div class="row">
            <!-- User Sidebar -->
            <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
                <!-- User Card -->
                <div class="card">
                    <div class="card-header">Update Profile Picture</div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('updateProfilePicture') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group row">
                                <label for="profile_picture" class="col-md-4 col-form-label text-md-right">Profile
                                    Picture</label>

                                <div class="col-md-6">
                                    <input id="profile_picture" type="file"
                                           class="form-control @error('profile_picture') is-invalid @enderror"
                                           name="profile_picture" required>

                                    @error('profile_picture')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <br>
                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Update
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="user-avatar-section">
                            <div class="d-flex align-items-center flex-column">
                                <img
                                    src="{{ route('getProfilePicture', ['filename' => str_replace('images/', '', Auth::user()->profile_picture)]) }}"
                                    class="img-fluid rounded mt-3 mb-2"
                                    height="110"
                                    width="110"
                                    alt="User avatar"
                                >


                                <div class="user-info text-center">
                                    <h4>{{$profile['user_profile']['name']}}</h4>
                                    <span class="badge bg-light-secondary">{{$profile['role']['role_name']}}</span>
                                </div>
                            </div>
                        </div>

                        <h4 class="fw-bolder border-bottom pb-50 mb-1">Details</h4>
                        <div class="info-container">
                            <ul class="list-unstyled">
                                <li class="mb-75">
                                    <span class="fw-bolder me-25">Username:</span>
                                    <span> {{$profile['name'] }}</span>
                                </li>
                                <li class="mb-75">
                                    <span class="fw-bolder me-25">Full Name:</span>
                                    <span>{{$profile['user_profile']['name'] }}</span>
                                </li>
                                <li class="mb-75">
                                    <span class="fw-bolder me-25">Contact Details:</span>
                                    <span>{{$profile['user_profile']['contact_details'] }}</span>
                                </li>
                                <li class="mb-75">
                                    <span class="fw-bolder me-25">Email:</span>
                                    <span>{{$profile['email'] }}</span>
                                </li>
                                <li class="mb-75">
                                    <span class="fw-bolder me-25">Role:</span>
                                    <span>{{$profile['role']['role_name']}}</span>
                                </li>
                                @if($role == 'Doctor')
                                <li class="mb-75">
                                    <span class="fw-bolder me-25">Qualification:</span>
                                    <span>{{$profile['provider_profile']['qualification']}}</span>
                                </li>
                                <li class="mb-75">
                                    <span class="fw-bolder me-25">Speciality:</span>
                                    <span>{{$profile['provider_profile']['specialty']}}</span>
                                </li>
                                <li class="mb-75">
                                    <span class="fw-bolder me-25">Years Of Experience:</span>
                                    <span>{{$profile['provider_profile']['years_of_experience']}}</span>
                                </li>
                                <hr>
                                <div class="card">
                                    <h5 class="card-header">Qualifications</h5>
                                    <div class="row" style="background: #464e66;margin-bottom: 5px">
                                        <div class="col-sm-4">
                                            <span>Qualification:</span>
                                        </div>
                                        <div class="col-sm-3">
                                            <span>Institution:</span>
                                        </div>
                                        <div class="col-sm-5">
                                            <span>Year Of Graduation:</span>
                                        </div>
                                    </div>
                                    @foreach($profile['provider_profile']['qualifications'] as $qualification)

                                        <div class="row" style="background: #464e66;margin-bottom: 5px">
                                            <div class="col-sm-4">
                                                <span>{{$qualification['qualification_name']}}</span>
                                            </div>
                                            <div class="col-sm-3">
                                                <span>{{$qualification['institution_name']}}</span>
                                            </div>
                                            <div class="col-sm-5">
                                                <span>{{$qualification['year_of_graduation']}}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                    <hr>
                                </div>
                                <div class="card">
                                    <h5 class="card-header">Specialties</h5>
                                    <div class="card-body">
                                        @foreach($profile['provider_profile']['specialties'] as   $specialty)
                                            <div>{{$specialty['specialty_name'].'  '}}</div>
                                        @endforeach
                                    </div>
                                </div>

                                @endif
                                @if($role == 'Patient')
                                    <li class="mb-75">
                                        <span class="fw-bolder me-25">DOB:</span>
                                        <span>{{$profile['patient_profile']['dob']}}</span>
                                    </li>
                                    <li class="mb-75">
                                        <span class="fw-bolder me-25">Gender:</span>
                                        <span>{{$profile['patient_profile']['gender']}}</span>
                                    </li>
                                    <li class="mb-75">
                                        <span class="fw-bolder me-25">Blood Group:</span>
                                        <span>{{$profile['patient_profile']['blood_group']}}</span>
                                    </li>
                                    <li class="mb-75">
                                        <span class="fw-bolder me-25">Allergies:</span>
                                        <span>{{$profile['patient_profile']['allergies']}}</span>
                                    </li>
                                @endif
                            </ul>
                            <div class="d-flex justify-content-center pt-2">
                                <a href="javascript:;" class="btn btn-primary me-1" data-bs-target="#modals-slide-in"
                                   data-bs-toggle="modal">
                                    Edit
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--/ User Sidebar -->

            <!-- User Content -->
            <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1">
                <!-- User Pills -->
                <ul class="nav nav-pills mb-2">
                    <li class="nav-item">
                        <a class="nav-link " href="{{route('profile')}}">
                            <i data-feather="user" class="font-medium-3 me-50"></i>
                            <span class="fw-bold">Account</span></a
                        >
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active"  href="{{route('profile.security')}}">
                            <i data-feather="lock" class="font-medium-3 me-50"></i>
                            <span class="fw-bold">Security</span>
                        </a>
                    </li>
                </ul>
                <!--/ User Pills -->

                <!-- Project table -->
                <div class="card">
                    <h4 class="card-header">Change Password</h4>
                    <div class="card-body">
                        <form id="formChangePassword" method="POST"   action="{{ route('profile.securityUpdate') }}">
                            @csrf
                            <div class="alert alert-warning mb-2" role="alert">
                                <h6 class="alert-heading">Ensure that these requirements are met</h6>
                                <div class="alert-body fw-normal">Minimum 8 characters long, uppercase & symbol</div>
                            </div>

                            <div class="row">
                                <div class="mb-2 col-md-6 form-password-toggle">
                                    <label class="form-label" for="newPassword">New Password</label>
                                    <div class="input-group input-group-merge form-password-toggle">
                                        <input
                                            class="form-control"
                                            type="password"
                                            id="newPassword"
                                            name="newPassword"
                                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        value="{{ old('newPassword') }}">

                                        <span class="input-group-text cursor-pointer">
                    <i data-feather="eye"></i>
                  </span>
                                    </div>
                                </div>
                                @error('newPassword')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                                <div>
                                    <button type="submit" class="btn btn-primary me-2">Change Password</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!-- /Project table -->
            </div>
            <!--/ User Content -->
        </div>
    </section>
    <div class="modal modal-slide-in new-user-modal fade" id="modals-slide-in">
        <div class="modal-dialog">
            <form class="add-new-user modal-content pt-0" id="form-modal-id" method="POST"
                  action="{{ route('profile.update') }}">
                @csrf
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
                <div class="modal-header mb-1">
                    <h5 class="modal-title" id="exampleModalLabel">Modify Profile</h5>
                </div>
                <div class="modal-body flex-grow-1">
                    <div class="mb-1">
                        <label class="form-label" for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username"
                               value="{{$profile['name']}}">
                    </div>
                    <div class="mb-1">
                        <label class="form-label" for="fullname">Full Name</label>
                        <input type="text" class="form-control" id="fullname" name="fullname"
                               value="{{$profile['user_profile']['name']}}">
                    </div>
                    <div class="mb-1">
                        <label class="form-label" for="contact">Contact Details</label>
                        <input type="text" class="form-control" id="contact" name="contact"
                               value="{{$profile['user_profile']['contact_details']}}">
                    </div>
                    @if($role == 'Doctor')
                    <div class="mb-1">
                        <label class="form-label" for="qualification">Qualification</label>
                        <input type="text" class="form-control" id="qualification" name="qualification"
                               value="{{$profile['provider_profile']['qualification']}}">
                    </div>
                    <div class="mb-1">
                        <label class="form-label" for="specialty">Speciality</label>
                        <input type="text" class="form-control" id="specialty" name="specialty"
                               value="{{$profile['provider_profile']['specialty']}}">
                    </div>
                    <div class="mb-1">
                        <label class="form-label" for="experience">Years Of Experience</label>
                        <input type="text" class="form-control" id="experience" name="experience"
                               value="{{$profile['provider_profile']['years_of_experience']}}">
                    </div>
                    <h5 class="mt-3">Qualifications</h5>
                    <div id="qualificationsList">
                        @foreach($profile['provider_profile']['qualifications'] as $qualification)
                            <div id="qualification-div_{{$qualification['id']}}">
                                <div class="mb-1">
                                    <label class="form-label"
                                           for="qualification_{{$qualification['id']}}">Qualification</label>
                                        <input type="text" class="form-control"
                                               id="qualification_{{$qualification['id']}}"
                                           name="qualification[]" value="{{$qualification['qualification_name']}}">
                                </div>
                                <div class="mb-1">
                                    <label class="form-label"
                                           for="institution_{{$qualification['id']}}">Institution</label>
                                        <input type="text" class="form-control"
                                               id="institution_{{$qualification['id']}}"
                                           name="institution[]" value='{{$qualification['institution_name']}}'>
                                </div>
                                <div class="mb-1">
                                    <label class="form-label" for="year_{{$qualification['id']}}">Year of
                                        Graduation</label>
                                    <input type="text" class="form-control" id="year_{{$qualification['id']}}"
                                           name="year[]" value="{{$qualification['year_of_graduation']}}">
                                </div>
                                <button type="button" class="btn btn-danger remove-qualification"
                                        data-id="{{$qualification['id']}}">Remove
                                </button>
                            </div>
                        @endforeach
                    </div>
                    <br>
                    <br>
                    <button type="button" class="btn btn-success add-qualification">Add Qualification</button>

                    <h5 class="mt-3">Specialties</h5>
                    <div id="specialtiesList">
                        @foreach($profile['provider_profile']['specialties'] as $specialty)
                            <div id='specialty-div_{{$specialty['id']}}'>
                                <div class="mb-1">
                                    <label class="form-label" for="specialty_{{$specialty['id']}}">Specialty</label>
                                    <input type="text" class="form-control" id="specialty_{{$specialty['id']}}"
                                           name="specialty[]"
                                           value="{{$specialty['specialty_name']}}">
                                </div>
                                <button type="button" class="btn btn-danger remove-specialty"
                                        data-id="{{$specialty['id']}}">Remove
                                </button>
                            </div>
                        @endforeach
                    </div>
                    <br>
                    <br>
                    <button type="button" class="btn btn-success add-specialty">Add Specialty</button>
                    <br>
                    <br>
                    @endif
                    @if($role == 'Patient')
                        <div class="mb-1">
                            <label class="form-label" for="qualification">DOB</label>
                            <input type="date" class="form-control" id="dob" name="dob"
                                   value="{{$profile['patient_profile']['dob']}}">
                        </div>
                        @error('dob')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                        <div class="mb-1">
                            <label class="form-label" for="qualification">Gender</label>
                            <input type="text" class="form-control" id="gender" name="gender"
                                   value="{{$profile['patient_profile']['gender']}}">
                        </div>
                        @error('gender')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                        <div class="mb-1">
                            <label class="form-label" for="qualification">Blood Group</label>
                            <select class="form-control" id="blood_group" name="blood_group">
                                @if(isset($profile['patient_profile']['blood_group']))
                                    <option
                                        value="{{$profile['patient_profile']['blood_group']}}">{{$profile['patient_profile']['blood_group']}}</option>
                                @endif
                                <option></option>
                                <option value="0+">0+</option>
                                <option value="A+">A+</option>
                                <option value="B+">B+</option>
                                <option value="AB+">AB+</option>
                                <option value="0-">0-</option>
                                <option value="A-">A-</option>
                                <option value="B-">B-</option>
                                <option value="AB-">AB-</option>
                            </select>
                        </div>
                        @error('blood_group')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                        <div class="mb-1">
                            <label class="form-label" for="qualification">Allergies</label>
                            <textarea type="date" class="form-control" id="allergies"
                                      name="allergies">{{$profile['patient_profile']['allergies']}}</textarea>
                        </div>
                        @error('allergies')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    @endif

                    <hr>
                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('vendor-script')
    {{-- Vendor js files --}}
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/cleave/cleave.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/cleave/addons/cleave-phone.us.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
    {{-- data table --}}
    <script src="{{ asset(mix('vendors/js/extensions/moment.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/jquery.dataTables.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.bootstrap5.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.responsive.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/responsive.bootstrap5.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.buttons.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/jszip.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/pdfmake.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/vfs_fonts.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.html5.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.print.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.rowGroup.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/extensions/polyfill.min.js')) }}"></script>
@endsection

@section('page-script')
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <script>
        // Counter for qualifications and specialties
        let qualificationCounter = 0;
        let specialtyCounter = 0;

        $('.add-qualification').on('click', function () {
            qualificationCounter++;

            let newQualification = `
    <div class="qualification" id="qualification_${qualificationCounter}">
        <div class="mb-1">
            <label class="form-label" for="qualification_${qualificationCounter}">Qualification</label>
            <input type="text" class="form-control" id="qualification_${qualificationCounter}" name="qualification[]">
        </div>
        <div class="mb-1">
            <label class="form-label" for="institution_${qualificationCounter}">Institution</label>
            <input type="text" class="form-control" id="institution_${qualificationCounter}" name="institution[]">
        </div>
        <div class="mb-1">
            <label class="form-label" for="year_${qualificationCounter}">Year of Graduation</label>
            <input type="text" class="form-control" id="year_${qualificationCounter}" name="year[]">
        </div>
        <button type="button" class="btn btn-danger remove-qualification" data-id="${qualificationCounter}">Remove</button>
    </div>
    `;

            $('#qualificationsList').append(newQualification);
        });

        $('.add-specialty').on('click', function () {
            specialtyCounter++;

            let newSpecialty = `
    <div class="specialty" id="specialty_${specialtyCounter}">
        <div class="mb-1">
            <label class="form-label" for="specialty_${specialtyCounter}">Specialty</label>
            <input type="text" class="form-control" id="specialty_${specialtyCounter}" name="specialty[]">
        </div>
        <button type="button" class="btn btn-danger remove-specialty" data-id="${specialtyCounter}">Remove</button>
    </div>
    `;

            $('#specialtiesList').append(newSpecialty);
        });
        // Remove qualification
        $(document).on('click', '.remove-qualification', function () {
            const id = $(this).data('id');
            $('#qualification-div_' + id).remove();
        });

        // Remove specialty
        $(document).on('click', '.remove-specialty', function () {
            const id = $(this).data('id');
            $('#specialty-div_' + id).remove();
        });


        $(document).ready(function () {
            $('#appointmentsTable').DataTable({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                processing: true,
                serverSide: true,
                paging: true,
                ajax: {
                    url: '{{ route("appointments.datatable") }}',
                    type: 'POST',
                },
                columns: [
                    {data: 'id', name: 'id'},
                        @if($role == 'Patient')
                    {
                        data: 'healthcare_professional.name', name: 'healthcare_professional.name'
                    },
                        @else
                    {
                        data: 'patient.name', name: 'patient.name'
                    },
                        @endif
                    {
                        data: 'appointment_time', name: 'appointment_time'
                    },
                    {data: 'status.status_name', name: 'status.status_name'},
                ]
            });
        });
    </script>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function () {
            const message = '{{ session('errors.message') }}';
            if (message) {
                Swal.fire({
                    title: 'Error!',
                    text: message,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        });


        @if (count($errors) > 0)
        $('#modals-slide-in').modal('show'); // Open the existing modal for editing
        @endif
    </script>
@endsection
