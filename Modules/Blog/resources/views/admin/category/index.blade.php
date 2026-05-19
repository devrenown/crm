@extends('layouts.app')
@include('blog::admin.layouts.layout')

@section('page-content')

<div class="content container-fluid">

    <div class="page-header d-flex justify-content-between">

        <h3 class="page-title">
            Blog Categories
        </h3>

        <a href="{{ route('blog.category.create') }}"
           class="btn btn-primary">

            Add Category

        </a>

    </div>

    <div class="card">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table table-bordered">

                    <thead>

                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Status</th>
                            <th width="150">Action</th>
                        </tr>

                    </thead>

                    <tbody>

                        @forelse($categories as $category)

                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>

                                <td>
                                    {{ $category->name }}
                                </td>

                                <td>
                                    {{ $category->slug }}
                                </td>

                                <td>

                                    @if($category->status == '1')

                                        <span class="badge bg-success">
                                            Active
                                        </span>

                                    @else

                                        <span class="badge bg-danger">
                                            Inactive
                                        </span>

                                    @endif

                                </td>

                                <td>

                                    <a href="{{ route('blog.category.edit', $category->id) }}"
                                       class="btn btn-sm btn-primary">

                                        Edit

                                    </a>

                                    <form action="{{ route('blog.category.destroy', $category->id) }}"
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

                                    No Categories Found

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            {{ $categories->links() }}

        </div>

    </div>

</div>

@endsection