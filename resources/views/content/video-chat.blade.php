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

            <div class="col-xl-12 col-lg-12 col-md-12 order-0 order-md-1">
                <div class="card">
                    @if(!isset($error))
                    <div id="video-container"></div>

                    @else
                        <div>
                            <p class="error">{{$error}}</p>
                        </div>
                    @endif

                </div>
                <!-- /Project table -->
            </div>
            <!--/ User Content -->
        </div>
    </section>
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
    @if(!isset($error)){
    <script src="//media.twiliocdn.com/sdk/js/video/releases/2.0.0-beta10/twilio-video.min.js"></script>

    <script>

        const Video = Twilio.Video;


        Video.connect('{{ $accessToken }}', { name: '{{$roomName}}' }).then(room => {
            console.log('Connected to Room "%s"', room.name);

            room.participants.forEach(participantConnected);
            room.on('participantConnected', participantConnected);

            room.on('participantDisconnected', participantDisconnected);
            room.once('disconnected', error => room.participants.forEach(participantDisconnected));
        });

        function participantConnected(participant) {
            console.log('Participant "%s" connected', participant.identity);

            const div = document.createElement('div');
            div.id = participant.sid;
            div.innerText = participant.identity;

            participant.on('trackSubscribed', track => trackSubscribed(div, track));
            participant.on('trackUnsubscribed', trackUnsubscribed);

            participant.tracks.forEach(publication => {
                if (publication.isSubscribed) {
                    trackSubscribed(div, publication.track);
                }
            });

            document.getElementById('video-container').appendChild(div);
        }


        function participantDisconnected(participant) {
            console.log('Participant "%s" disconnected', participant.identity);
            document.getElementById(participant.sid).remove();
        }

        function trackSubscribed(div, track) {
            div.appendChild(track.attach());
        }

        function trackUnsubscribed(track) {
            track.detach().forEach(element => element.remove());
        }
    </script>
    @endif
@endsection
