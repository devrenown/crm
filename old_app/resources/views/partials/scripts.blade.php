@vite([
    'resources/js/app.js',
    'resources/assets/js/bootstrap.bundle.min.js',
    'resources/assets/js/jquery.slimscroll.min.js',
    'resources/js/ckeditor.js',
    'resources/assets/plugins/jquery-repeater/jquery.repeater.min.js',
    'resources/assets/js/app.js',
])
<!-- Vendor JS -->

@livewireScriptConfig 
@yield('vendor-scripts')
@stack('page-scripts')
{{-- <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> --}}
<!-- jQuery Validation plugin -->
<script defer src="{{ asset('assets/plugins/jquery-validator/dist/jquery.validate.min.js') }}"></script>
<!-- jQuery Steps -->
<script defer src="{{ asset('assets/plugins/jquery-steps-master/build/jquery.steps.min.js') }}"></script>

<script defer src="{{ asset('js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.js') }}"></script>

<script defer type="module" src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/5.4.149/pdf.min.mjs" ></script>
<script src="{{ asset('js/onboarding-form.js') }}"></script>

<script type="module">
    @if(count($errors) > 0)
        @foreach($errors->all() as $error)
            Toastify({
                text: "{{ $error }}",
                className: "danger",
            }).showToast();
        @endforeach
    @endif
    @if(Session::has('message'))
        var type = "{{ Session::get('alert-type', '') }}";
        switch (type) {
            case 'info':
                Toastify({
                    text: "{{ Session::get('message') }}",
                    className: "info",
                }).showToast();
                break;
            
            case 'success':
                Toastify({
                    text: "{{ Session::get('message') }}",
                    className: "success",
                }).showToast();
                break;
            
            case 'warning':
                Toastify({
                    text: "{{ Session::get('message') }}",
                    className: "warning",
                }).showToast();
                break;
            
            case 'error':
                Toastify({
                    text: "{{ Session::get('message') }}",
                    className: "danger",
                }).showToast();
                break;
            
            case 'danger':
                Toastify({
                    text: "{{ Session::get('message') }}",
                    className: "danger",
                }).showToast();
                break;
            
        }
    @endif
</script>