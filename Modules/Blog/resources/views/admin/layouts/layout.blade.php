<style>

    .sidebar-menu ul li a.active,
    .sidebar-menu ul li a:hover{
        background:#0d6efd !important;
        color:#fff !important;
        border-radius:8px;
    }
    
    .header .header-left {
        padding: 10px 20px !important;
    }
    
    .header #toggle_btn {
        padding: 20px 10px !important;
    }
    
    .header .user-img img {
        width: 50px !important;
        height: 50px !important;
    }

</style>

@section('sidebar')
    @include('blog::admin.layouts.sidebar')
@endsection

@section('header')
    @include('blog::admin.layouts.header')
@endsection

@push('page-scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>
<script>
    ClassicEditor
    .create(document.querySelector('#blog-editor'))
    .catch(error => {
        console.error(error);
    });
</script>

@endpush