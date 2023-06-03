@extends('layouts/contentLayoutMaster')

@section('title', 'File sharing')

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('css/base/pages/page-profile.css')) }}">
@endsection

@section('content')
    <div id="user-profile">
        <!-- profile info section -->
        <section id="profile-info">
            <div class="row">
                <!-- left profile info section -->
                @if($role == 'Patient')
                    <div class="col-lg-5 col-12 order-2 order-lg-1">
                        <!-- about -->
                        <div class="card">
                            <div class="card-body profile-suggestion">
                                <h5 class="mb-75">Add new record</h5>
                                <div class="card-body">
                                    <form method="POST" action="{{ route('addRecord') }}"
                                          enctype="multipart/form-data">
                                        @csrf <!-- {{ csrf_field() }} -->
                                        <div class="mt-2">
                                            <label for="medical_history" class="mb-75">Medical History:</label>
                                            <input type="text" class="form-control" id="medical_history"
                                                   name="medical_history">
                                        </div>
                                        @error('medical_history')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                        <div class="mt-2">
                                            <label for="medical_history" class="mb-75">File:</label>
                                            <input type="file" class="form-control" id="file" name="file">
                                        </div>
                                        @error('file')
                                        <div class="text-danger">{{ $message }}</div>
                                        @enderror
                                        <div class="mt-2">
                                            <button type="submit" class="btn btn-primary">Add</button>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="card">
                            <div class="card-body">
                                <h5 class="mb-75">Medical Records</h5>
                                @foreach($allRecords as $record)
                                    <div class="mt-2">
                                        <h5 class="mb-75">Medical History:</h5>
                                        <p class="card-text">{{$record['medical_history']}}</p>
                                    </div>
                                    <div class="mt-2">
                                        @if(isset($record['file']['file_path']))

                                            <h5 class="mb-75">File:</h5>
                                            <a class="btn btn-primary"
                                               href="/file-sharing/downloadFile/{{$record['file']['file_path']}}">Download </a>

                                        @endif
                                        <a class="btn btn-warning"
                                           href="/file-sharing/deleteRecord/{{$record['id']}}">Delete </a>
                                    </div>
                                    <br>
                                    <br>
                                    <!-- comment box -->
                                    <div class="row">

                                        <div class="col-sm-4">
                                            <label class="form-label" for="label-textarea">Select Doctor</label>
                                            <select type="select" class="form-control" id="doctor_{{$record['id']}}">
                                                <option></option>
                                                @foreach($doctors as $doctor)
                                                    <option value="{{$doctor['id']}}">{{$doctor['name']}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <br>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <button onclick="shareRecord('{{$record['id']}}')" type="button"
                                                    class="btn btn-sm btn-primary">Send
                                            </button>
                                        </div>
                                    </div>
                                    <hr>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-7 col-12 order-1 order-lg-2">
                        @endif
                        <h3>Shared History</h3>
                        <!-- post 1 -->

                        @foreach($sharedRecords as $sharedRecord)
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-start align-items-center mb-1">

                                        <div class="profile-user-info">
                                            @if($role == 'Patient')
                                                <h6 class="mb-0">{{ucfirst($sharedRecord[0]['shared_with']['name'])}}</h6>
                                            @elseif($role == 'Doctor')
                                                <h6 class="mb-0">{{ucfirst($sharedRecord[0]['record']['patient']['name'])}}</h6>
                                            @endif
                                            <small
                                                class="text-muted">{{date('Y-m-d H:i:s',strtotime($sharedRecord[0]['created_at']))}}</small>
                                        </div>
                                    </div>

                                    @foreach($sharedRecord as $rec)

                                        <!-- comments -->
                                        <div class="d-flex align-items-start mb-1">
                                            <div class="profile-user-info w-100">
                                                <div class="d-flex align-items-center justify-content-between">
                                                    <h6 class="mb-0">{{$rec['record']['medical_history']}}</h6>
                                                </div>

                                                <small>{{date('Y-m-d H:i:s',strtotime($rec['created_at']))}}</small>
                                                @if(isset($rec['record']['file']['file_path']))
                                                    <div
                                                        class="d-flex align-items-center justify-content-between">
                                                        <a class="btn btn-secondary"
                                                           href="/file-sharing/downloadFile/{{$rec['record']['file']['file_path']}}">Download </a>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
            </div>
            <!--/ polls card -->
        </section>
    </div>
@endsection

@section('page-script')
    {{-- Page js files --}}
    <script>
        function shareRecord(record_id) {
            let doctor_id = $('#doctor_' + record_id).val();
            console.log(record_id, doctor_id)


            fetch('/file-sharing/shareRecord', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json', // Set the content type to JSON
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // Add CSRF token if required
                },
                body: JSON.stringify({
                    'record_id': record_id,
                    'doctor_id': doctor_id
                })
            })
                .then(response => {
                    // Handle the response
                    if (response.ok) {
                        location.reload();
                    } else {
                        // Handle error response
                        response.json().then(data => {
                            // Use SweetAlert2 to display the error message
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message,
                            });
                        });
                    }
                })
                .catch(error => {
                    // Handle network or other errors
                    console.error('Error:', error);
                });
        }
    </script>
@endsection
