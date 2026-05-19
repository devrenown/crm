<!-- Blog Section -->
<section class="px-3 bg-light py-5 px-md-4 px-lg-5" id="blog-section">

    <div class="container-fluid">

        <!-- Section Heading -->
        <div class="text-center mb-5">

            <span class="section-title-badge">
                Latest Articles
            </span>

            <h2 class="mt-4">
                <span class="text-gradient fw-bold">
                    OUR BLOGS
                </span>
            </h2>

            <p class="text-muted">
                Insights, updates, and resources from Renown System
            </p>

        </div>

        <!-- Blog Cards -->
        <div class="row g-4">

            @forelse($blogs as $blog)

                <div class="col-lg-4 col-md-6">

                    <div class="card border-0 shadow-sm h-100 blog-card">

                        <!-- Blog Image -->
                        <div class="blog-image-wrapper">

                            <img src="{{ asset($blog->featured_image) }}"
                                 class="card-img-top blog-image"
                                 alt="{{ $blog->title }}">

                        </div>

                        <!-- Blog Content -->
                        <div class="card-body d-flex flex-column">

                            <h4 class="blog-title">

                                {{ Str::limit($blog->title, 60) }}

                            </h4>

                            <p class="text-muted flex-grow-1">

                                {{ Str::limit(strip_tags($blog->content), 120) }}

                            </p>

                            <div class="d-flex justify-content-between align-items-center mt-3">

                                <small class="text-muted">

                                    {{ $blog->created_at->format('d M Y') }}

                                </small>

                                <a href="{{ route('blog.details', $blog->slug) }}"
                                   class="btn btn-primary border-white rounded-pill px-2 px-3 shadow-sm">

                                    Read More

                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            @empty

                <div class="col-12">

                    <div class="text-center">

                        <p class="text-muted mb-0">
                            No blogs available right now.
                        </p>

                    </div>

                </div>

            @endforelse

        </div>

        <!-- View All -->
        <div class="text-center mt-5">

            <a href="{{ route('blogs') }}"
               class="btn btn-outline-primary px-4 py-2">

                View All Blogs

            </a>

        </div>

    </div>

</section>


<style>

    .blog-card{
        border-radius:16px;
        overflow:hidden;
        transition:all .3s ease;
    }

    .blog-card:hover{
        transform:translateY(-6px);
    }

    .blog-image-wrapper{
        overflow:hidden;
        height:240px !important;
    }

    .blog-image{
        width:100%;
        height:100%;
        object-fit:cover;
        transition:transform .4s ease;
    }

    .blog-card:hover .blog-image{
        transform:scale(1.05);
    }

    .blog-title{
        font-size:1.25rem;
        font-weight:700;
        line-height:1.5;
        margin-bottom:12px;
    }

</style>
