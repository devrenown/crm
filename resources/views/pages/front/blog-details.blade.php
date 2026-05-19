@extends(FRONT_LAYOUT_PATH)

@section('content')

<!-- Blog Details Section -->
<section class="blog-details-section py-5 mt-5">

    <div class="container">

        <div class="row justify-content-center">

            <div class="blog-wrapper col-lg-8">

                <!-- Blog Header -->
                <div class="blog-header">

                    <h1 class="blog-title text-capitalize">

                        {{ $blog->title }}

                    </h1>

                </div>

                <!-- Featured Image -->
                <div class="featured-image-box">

                    <img src="{{ asset($blog->featured_image) }}"
                         class="img-fluid featured-image"
                         alt="{{ $blog->title }}">

                </div>

                <!-- Blog Content -->
                <div class="blog-content">

                    {!! $blog->content !!}

                </div>
                
                <!-- Author -->
                <div class="text-end">

                        <!-- Social -->
                        {{-- <div class="blog-social d-none d-md-flex">
                            <a href="#">
                                <i class="fa fa-facebook"></i>
                            </a>

                            <a href="#">
                                <i class="fa fa-twitter"></i>
                            </a>

                            <a href="#">
                                <i class="fa fa-link"></i>
                            </a>

                        </div> --}}
                        
                        <small class="text-muted">
                            {{ $blog->created_at->format('M d, Y') }}
                        </small>

                    </div>

            </div>
            <div class="col-lg-4">
                <div class="position-sticky" style="top: 95px;">

                    <!-- Categories -->
                    <div class="sidebar-widget">
    
                        <h4 class="widget-title">
                            Categories
                        </h4>
    
                        <ul class="category-list">
    
                            @forelse($categories as $category)
    
                                <li>
    
                                    <a href="{{ route('blogs', $category->slug) }}">
    
                                        {{ $category->name }}
    
                                    </a>
    
                                    <span>
    
                                        {{ $category->blogs_count ?? 0 }}
    
                                    </span>
    
                                </li>
    
                            @empty
    
                                <li>
                                    No Categories
                                </li>
    
                            @endforelse
    
                        </ul>
    
                    </div>
    
                    <!-- Recent Posts -->
                    <div class="sidebar-widget mt-4">
    
                        <h4 class="widget-title">
                            Recent Posts
                        </h4>
    
                        @forelse($recentBlogs as $recent)
    
                            <div class="recent-post">
    
                                <a href="{{ route('blog.details', $recent->slug) }}"
                                   class="recent-image">
    
                                    <img src="{{ asset($recent->featured_image) }}"
                                         alt="{{ $recent->title }}">
    
                                </a>
    
                                <div class="recent-content">
    
                                    <h6>
    
                                        <a href="{{ route('blog.details', $recent->slug) }}">
    
                                            {{ \Illuminate\Support\Str::limit($recent->title, 45) }}
    
                                        </a>
    
                                    </h6>
    
                                    <span>
    
                                        {{ $recent->created_at->format('M d, Y') }}
    
                                    </span>
    
                                </div>
    
                            </div>
    
                        @empty
    
                            <p>
                                No Recent Posts
                            </p>
    
                        @endforelse
    
                    </div>
                
                </div>

            </div>
        </div>

    </div>

</section>

<!-- Related Blogs -->
@if(isset($relatedBlogs) && count($relatedBlogs))

<section class="related-blog-section pb-5">

    <div class="container">

        <div class="row">

            <div class="col-lg-12">

                <div class="section-heading mb-4">

                    <h2 class="fw-bold">
                        Related Articles
                    </h2>

                </div>

            </div>

        </div>

        <div class="row g-4">

            @foreach($relatedBlogs as $related)

                <div class="col-lg-4 col-md-6">

                    <div class="related-blog-card">

                        <div class="related-blog-image">

                            <img src="{{ asset($related->featured_image) }}"
                                 class="img-fluid"
                                 alt="{{ $related->title }}">

                        </div>

                        <div class="related-blog-content">

                            <h5>

                                {{ Str::limit($related->title, 60) }}

                            </h5>

                            <p>

                                {{ Str::limit(strip_tags($related->content), 90) }}

                            </p>

                            <a href="{{ route('website.blog.show', $related->slug) }}"
                               class="read-more-btn">

                                Read More

                            </a>

                        </div>

                    </div>

                </div>

            @endforeach

        </div>

    </div>

</section>

@endif

<!-- Scroll Button -->
<button id="scrollTopBtn">

    <i class="fa fa-angle-up"></i>

</button>

@endsection


<!-- ================= Styles ================= -->
@push('styles')

<style>

    body{
        background:#f3f4f6;
    }

    .blog-details-section{
        padding-top:100px;
    }

    .blog-wrapper{
        background:#fff;
        border-radius:24px;
        padding:45px;
        box-shadow:0 10px 35px rgba(0,0,0,.05);
    }

    .logo-circle{
        margin-right:8px;
    }

    .blog-title{
        font-size:2em;
        font-weight:800;
        line-height:1.3;
        margin:0px 0 35px 0px;
        color:#111827;
    }

    .blog-social{
        display:flex;
        gap:12px;
    }

    .blog-social a{
        width:42px;
        height:42px;
        border-radius:50%;
        background:#f3f4f6;
        display:flex;
        align-items:center;
        justify-content:center;
        color:#6b7280;
        transition:.3s;
        text-decoration:none;
    }

    .blog-social a:hover{
        background:#4f46e5;
        color:#fff;
    }

    .featured-image-box{
        margin-top:40px;
        border-radius:22px;
        overflow:hidden;
    }

    .featured-image{
        width:100%;
        max-height:520px;
        object-fit:cover;
    }

    .blog-content{
        margin-top:50px;
        color:#374151;
        font-size:19px;
        line-height:2;
    }

    .blog-content h1,
    .blog-content h2,
    .blog-content h3,
    .blog-content h4,
    .blog-content h5,
    .blog-content h6{
        color:#111827;
        font-weight:700;
        margin-top:40px;
        margin-bottom:18px;
    }

    .blog-content p{
        margin-bottom:28px;
    }

    .blog-content img{
        max-width:100%;
        border-radius:16px;
        margin:25px 0;
    }

    .blog-content ul,
    .blog-content ol{
        padding-left:25px;
        margin-bottom:25px;
    }

    .related-blog-card{
        background:#fff;
        border-radius:20px;
        overflow:hidden;
        box-shadow:0 8px 25px rgba(0,0,0,.05);
        transition:.3s;
        height:100%;
    }

    .related-blog-card:hover{
        transform:translateY(-5px);
    }

    .related-blog-image img{
        width:100%;
        height:230px;
        object-fit:cover;
    }

    .related-blog-content{
        padding:25px;
    }

    .related-blog-content h5{
        font-weight:700;
        line-height:1.5;
        margin-bottom:15px;
    }

    .related-blog-content p{
        color:#6b7280;
        margin-bottom:20px;
    }

    .read-more-btn{
        text-decoration:none;
        color:#4f46e5;
        font-weight:600;
    }
    
    .sidebar-widget{
        background:#fff;
        border-radius:18px;
        padding:28px;
        box-shadow:0 4px 20px rgba(0,0,0,.05);
    }

    .widget-title{
        font-size:28px;
        font-weight:700;
        margin-bottom:25px;
        color:#111827;
        border-bottom:1px solid #eee;
        padding-bottom:15px;
    }

    .category-list{
        padding:0;
        margin:0;
        list-style:none;
    }

    .category-list li{
        display:flex;
        justify-content:space-between;
        align-items:center;
        margin-bottom:18px;
    }

    .category-list li a{
        text-decoration:none;
        color:#374151;
        font-size:18px;
    }

    .category-list li span{
        width:28px;
        height:28px;
        border-radius:50%;
        background:#fff3eb;
        color:#ff6b00;
        font-size:13px;
        display:flex;
        align-items:center;
        justify-content:center;
        font-weight:600;
    }

    .recent-post{
        display:flex;
        gap:14px;
        margin-bottom:20px;
    }

    .recent-image img{
        width:220px;
        height:90px;
        object-fit:cover;
        border-radius:10px;
    }

    .recent-content h6{
        margin-bottom:6px;
        line-height:1.5;
    }

    .recent-content h6 a{
        text-decoration:none;
        color:#111827;
        font-size:17px;
        font-weight:600;
    }

    .recent-content span{
        color:#888;
        font-size:14px;
    }

    .empty-blog{
        background:#fff;
        padding:80px 20px;
        border-radius:18px;
        text-align:center;
    }

    #scrollTopBtn{
        position:fixed;
        right:25px;
        bottom:25px;
        width:50px;
        height:50px;
        border:none;
        border-radius:50%;
        background:#4f46e5;
        color:#fff;
        display:none;
        z-index:999;
        box-shadow:0 10px 20px rgba(79,70,229,.3);
    }

    @media(max-width:991px){

        .blog-wrapper{
            padding:30px;
        }

        .blog-title{
            font-size:38px;
        }

        .blog-content{
            font-size:17px;
        }

    }

    @media(max-width:767px){

        .blog-wrapper{
            padding:22px;
            border-radius:18px;
        }

        .blog-title{
            font-size:32px;
            margin-top:35px;
        }

        .blog-content{
            font-size:16px;
            line-height:1.9;
        }

        .featured-image{
            max-height:320px;
        }

    }

</style>

@endpush


<!-- ================= Scripts ================= -->
@push('scripts')

<script>

    $(window).scroll(function(){

        if($(this).scrollTop() > 300){

            $('#scrollTopBtn').fadeIn();

        }else{

            $('#scrollTopBtn').fadeOut();

        }

    });

    $('#scrollTopBtn').click(function(){

        $('html, body').animate({
            scrollTop:0
        }, 600);

    });

</script>

@endpush