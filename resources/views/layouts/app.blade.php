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

    <!-- Global Document Viewer Modal -->
<div class="modal fade" id="globalDocumentModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">View Document</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0">
                <iframe id="globalDocumentFrame"
                        src=""
                        frameborder="0"
                        style="width:100%; height:85vh;">
                </iframe>
            </div>
        </div>
    </div>
</div>
@push('page-scripts')
<script>
    function openSecureDocument(url) {
        $('#globalDocumentFrame').attr('src', url);
        $('#globalDocumentModal').modal('show');
    }

    // Clear iframe when modal closes
    $(document).on('hidden.bs.modal', '#globalDocumentModal', function () {
        $('#globalDocumentFrame').attr('src', '');
    });

    $(document).on('change', '.status-dropdown', function () {
        let selectedVal = $(this).val();

        let parentRow = $(this).closest('.document-block');

        if (selectedVal == '2') { 
            parentRow.find('.remarks-container').slideDown();
            parentRow.find('input[name="remarks"]').attr('required', true);
        } else {
            parentRow.find('.remarks-container').slideUp();
            parentRow.find('input[name="remarks[]"]').val('');
            parentRow.find('input[name="remarks[]"]').attr('required', false);
        }
    });

    window.routes = {
        onboardDeleteIdentityId: "{{ route('onboard.deleteIdentityId') }}",
    }
</script>
@endpush

    <!-- <script>
        function openSecureDocument(url) {
            document.getElementById('globalDocumentFrame').src = url;
            let modal = new bootstrap.Modal(document.getElementById('globalDocumentModal'));
            modal.show();
        }

        // Clear iframe when modal closes
        document.getElementById('globalDocumentModal')
            .addEventListener('hidden.bs.modal', function () {
                document.getElementById('globalDocumentFrame').src = '';
            });
    </script> -->

@endsection