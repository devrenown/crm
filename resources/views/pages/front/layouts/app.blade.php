<!DOCTYPE html>
<html lang="en">
    
@include('pages.front.layouts.head')


<!-- =================  For specific pages styles ===============  -->
@stack('styles')

@include('pages.front.layouts.navbar')

@yield('content')

@include('pages.front.layouts.footer')
@include('pages.front.blocks.demo-modal')

<!-- =================  For specific pages scripts ===============  -->

@include('pages.front.layouts.footer-script')

@stack('scripts')

</html>