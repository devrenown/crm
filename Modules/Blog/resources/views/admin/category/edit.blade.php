@extends('layouts.app')
@include('blog::admin.layouts.layout')

@section('page-content')

<div class="content container-fluid">

    <div class="page-header">

        <h3 class="page-title">
            Edit Category
        </h3>

    </div>

    <div class="card">

        <div class="card-body">

            <form action="{{ route('blog.category.update', $category->id) }}"
                  method="POST">

                @csrf
                @method('PUT')

                <div class="mb-3">

                    <label class="form-label">
                        Name
                    </label>

                    <input type="text"
                           name="name"
                           value="{{ $category->name }}"
                           class="form-control">

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Slug
                    </label>

                    <input type="text"
                           name="slug"
                           value="{{ $category->slug }}"
                           class="form-control">

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Status
                    </label>

                    <select name="status"
                            class="form-control">

                        <option value="1"
                            {{ $category->status == '1' ? 'selected' : '' }}>

                            Active

                        </option>

                        <option value="0"
                            {{ $category->status == '0' ? 'selected' : '' }}>

                            Inactive

                        </option>

                    </select>

                </div>

                <button type="submit"
                        class="btn btn-primary">

                    Update Category

                </button>

            </form>

        </div>

    </div>

</div>

@endsection