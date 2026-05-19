@extends('layouts.app')

@include('blog::admin.layouts.layout')

@section('page-content')

<div class="content container-fluid">

    <div class="page-header">

        <div class="row align-items-center">

            <div class="col">
                <h3 class="page-title">Blogs</h3>
            </div>

            <div class="col-auto">

                <a href="{{ route('blog.create') }}"
                   class="btn btn-primary">

                    <i class="fa fa-plus"></i>
                    Create Blog

                </a>

            </div>

        </div>

    </div>

    <div class="card">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-hover">

                    <thead>

                        <tr>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Status</th>
                            <th>Created By</th>
                            <th>Created</th>
                            <th width="180">Action</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($blogs as $blog)

                            <tr>

                                <td width="80">

                                    <img src="{{ asset($blog->featured_image) }}"
                                         class="img-fluid rounded"
                                         width="60">

                                </td>

                                <td>
                                    {{ $blog->title }}
                                </td>
                                
                                <td>{{ $blog->category?->name }}</td>

                                <td>

                                    @if($blog->status == 'published')

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
                                    {{ $blog->createdBy->fullname ?? '' }}
                                </td>

                                <td>
                                    {{ $blog->created_at->format('d M Y') }}
                                </td>

                                <td>

                                    <a href="{{ route('blog.show', $blog->id) }}"
                                       class="btn btn-sm btn-info">

                                        View

                                    </a>

                                    <a href="{{ route('blog.edit', $blog->id) }}"
                                       class="btn btn-sm btn-primary">

                                        Edit

                                    </a>

                                    <form action="{{ route('blog.destroy', $blog->id) }}"
                                          method="POST"
                                          class="d-inline">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-sm btn-danger">

                                            Delete

                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="5"
                                    class="text-center">
                                    No blogs found
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            {{ $blogs->links() }}

        </div>

    </div>

</div>

@endsection