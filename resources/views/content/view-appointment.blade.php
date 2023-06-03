@extends('layouts/contentLayoutMaster')

@section('title', 'Appointment Preview')

@section('vendor-style')
    <link rel="stylesheet" href="{{asset('vendors/css/pickers/flatpickr/flatpickr.min.css')}}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/extensions/sweetalert2.min.css')) }}">
@endsection
@section('page-style')
    <link rel="stylesheet" href="{{asset('css/base/plugins/forms/pickers/form-flat-pickr.css')}}">
    <link rel="stylesheet" href="{{asset('css/base/pages/app-invoice.css')}}">
    <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/extensions/ext-component-sweet-alerts.css')) }}">
@endsection

@section('content')
    <section class="invoice-preview-wrapper">
        <div class="row invoice-preview">
            <!-- Invoice -->
            <div class="col-xl-9 col-md-8 col-12">
                <div class="card invoice-preview-card">
                    <div class="card-body invoice-padding pb-0">
                        <!-- Header starts -->
                        <div class="d-flex justify-content-between flex-md-row flex-column invoice-spacing mt-0">
                            <div>
                                <div class="logo-wrapper">
                                    <svg
                                        viewBox="0 0 139 95"
                                        version="1.1"
                                        xmlns="http://www.w3.org/2000/svg"
                                        xmlns:xlink="http://www.w3.org/1999/xlink"
                                        height="24"
                                    >
                                        <defs>
                                            <linearGradient id="invoice-linearGradient-1" x1="100%" y1="10.5120544%"
                                                            x2="50%" y2="89.4879456%">
                                                <stop stop-color="#000000" offset="0%"></stop>
                                                <stop stop-color="#FFFFFF" offset="100%"></stop>
                                            </linearGradient>
                                            <linearGradient
                                                id="invoice-linearGradient-2"
                                                x1="64.0437835%"
                                                y1="46.3276743%"
                                                x2="37.373316%"
                                                y2="100%"
                                            >
                                                <stop stop-color="#EEEEEE" stop-opacity="0" offset="0%"></stop>
                                                <stop stop-color="#FFFFFF" offset="100%"></stop>
                                            </linearGradient>
                                        </defs>
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <g transform="translate(-400.000000, -178.000000)">
                                                <g transform="translate(400.000000, 178.000000)">
                                                    <path
                                                        class="text-primary"
                                                        d="M-5.68434189e-14,2.84217094e-14 L39.1816085,2.84217094e-14 L69.3453773,32.2519224 L101.428699,2.84217094e-14 L138.784583,2.84217094e-14 L138.784199,29.8015838 C137.958931,37.3510206 135.784352,42.5567762 132.260463,45.4188507 C128.736573,48.2809251 112.33867,64.5239941 83.0667527,94.1480575 L56.2750821,94.1480575 L6.71554594,44.4188507 C2.46876683,39.9813776 0.345377275,35.1089553 0.345377275,29.8015838 C0.345377275,24.4942122 0.230251516,14.560351 -5.68434189e-14,2.84217094e-14 Z"
                                                        style="fill: currentColor"
                                                    ></path>
                                                    <path
                                                        d="M69.3453773,32.2519224 L101.428699,1.42108547e-14 L138.784583,1.42108547e-14 L138.784199,29.8015838 C137.958931,37.3510206 135.784352,42.5567762 132.260463,45.4188507 C128.736573,48.2809251 112.33867,64.5239941 83.0667527,94.1480575 L56.2750821,94.1480575 L32.8435758,70.5039241 L69.3453773,32.2519224 Z"
                                                        fill="url(#invoice-linearGradient-1)"
                                                        opacity="0.2"
                                                    ></path>
                                                    <polygon
                                                        fill="#000000"
                                                        opacity="0.049999997"
                                                        points="69.3922914 32.4202615 32.8435758 70.5039241 54.0490008 16.1851325"
                                                    ></polygon>
                                                    <polygon
                                                        fill="#000000"
                                                        opacity="0.099999994"
                                                        points="69.3922914 32.4202615 32.8435758 70.5039241 58.3683556 20.7402338"
                                                    ></polygon>
                                                    <polygon
                                                        fill="url(#invoice-linearGradient-2)"
                                                        opacity="0.099999994"
                                                        points="101.428699 0 83.0667527 94.1480575 130.378721 47.0740288"
                                                    ></polygon>
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                </div>
                            </div>
                            <div class="mt-md-0 mt-2">
                                <h4 class="invoice-title">
                                    Appointment
                                    <span class="invoice-number">#{{$appointment['id']}}</span>
                                </h4>
                                <div class="invoice-date-wrapper">
                                    <p class="invoice-date-title">Appointment Date:</p>
                                    <p class="invoice-date">{{$appointment['appointment_time']}}</p>
                                </div>

                                <div class="invoice-date-wrapper">
                                    <p class="invoice-date-title">Cost:</p>
                                    <p class="invoice-date">{{!empty($appointment['cost'])?$appointment['cost']['amount']: 0 }}
                                        EUR</p>
                                </div>
                            </div>

                        </div>
                        <!-- Header ends -->
                    </div>

                    <hr class="invoice-spacing"/>

                    <!-- Address and Contact starts -->
                    <div class="card-body invoice-padding pt-0">
                        <div class="row invoice-spacing">
                            <div class="col-xl-3 p-0">
                                <h6 class="mb-2">Patient:</h6>
                                <p class="card-text mb-25">{{$appointment['patient']['name']}}</p>
                            </div>
                            <div class="col-xl-3 p-0">
                                <h6 class="mb-2">Appointment Status:</h6>
                                <p class="card-text mb-25">{{$appointment['status']['status_name']}}</p>
                            </div>
                            <div class="col-xl-3 p-0">
                                <h6 class="mb-2">Description:</h6>
                                <p class="card-text mb-25">{{$appointment['description']}}</p>
                            </div>
                            <div class="col-xl-3 p-0">
                                <h6 class="mb-2">Note:</h6>
                                <textarea disabled rows="1">{{$appointment['notes']}}</textarea>
                            </div>
                        </div>
                        <div class="row invoice-spacing">
                            <div class="col-xl-3 p-0">
                                <h6 class="mb-2">Online/In Presence:</h6>
                                <p class="card-text mb-25">{{$appointment['online_or_in_presence'] == true ?"In presence":'Online'}}</p>
                            </div>
                            <div class="col-xl-3 p-0">
                                <h6 class="mb-2">Scheduling Time</h6>
                                <p class="card-text mb-25">{{is_null( $appointment['schedule'])?
                                                            'Online':$appointment['schedule']['start_time'].' - '.
                                                               $appointment['schedule']['end_time']}}</p>
                            </div>
                            <div class="col-xl-3 p-0">
                                <h6 class="mb-2">Location:</h6>
                                <p class="card-text mb-25">{{is_null( $appointment['schedule'])?
                                                            'Online':$appointment['schedule']['location']['location_name']}}</p>
                            </div>
                            <div class="col-xl-3 p-0">
                                <h6 class="mb-2">Location Adress:</h6>
                                <p class="card-text mb-25">{{is_null( $appointment['schedule'])?
                                                            'Online':$appointment['schedule']['location']['location_address']}}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /Invoice -->

            <!-- Invoice Actions -->
            <div class="col-xl-3 col-md-4 col-12 invoice-actions mt-md-0 mt-2">
                <div class="card">
                    <div class="card-body">
                        @if($role == 'Doctor')
                            @if(!empty($appointment['status']['status_name']) && $appointment['status']['status_name'] == 'Not Accepted')
                                <button class="btn btn-primary w-100 mb-75"
                                        onclick="modifyAppointment({{ $appointment['id'] }},'accept')">
                                    Accept
                                </button>
                                <button class="btn btn-primary w-100 mb-75"
                                        onclick="modifyAppointment({{ $appointment['id'] }},'reject')">
                                    Reject
                                </button>
                            @endif
                            @if(!empty($appointment['status']['status_name']) && $appointment['status']['status_name'] == 'WIP')
                                <button class="btn btn-success w-100 mb-75"
                                        onclick="modifyAppointment({{ $appointment['id'] }},'complete')">
                                    Complete
                                </button>
                            @endif
                            @if(!empty($appointment['status']['status_name']) && $appointment['status']['status_name'] == 'Not Billed')
                                <button class="btn btn-success w-100 mb-75" data-bs-target="#modals-slide-in"
                                        data-bs-toggle="modal">
                                    Bill
                                </button>
                            @endif
                        @endif

                        @if($role == 'Patient')
                            @if(!empty($appointment['status']['status_name']) && $appointment['status']['status_name'] == 'Not Paid')
                                    <div class="container">
                                        <h2>Stripe Payment</h2>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <form action="{{ route('stripe.post') }}" method="post" id="payment-form">
                                                    @csrf
                                                    <div class="form-row">
                                                        <label for="card-element">
                                                            Enter your credit card information
                                                        </label>
                                                        <div id="card-element">
                                                            <!-- A Stripe Element will be inserted here. -->
                                                        </div>
                                                        <!-- Used to display form errors. -->
                                                        <div id="card-errors" role="alert"></div>
                                                    </div>
                                                    <br>
                                                    <button class="btn btn-primary btn-sm">Pay</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
            <!-- /Invoice Actions -->
        </div>
    </section>
    <div class="modal modal-slide-in new-user-modal fade" id="modals-slide-in">
        <div class="modal-dialog">
            <form class="add-new-user modal-content pt-0" id="form-modal-id">
                @csrf
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">×</button>
                <div class="modal-header mb-1">
                    <h5 class="modal-title" id="exampleModalLabel">Modify Profile</h5>
                </div>
                <div class="modal-body flex-grow-1">
                    <div class="mb-1">
                        <label class="form-label" for="amount">Amount</label>
                        <input type="text" class="form-control" id="amount" name="amount" required>
                        <div class="invalid-feedback">Please enter the amount.</div>
                    </div>
                    <div class="mb-1">
                        <label class="form-label" for="note">Note</label>
                        <input type="text" class="form-control" id="note" name="note" required>
                        <div class="invalid-feedback">Please enter the note.</div>
                    </div>

                    <button type="button" class="btn btn-primary"
                            onclick="modifyAppointment({{ $appointment['id'] }}, 'bill')">Update
                    </button>
                    <button type="reset" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                </div>
            </form>

        </div>
    </div>

@endsection

@section('vendor-script')
    <script src="{{asset('vendors/js/forms/repeater/jquery.repeater.min.js')}}"></script>
    <script src="{{asset('vendors/js/pickers/flatpickr/flatpickr.min.js')}}"></script>
    <script src="{{ asset(mix('vendors/js/extensions/sweetalert2.all.min.js')) }}"></script>
@endsection

@section('page-script')
    <script>
        function modifyAppointment(appointmentId, type) {
            // Get the form data
            const form = document.getElementById('form-modal-id');
            const formData = new FormData(form);

            // Append the 'type' parameter to the form data
            formData.append('type', type);

            fetch('/appointments/modify/' + appointmentId, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // Add CSRF token if required
                },
                body: formData
            })
                .then(response => {
                    // Handle the response
                    if (response.ok) {
                        // Appointment modified successfully
                        // You can perform any necessary actions here
                        location.reload(); // Reload the page
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


    <!-- Include the Stripe.js v3 library. This is required. -->
    <script src="https://js.stripe.com/v3/"></script>

    <script>
        // Create an instance of the Stripe object.
        // Make sure to change the key to your actual publishable key.
        var stripe = Stripe('pk_test_51NETr5FaKWtJZ9CcyXshTFRca3a2TuNVbkPaJNTAoXoc0HqDLLhSOMSQeK9VhXdpHoR1Q9evz7UZG0HJC5g9DESa001T1tPGT5');

        // Create an instance of elements.
        var elements = stripe.elements();

        // Create an instance of the card Element.
        var card = elements.create('card');

        // Add an instance of the card Element into the `card-element` <div>.
        card.mount('#card-element');

        // Handle real-time validation errors from the card Element.
        card.on('change', function(event) {
            var displayError = document.getElementById('card-errors');
            if (event.error) {
                displayError.textContent = event.error.message;
            } else {
                displayError.textContent = '';
            }
        });

        // Handle form submission.
        var form = document.getElementById('payment-form');
        form.addEventListener('submit', function(event) {
            event.preventDefault();

            stripe.createToken(card).then(function(result) {
                if (result.error) {
                    // Inform the user if there was an error.
                    var errorElement = document.getElementById('card-errors');
                    errorElement.textContent = result.error.message;
                } else {
                    // Send the token to your server.
                    stripeTokenHandler(result.token);
                }
            });
        });

        // Submit the form with the token ID.
        function stripeTokenHandler(token) {
            // Insert the token ID into the form so it gets submitted to the server
            var form = document.getElementById('payment-form');
            // Add Stripe Token to hidden input
            var hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'stripeToken');
            hiddenInput.setAttribute('value', token.id);
            form.appendChild(hiddenInput);
            // Submit the form
            form.submit();
        }
    </script>
    <script src="{{asset('js/scripts/pages/app-invoice.js')}}"></script>
@endsection
