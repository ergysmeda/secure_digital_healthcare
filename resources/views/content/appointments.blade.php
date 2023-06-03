@extends('layouts/contentLayoutMaster')

@section('title', 'Appointments')

@section('vendor-style')
    <link rel="stylesheet" href="{{asset('vendors/css/tables/datatable/dataTables.bootstrap5.min.css')}}">
    <link rel="stylesheet" href="{{asset('vendors/css/tables/datatable/extensions/dataTables.checkboxes.css')}}">
    <link rel="stylesheet" href="{{asset('vendors/css/tables/datatable/responsive.bootstrap5.min.css')}}">
@endsection

@section('page-style')
    <link rel="stylesheet" href="{{asset('css/base/pages/app-invoice-list.css')}}">
@endsection

@section('content')
    <section class="invoice-list-wrapper">
        @if($role == 'Patient')
        <div class="card">
            <div class="container">

                <form action="{{ route('appointments.store') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-sm-3">
                            <label for="doctor_id">Doctor</label>
                            <select class="form-control" name="doctor_id" id="doctor_id">
                                <option></option>
                                @foreach ($doctors as $doctor)
                                    <option value="{{ $doctor['id'] }}">{{ $doctor['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('doctor_id')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                        <div class="col-sm-3">
                            <label for="appointment_time">Time</label>
                            <select class="form-control" name="appointment_time" id="appointment_time">
                                <!-- Appointment times will be populated by AJAX request -->
                            </select>
                        </div>
                        @error('appointment_time')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                        <div class="col-sm-3">
                            <label for="description">Description</label>
                            <textarea rows="1"  class="form-control" name="description" id="description"></textarea>
                        </div>
                        @error('description')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                        <div class="col-sm-3">
                            <label for="description">Online/In presence</label>
                            <select class="form-control" name="online_in_presence" id="online_in_presence">
                                <option value="0">Online</option>
                                <option value="1">In presence</option>
                            </select>
                        </div>
                        @error('online_in_presence')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="col-sm-3" id="locationField" style="display: none;">
                            <label for="location">Location</label>
                            <select class="form-control" name="location" id="location">
                                <option value=""></option>
                                @foreach($locations as $location)
                                    <option value="{{$location['id']}}">{{$location['location_name']}}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('location')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <br><br>
                    <button class="btn btn-primary" type="submit">Create Appointment</button>
                </form>

            </div>
        </div>
        @endif
        <div class="card">
            <div class="card-datatable table-responsive">
                <table class="invoice-list-table table" id='appointmentsTable'>
                    <thead>
                    <tr>
                        <th>ID</th>
                        @if($role == 'Patient')
                            <th class="text-nowrap">Doctor</th>
                        @else
                            <th class="text-nowrap">Patient</th>
                        @endif
                        <th>Status</th>
                        <th>Cost</th>
                        <th>Appointment Time</th>
                        <th>Description</th>
                        <th>Online/In Presence</th>
                        <th>Notes</th>
                        <th>Scheduling Data</th>
                        <th class="cell-fit">Actions</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </section>
@endsection

@section('vendor-script')
    <script src="{{asset('vendors/js/extensions/moment.min.js')}}"></script>
    <script src="{{asset('vendors/js/tables/datatable/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('vendors/js/tables/datatable/datatables.buttons.min.js')}}"></script>
    <script src="{{asset('vendors/js/tables/datatable/dataTables.bootstrap5.min.js')}}"></script>
    <script src="{{asset('vendors/js/tables/datatable/datatables.checkboxes.min.js')}}"></script>
    <script src="{{asset('vendors/js/tables/datatable/dataTables.responsive.min.js')}}"></script>
    <script src="{{asset('vendors/js/tables/datatable/responsive.bootstrap5.js')}}"></script>
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

        $(document).ready(function () {
            $('#appointmentsTable').DataTable({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                processing: true,
                serverSide: true,
                paging: true,
                ajax: {
                    url: '{{ route("appointments.detailedDatatable") }}',
                    type: 'POST',
                },
                columns: [
                    {data: 'id', name: 'id'},
                        @if($role == 'Patient')
                    {
                        data: 'healthcare_professional.name',
                        name: 'healthcare_professional.name',
                        defaultContent: '',
                        render: function (data, type, row) {
                            return data ? data : 'N/A';
                        }
                    },
                        @else
                    {
                        data: 'patient.name',
                        name: 'patient.name',
                        defaultContent: '',
                        render: function (data, type, row) {
                            return data ? data : 'N/A';
                        }
                    },
                        @endif
                    {
                        data: 'status.status_name',
                        name: 'status.status_name',
                        defaultContent: '',
                        render: function (data, type, row) {
                            return data ? data : 'N/A';
                        }
                    },
                    {
                        data: 'cost.amount',
                        name: 'cost.amount',
                        render: function (data, type, row) {
                            if (data) {
                                return data;
                            } else {
                                return '0.00';
                            }
                        }
                    },
                    {data: 'appointment_time', name: 'appointment_time'},
                    {data: 'description', name: 'description', defaultContent: 'N/A'},
                    {
                        data: 'online_or_in_presence',
                        name: 'online_or_in_presence',
                        render: function (data, type, row) {
                            return data === true ? 'In Presence' : 'Online';
                        }
                    },
                    {data: 'notes', name: 'notes', defaultContent: 'N/A'},
                    {
                        data: 'schedule',
                        name: 'schedule',
                        render: function (data, type, row) {
                            var html = '';
                            if (data) {
                                html += '<div>';
                                html += '<strong>Start Time:</strong> ' + data.start_time + '<br>';
                                html += '<strong>End Time:</strong> ' + data.end_time + '<br>';
                                if (data.location) {
                                    html += '<strong>Location Name:</strong> ' + data.location.location_name + '<br>';
                                    html += '<strong>Location Address:</strong> ' + data.location.location_address;
                                }
                                html += '</div>';
                            } else {
                                html = 'N/A';
                            }
                            return html;
                        }
                    },
                    {
                        data: null,
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row) {
                            return '<a class="btn btn-primary btn btn-edit view-appointment" style="margin-right: 10px;" data-id="' + row.id + '">View</a>';

                        }
                    }
                ]

            });
        });

        $(document).on('click', '.view-appointment', function () {
            var appointmentId = $(this).data('id');
            window.location.href = '/appointments/view/' + appointmentId;
        });


        $(document).ready(function(){
            $('#doctor_id').change(function() {
                var doctor_id = $(this).val();
                if (doctor_id) {
                    $.get('/appointments/doctor/'+doctor_id+'/available-times', function(times) {
                        var options = '';
                        for(var i=0; i<times.length; i++) {
                            options += '<option value="'+ times[i] +'">' + times[i] + '</option>';
                        }
                        $('#appointment_time').html(options);
                    });
                }
            });
        });

        $(document).ready(function() {
            $('#online_in_presence').change(function() {
                var selectedOption = $(this).val();
                if (selectedOption == '1') {
                    $('#locationField').show();
                } else {
                    $('#locationField').hide();
                }
            });
        });
    </script>

@endsection
