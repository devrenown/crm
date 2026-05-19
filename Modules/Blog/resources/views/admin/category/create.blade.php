@extends('layouts.app')
@include('blog::admin.layouts.layout')

@section('page-content')

<div class="content container-fluid">

    <div class="page-header">

        <h3 class="page-title">
            Create Category
        </h3>

    </div>

    <div class="card">

        <div class="card-body">

            <form action="{{ route('blog.category.store') }}"
                  method="POST">

                @csrf

                <div class="mb-3">

                    <label class="form-label">
                        Name
                    </label>

                    <input type="text"
                           name="name"
                           class="form-control"
                           required>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Slug
                    </label>

                    <input type="text"
                           name="slug"
                           class="form-control">

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Status
                    </label>

                    <select name="status"
                            class="form-control">

                        <option value="1">
                            Active
                        </option>

                        <option value="0">
                            Inactive
                        </option>

                    </select>

                </div>

                <button type="submit"
                        class="btn btn-primary">

                    Save Category

                </button>

            </form>

        </div>

    </div>

</div>

@endsection