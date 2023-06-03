
@extends('layouts/contentLayoutMaster')


@section('title', 'User List')

@section('vendor-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap5.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap5.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap5.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/rowGroup.bootstrap5.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/sweetalert2.min.css')) }}">
@endsection

@section('page-style')
    {{-- Page Css files --}}
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
@endsection

@section('content')
    <!-- users list start -->
    <section class="app-user-list">


        <!-- list and filter start -->
        <div class="card">
            <div class="card-body border-bottom">
                <h4 class="card-title">Search & Filter</h4>
                <div class="row">
                    <div class="col-md-4 user_role"></div>
                    <div class="col-md-4 user_status"></div>
                </div>
            </div>
            <div class="card-datatable table-responsive pt-0">
                <table id="invoicesTable" class="table">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Patient</th>
                        <th>Amount</th>
                        <th>Payment Time</th>
                        <th>Appointment Time</th>
                        <th>Description</th>
                        <th>Online/In Presence</th>
                        <th>Notes</th>
                        <th>Scheduling Data</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody></tbody> <!-- Replace the foreach loop with an empty tbody element -->
                </table>
            </div>
            <!-- list and filter end -->
    </section>
    <!-- Modal to add new user starts-->

    <!-- users list ends -->
@endsection

@section('vendor-script')
    {{-- Vendor js files --}}
    <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
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
    <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/cleave/cleave.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/cleave/addons/cleave-phone.us.js')) }}"></script>


@endsection

@section('page-script')
    {{-- Page js files --}}
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>
    <script>

        $(document).ready(function() {
            $('#invoicesTable').DataTable({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                processing: true,
                serverSide: true,
                paging: true,
                ajax: {
                    url: '{{ route("invoices.datatable") }}',
                    type: 'POST',
                },
                columns: [
                    { data: 'id', name: 'id' },
                    {
                        data: 'patient.name',
                        name: 'patient.name',
                        defaultContent: '',
                        render: function(data, type, row) {
                            return data ? data : 'N/A';
                        }
                    },
                    {
                        data: 'amount',
                        name: 'amount',
                        render: function(data, type, row) {
                            if (data) {
                                return data;
                            } else {
                                return '0.00';
                            }
                        }
                    },
                    { data: 'payment_time', name: 'payment_time' },
                    { data: 'cost.appointment.appointment_time', name: 'cost.appointment.appointment_time' },
                    { data: 'cost.appointment.description', name: 'cost.appointment.description', defaultContent: 'N/A' },
                    {
                        data: 'cost.appointment.online_or_in_presence',
                        name: 'cost.appointment.online_or_in_presence',
                        render: function(data, type, row) {
                            return data === true ? 'In Presence' : 'Online';
                        }
                    },

                    { data: 'cost.appointment.notes', name: 'cost.appointment.notes', defaultContent: 'N/A' },
                    {
                        data: 'cost.appointment.schedule',
                        name: 'cost.appointment.schedule',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
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
                        render: function(data, type, row) {
                            return '<a href="/invoices/getBill/'+ row.id+'" class="btn btn-primary btn btn-edit download-bill" style="margin-right: 10px;" data-bill="' + row.id + '">View</a>';

                        }
                    }
                ]

            });
        });

    </script>
@endsection
