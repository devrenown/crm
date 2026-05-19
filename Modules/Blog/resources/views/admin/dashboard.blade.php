@extends('layouts.app')

@include('blog::admin.layouts.layout')

@section('page-content')

<div class="content container-fluid">

    <!-- Page Header -->
    <div class="page-header">

        <div class="row align-items-center">

            <div class="col">

                <h3 class="page-title">
                    Dashboard
                </h3>

                <ul class="breadcrumb">

                    <li class="breadcrumb-item">
                        <a href="{{ route('blog.index') }}">
                            Blog Panel
                        </a>
                    </li>

                    <li class="breadcrumb-item active">
                        Dashboard
                    </li>

                </ul>

            </div>

        </div>

    </div>
    <!-- /Page Header -->

    <!-- Stats -->
    <div class="row">

        <div class="col-md-6 col-xl-3">

            <div class="card dash-widget">

                <div class="card-body">

                    <span class="dash-widget-icon">
                        <i class="fa fa-file-text"></i>
                    </span>

                    <div class="dash-widget-info">

                        <h3>{{ @$totalBlogs ?? 0 }}</h3>

                        <span>Total Blogs</span>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-md-6 col-xl-3">

            <div class="card dash-widget">

                <div class="card-body">

                    <span class="dash-widget-icon">
                        <i class="fa fa-check-circle"></i>
                    </span>

                    <div class="dash-widget-info">

                        <h3>{{ @$publishedBlogs ?? 0 }}</h3>

                        <span>Published</span>

                    </div>

                </div>

            </div>

        </div>

        <div class="col-md-6 col-xl-3">

            <div class="card dash-widget">

                <div class="card-body">

                    <span class="dash-widget-icon">
                        <i class="fa fa-pencil"></i>
                    </span>

                    <div class="dash-widget-info">

                        <h3>{{ @$draftBlogs ?? 0 }}</h3>

                        <span>Drafts</span>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <!-- Recent Blogs -->
    <div class="card">

        <div class="card-header">

            <div class="d-flex justify-content-between align-items-center">

                <h4 class="card-title mb-0">
                    Recent Blogs
                </h4>

                <a href="{{ route('blog.index') }}"
                   class="btn btn-primary btn-sm">

                    View All

                </a>

            </div>

        </div>

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover">

                    <thead>

                        <tr>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Action</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse(@$recentBlogs ?? [] as $blog)

                            <tr>

                                <td>
                                    {{ @$blog->title }}
                                </td>

                                <td>

                                    @if(@$blog->status == 'published')

                                        <span class="badge bg-success">
                                            Published
                                        </span>

                                    @else

                                        <span class="badge bg-warning">
                                            Draft
                                        </span>

                                    @endif

                                </td>

                                <td>
                                    {{ @$blog->created_at->format('d M Y') }}
                                </td>

                                <td>

                                    <a href="{{ route('blog.edit', @$blog->id) }}"
                                       class="btn btn-sm btn-outline-primary">

                                        Edit

                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="4"
                                    class="text-center py-4">

                                    No blogs found

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

@endsection
