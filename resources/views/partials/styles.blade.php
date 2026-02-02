<!-- Favicon -->
<meta charset="UTF-8">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link rel="shortcut icon" type="image/x-icon" href="{{Theme('favicon') ? asset('storage/settings/theme/' . Theme('favicon')) : Vite::asset('resources/assets/img/favicon.png') }}">

<link rel="stylesheet" href="{{ asset('js/plugins/bootstrap-datetimepicker/bootstrap-datetimepicker.min.css') }}">
<!-- Jquery Steps CSS -->
<link rel="stylesheet" href="{{ asset('assets/plugins/jquery-steps-master/demo/css/jquery.steps.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/fullcalendar.min.css') }}">

<!-- Pdf viewer -->
<!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/5.4.149/pdf_viewer.min.css" integrity="sha512-qbvpAGzPFbd9HG4VorZWXYAkAnbwKIxiLinTA1RW8KGJEZqYK04yjvd+Felx2HOeKPDKVLetAqg8RIJqHewaIg==" crossorigin="anonymous" referrerpolicy="no-referrer" /> -->

@vite([
    'resources/assets/css/bootstrap.min.css',
    'resources/assets/css/line-awesome.min.css',
    'resources/assets/css/material.css',
    'resources/assets/css/ckeditor.css',
    'resources/assets/plugins/bootstrap-tagsinput/bootstrap-tagsinput.css',
    'resources/assets/css/style.css',
    'resources/css/app.scss',
])
<!-- Vendor CSS -->
@stack('vendor-styles')
@yield('vendor-styles')
<!-- Custom CSS -->
@livewireStyles
@stack('page-styles')
@stack('style')