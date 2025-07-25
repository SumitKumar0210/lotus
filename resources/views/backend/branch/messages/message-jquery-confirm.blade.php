@if (Session::has('success'))
    <script>
        $(document).ready(function() {
            $.alert({
                title: "{{ __('Success') }}",
                content: "{{ Session::get('success') }}",
                icon: 'fas fa-smile',
                animation: 'scale',
                closeAnimation: 'scale',
                theme: 'supervan',
                autoClose: 'Close|3000',
                type: 'green',
                buttons: {
                    Close: {
                        btnClass: 'btn-green'
                    }
                }
            });
        });
    </script>
@endif

@if (Session::has('error'))
    <script>
        $(document).ready(function() {
            $.alert({
                title: "{{ __('Invalid') }}",
                content: "{{ Session::get('error') }}",
                icon: 'fas fa-frown',
                animation: 'scale',
                closeAnimation: 'scale',
                theme: 'supervan',
                autoClose: 'Close|3000',
                type: 'red',
                buttons: {
                    Close: {
                        btnClass: 'btn-red'
                    }
                }
            });
        });
    </script>
@endif
@if ($errors->any())
    <script>
        $(document).ready(function() {
            $.alert({
                title: "{{ __('Invalid') }}",
                content: '<div class="card-panel teal lighten-2">' +
                    '        <ul>' +
                    @foreach ($errors->all() as $error)' +
                        ' <li>{{ $error }}</li>' +
                        ' @endforeach' +
                    '        </ul>' +
                    '    </div>',
                icon: 'fas fa-frown',
                animation: 'scale',
                closeAnimation: 'scale',
                theme: 'supervan',
                autoClose: 'Close|3000',
                type: 'red',
                buttons: {
                    Close: {
                        btnClass: 'btn-red'
                    }
                }
            });
        });
    </script>
@endif
