@extends('layouts.blank')

@section('content')
    <!-- Header -->
    @include('partials.header')
    <!-- /Header -->
    <!-- Sidebar -->
    
    @if (!request()->is('onboarding/welcome/*') && !request()->is('onboarding') && !request()->is('onboarding/start/*')) 

        @hasSection('sidebar')
            @yield('sidebar')
        @else
            @include('partials.sidebar')
        @endif

    @endif 
    <!-- /Sidebar -->
    <!-- Page Wrapper -->
    <div class="page-wrapper">
        <div id="loader-wrapper">
            <div id="loader">
                <div class="loader-ellips">
                    <span class="loader-ellips__dot"></span>
                    <span class="loader-ellips__dot"></span>
                    <span class="loader-ellips__dot"></span>
                    <span class="loader-ellips__dot"></span>
                </div>
            </div>
        </div>
        <!-- Page Content -->
        @yield('page-content')
        <!-- /Page Content -->
    </div>
    <!-- /Page Wrapper -->
    <!-- Delete Modal -->
    <div class="modal custom-modal fade" id="GeneralDeleteModal" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="form-header">
                        <h3 class="modal_title">Delete</h3>
                        <p class="modal_message">Are you sure want to delete?</p>
                    </div>
                    <form method="post">
                        @method('DELETE')
                        @csrf
                        <div class="modal-btn delete-action">
                            <input type="hidden" name="id">
                            <div class="row">
                                <div class="col-6">
                                    <a href="javascript:void(0);" data-bs-dismiss="modal"
                                        class="btn btn-primary cancel-btn">{{ __('Cancel') }}</a>
                                </div>
                                <div class="col-6">
                                    <button type="submit"
                                        class="btn btn-primary continue-btn w-100">{{ __('Delete') }}</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- /Delete Modal -->

    {{-- Global Secure Document Modal --}}
    <div class="modal fade" id="globalDocumentModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Document Preview</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body p-0" style="height:80vh;">
                    <iframe id="globalDocumentFrame"
                            style="width:100%; height:100%; border:0;">
                    </iframe>
                </div>
            </div>
        </div>
    </div>

    <script>
       $(document).ready(function() {
    window.openSecureDocument = function(url) {
        $('#globalDocumentFrame').attr('src', url);
        let modal = new bootstrap.Modal($('#globalDocumentModal')[0]);
        modal.show();
    };

    $('#globalDocumentModal').on('hidden.bs.modal', function() {
        $('#globalDocumentFrame').attr('src', '');
    });
});
    </script>

@endsection