@extends(FRONT_LAYOUT_PATH)

@section('content')

<section class="blog-page py-5">

    <div class="container">

        <!-- Breadcrumb -->
        <div class="blog-breadcrumb mb-5">

            <a href="{{ url('/') }}">Home</a>
            <span class="mx-2"><i class="fa fa-angle-right"></i></span>
            <span>Blog</span>
        </div>

        <div class="row">

            <!-- Blog List -->
            <div class="col-lg-8">
                
                <div class="row g-4">

                    @forelse($blogs as $blog)

                        <div class="col-md-6">

                            <div class="blog-card">

                                <!-- Image -->
                                <div class="blog-image-wrapper">

                                    <a href="{{ route('blog.details', $blog->slug) }}">

                                        <img src="{{ asset($blog->featured_image) }}"
                                             alt="{{ $blog->title }}"
                                             class="blog-image">
                                    </a>

                                </div>

                                <!-- Content -->
                                <div class="blog-card-body">

                                    <!-- Meta -->
                                    <div class="blog-meta">

                                        <span>
                                            <i class="fa fa-calendar"></i>
                                            {{ $blog->created_at->format('M d, Y') }}
                                        </span>

                                        <span>
                                            <i class="fa fa-clock-o"></i>4 min read
                                        </span>

                                    </div>

                                    <!-- Title -->
                                    <h3 class="blog-title">
                                        <a href="{{ route('blog.details', $blog->slug) }}">{{ $blog->title }}</a>
                                    </h3>

                                    <!-- Read More -->
                                    <a href="{{ route('blog.details', $blog->slug) }}" class="read-more-btn">Read More
                                        <i class="fa fa-arrow-right"></i>
                                    </a>

                                </div>

                            </div>

                        </div>

                    @empty

                        <div class="col-12">
                            <div class="empty-blog">
                                <h4> No Blogs Found </h4>
                            </div>
                        </div>
                    @endforelse

                </div>

                <!-- Pagination -->
                <div class="mt-5">
                    {{ $blogs->links() }}
                </div>

            </div>

            <!-- Sidebar -->
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
                                    <a href="{{ route('blogs', $category->slug) }}">{{ $category->name }}</a>
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
                            <p>No Recent Posts </p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('styles')

<style>

    .blog-page{
        background:#f8f8f8;
    }

    .blog-breadcrumb{
        font-size:15px;
        color:#777;
    }

    .blog-breadcrumb a{
        color:#1e293b;
        text-decoration:none;
        font-weight:500;
    }

    .blog-card{
        background:#fff;
        border-radius:18px;
        overflow:hidden;
        box-shadow:0 4px 20px rgba(0,0,0,.05);
        transition:.3s;
        height:100%;
    }

    .blog-card:hover{
        transform:translateY(-4px);
    }

    .blog-image-wrapper{
        overflow:hidden;
    }

    .blog-image{
        width:100%;
        height:200px;
        object-fit:cover;
        transition:.4s;
    }

    .blog-card:hover .blog-image{
        transform:scale(1.05);
    }

    .blog-card-body{
        padding:22px;
    }

    .blog-meta{
        display:flex;
        flex-wrap:wrap;
        gap:14px;
        font-size:13px;
        color:#777;
        margin-bottom:15px;
    }

    .blog-meta span i{
        margin-right:6px;
    }

    .blog-title{
        font-size:16px;
        line-height:1.5;
        margin-bottom:18px;
    }

    .blog-title a{
        color:#111827;
        text-decoration:none;
        font-weight:700;
    }

    .blog-title a:hover{
        color:#0D6EFD;
    }

    .read-more-btn{
        color:#0D6EFD;
        text-decoration:none;
        font-weight:600;
        display:inline-flex;
        align-items:center;
        gap:8px;
    }

    .read-more-btn:hover{
        color:#111827;
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

    @media(max-width:991px){

        .sidebar-widget{
            margin-top:30px;
        }

    }

    @media(max-width:767px){

        .blog-title{
            font-size:22px;
        }

        .widget-title{
            font-size:22px;
        }

    }

</style>

@endpush