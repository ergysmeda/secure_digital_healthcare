
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
        <div class="row">
            <div class="col-lg-3 col-sm-6">
                <div class="card">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <h3 class="fw-bolder mb-75">{{$users['Admin']}}</h3>
                            <span>Admins</span>
                        </div>
                        <div class="avatar bg-light-primary p-50">
            <span class="avatar-content">
              <i data-feather="user" class="font-medium-4"></i>
            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="card">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <h3 class="fw-bolder mb-75">{{$users['Doctor']}}</h3>
                            <span>Doctors</span>
                        </div>
                        <div class="avatar bg-light-danger p-50">
            <span class="avatar-content">
              <i data-feather="user-plus" class="font-medium-4"></i>
            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-sm-6">
                <div class="card">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <h3 class="fw-bolder mb-75">{{$users['Patient']}}</h3>
                            <span>Patients</span>
                        </div>
                        <div class="avatar bg-light-success p-50">
            <span class="avatar-content">
              <i data-feather="user-check" class="font-medium-4"></i>
            </span>
                        </div>
                    </div>
                </div>
            </div><div class="col-lg-3 col-sm-6">
                <div class="card">
                    <div class="card-body d-flex align-items-center justify-content-between">
                        <div>
                            <!-- Button to open the modal -->
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" id = 'add-user' data-bs-target="#modals-slide-in">
                                Add User
                            </button>
                        </div>
                    </div>
                </div>
            </div>

        </div>


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
                <table id="userTable" class="table">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody></tbody> <!-- Replace the foreach loop with an empty tbody element -->
                </table>
        </div>
        <!-- list and filter end -->
    </section>
    <!-- Modal to add new user starts-->

    <div class="modal modal-slide-in new-user-modal fade" id="modals-slide-in">
        <div class="modal-dialog">
            <form class="add-new-user modal-content pt-0" id="form-modal-id" method="POST" action="{{ route('users.create') }}">
                @csrf
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
                <div class="modal-header mb-1">
                    <h5 class="modal-title" id="exampleModalLabel">Add User</h5>
                </div>
                <div class="modal-body flex-grow-1">
                    <div class="mb-1">
                        <label class="form-label" for="basic-icon-default-uname">Username</label>
                        <input
                            type="text"
                            class="form-control dt-uname"
                            placeholder="Web Developer"
                            name="username"
                            id="username"
                            value="{{ old('username') }}">
                        @error('username')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-1">
                        <label class="form-label" for="basic-icon-default-email">Email</label>
                        <input
                            type="text"
                            class="form-control dt-email"
                            placeholder="john.doe@example.com"
                            name="email"
                            id="email"
                            value="{{ old('email') }}">
                        @error('email')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-1">
                        <label class="form-label" for="role">User Role</label>
                        <select id="role" class="select2 form-select" name="role">
                            @foreach ($roles as $role)
                                <option value="{{$role['id']}}">{{$role['role_name']}}</option>
                            @endforeach

                        </select>
                        @error('role')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary me-1 data-submit">Submit</button>
                    <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>
        </div>
    </div>
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
            $('#userTable').DataTable({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                processing: true,
                serverSide: true,
                paging: true,
                ajax: {
                    url: '{{ route("users.datatables") }}',
                    type: 'POST',
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'name', name: 'name' },
                    { data: 'email', name: 'email' },
                    {
                        data: 'role.role_name', // Use dot notation to access nested property
                        name: 'role.role_name',
                        defaultContent: '', // Optional, provide default content if the value is missing
                    },
                    {
                        data: null,
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            var editButton = '<button class="btn btn-primary btn btn-edit" id="edit-user" style="margin-right: 10px;" data-id="' + row.id + '">Edit</button>';
                            var deleteButton = '<button class="btn btn-danger btn btn-delete" id="delete-user"  data-id="' + row.id + '">Delete</button>';
                            return editButton + deleteButton;
                        }
                    }
                ]
            });
        });

        // Edit button click event listener
        $(document).on('click', '#edit-user', function() {
            var userId = $(this).data('id');

            var rowData = $('#userTable').DataTable().row($(this).closest('tr')).data();

            // Code to open the modal and populate data for editing
            // Example:
            $('.modal-title').text('Edit User'); // Update the form title to "Edit User"
            $('#modals-slide-in').modal('show'); // Open the existing modal for editing

            // Populate data into the modal fields
            $('#username').val(rowData.name);
            $('#email').val(rowData.email);
            $('#role').val(rowData.role_id).trigger('change');
            $('#form-modal-id').attr('action', '{{ route("users.edit", ":id") }}'.replace(':id', userId)); // Change the form action route
        });

        // Delete button click event listener
        $(document).on('click', '.btn-delete', function() {
            var userId = $(this).data('id');
            // Show the confirmation dialog using SweetAlert
            Swal.fire({
                title: 'Are you sure?',
                text: 'You are about to delete this user.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Delete',
                cancelButtonText: 'Cancel',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Make a call to the 'users.delete' route
                    $.ajax({
                        url: '{{ route("users.delete", ":id") }}'.replace(':id', userId),
                        type: 'DELETE',
                        success: function(response) {
                            // Handle the success response
                            Swal.fire('Deleted!', 'The user has been deleted.', 'success');
                            $('#userTable').DataTable().ajax.reload();

                        },
                        error: function(xhr, status, error) {
                            Swal.fire('Error!', 'An error occurred while deleting the user.', 'error');
                        }
                    });
                }
            });
        });
        // Reset form and update title for insert
        $(document).on('click', '#add-user', function() {
            $('.modal-title').text('Add User'); // Update the form title to "Add User"
            $('#form-modal-id').trigger('reset'); // Reset the form fields
        });
    </script>
    <script type="text/javascript">
        @if (count($errors) > 0)
        $('#modals-slide-in').modal('show'); // Open the existing modal for editing
        @endif
    </script>
@endsection
