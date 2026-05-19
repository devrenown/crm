@extends('layouts.app')
@include('blog::admin.layouts.layout')

@section('page-content')

<div class="content container-fluid">

    <div class="page-header">

        <h3 class="page-title">
            Create Blog
        </h3>

    </div>

    <div class="card">

        <div class="card-body">

            <form action="{{ route('blog.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label"> Title</label>
                    <input type="text" name="title" class="form-control" required>

                </div>

                <div class="mb-3">
                    <label class="form-label">Slug</label>
                    <input type="text" name="slug" class="form-control">

                </div>
                
                <div class="mb-3">
                    <label class="form-label">Blog Category</label>
                    
                    @foreach($categories as $category)
                    <select name="category" class="form-control">
                        <option value="{{ $category }}"> {{ $category->name }} </option>
                    </select>
                    @endforeach
                </div>

                <div class="mb-3">
                    <label class="form-label">Featured Image</label>
                    <input type="file" name="featured_image" class="form-control">

                </div>

                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-control">
                        <option value="draft">Draft</option>
                        <option value="published">Published</option>
                    </select>
                    
                </div>

                <div class="mb-3">
                    <label class="form-label">Content</label>
                    <x-form.ckeditor name="content" id="blog-editor"></x-form.ckeditor>

                </div>
                
                <div class="mb-3 mt-5">
                    <strong>SEO Content</strong>
                </div>
                
                 <!-- Meta Title -->
                <div class="mb-3">

                    <label class="form-label">
                        Meta Title
                    </label>

                    <input type="text"
                           name="meta_title"
                           value="{{ old('meta_title') }}"
                           class="form-control"
                           maxlength="70">

                    <small class="text-muted">
                        Recommended: 50-60 characters
                    </small>

                </div>

                <!-- Meta Description -->
                <div class="mb-3">

                    <label class="form-label">
                        Meta Description
                    </label>

                    <textarea name="meta_description"
                              rows="4"
                              class="form-control"
                              maxlength="170">{{ old('meta_description') }}</textarea>

                    <small class="text-muted">
                        Recommended: 150-160 characters
                    </small>

                </div>

                <!-- Meta Keywords -->
                <div class="mb-3">

                    <label class="form-label">
                        Meta Keywords
                    </label>

                    <input type="text"
                           name="meta_keywords"
                           value="{{ old('meta_keywords') }}"
                           class="form-control">

                    <small class="text-muted">
                        Example: crm software, hrm, employee management
                    </small>

                </div>

                <button type="submit"
                        class="btn btn-primary">

                    Save Blog
                </button>

            </form>

        </div>

    </div>

</div>

@endsection
