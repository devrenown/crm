@extends(FRONT_LAYOUT_PATH)

@section('content')

    @include('pages.front.blocks.banner')
    @include('pages.front.blocks.features')
    @include('pages.front.blocks.platform')
    @include('pages.front.blocks.why-choose-us')
    @include('pages.front.blocks.trail')
    @include('pages.front.blocks.how-it-works')
    @include('pages.front.blocks.pricing')
    {{-- @include('pages.front.blocks.counter')
    @include('pages.front.blocks.security') --}}
    @include('pages.front.blocks.testimony')
    @include('pages.front.blocks.faq')
    @include('pages.front.blocks.contact')
    @include('pages.front.blocks.growing-business')
    
    @if(isset($blogs) && count($blogs))
    @include('pages.front.blocks.blog')
    @endif
    

    <!-- Scroll to Top Button -->
    <button id="scrollTopBtn">&#8679;</button>

@endsection

<!-- =================  For styles =============== -->
@push('styles')

 <style>
    .text-primary {
        color: #1A194A !important;
    }

    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        filter: invert(1);       
    }

    .modal button.btn-close {
        background: none;
    }

    .btn-outline-primary:hover {
        background-color: #0B5ED7 !important;
        border-color: #0B5ED7 !important;
        color: #ffffff !important;
    }

    .btn-primary {
        border-color: #ffffff;
    }

    .btn-primary:hover {
       border-color: #ffffff; 
    }
 </style>

@endpush

<!-- =================  For scripts ===============  -->
@push('scripts')

@endpush
