@extends('layouts.app')
@include('blog::admin.layouts.layout')

@section('page-content')

<div class="content container-fluid">

    <div class="page-header">

        <h3 class="page-title">
            Edit Blog
        </h3>

    </div>

    <div class="card">

        <div class="card-body">

            <form action="{{ route('blog.update', $blog->id) }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf
                @method('PUT')

                <div class="mb-3">

                    <label class="form-label">
                        Title
                    </label>

                    <input type="text"
                           name="title"
                           value="{{ $blog->title }}"
                           class="form-control">

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Slug
                    </label>

                    <input type="text"
                           name="slug"
                           value="{{ $blog->slug }}"
                           class="form-control">

                </div>
                
                <div class="mb-3">
                    <label class="form-label">Blog Category</label>
                    
                    @foreach($categories as $category)
                    <select name="category" class="form-control">
                        <option value="{{ $category->id }}" {{ $category->name == $blog->category?->name ? 'selected' : '' }}> {{ $category->name }} </option>
                    </select>
                    @endforeach
                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Current Image
                    </label>

                    <br>

                    <img src="{{ asset($blog->featured_image) }}"
                         width="120"
                         class="rounded">

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Change Image
                    </label>

                    <input type="file"
                           name="featured_image"
                           class="form-control">

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Status
                    </label>

                    <select name="status"
                            class="form-control">

                        <option value="draft"
                            {{ $blog->status == 'draft' ? 'selected' : '' }}>

                            Draft

                        </option>

                        <option value="published"
                            {{ $blog->status == 'published' ? 'selected' : '' }}>

                            Published

                        </option>

                    </select>

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Content
                    </label>
                    
                    <x-form.ckeditor name="content" id="blog-editor">{!! $blog->content !!}</x-form.ckeditor>

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
                           value="{{ old('meta_title', $blog->meta_title) }}"
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
                              maxlength="170">{{ old('meta_description', $blog->meta_description) }}</textarea>

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
                           value="{{ old('meta_keywords', $blog->kaywords) }}"
                           class="form-control">

                    <small class="text-muted">
                        Example: crm software, hrm, employee management
                    </small>

                </div>

                <button type="submit"
                        class="btn btn-primary">

                    Update Blog

                </button>

            </form>

        </div>

    </div>

</div>

@endsection