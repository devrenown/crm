@extends('layouts.app')
@include('blog::admin.layouts.layout')

@section('page-content')

<div class="content container-fluid">

    <div class="card">

        <img src="{{ asset($blog->featured_image) }}"
             class="card-img-top">

        <div class="card-body">

            <h2 class="mb-3">
                {{ $blog->title }}
            </h2>

            <div class="mb-3">

                <span class="badge bg-primary">
                    {{ ucfirst($blog->status) }}
                </span>

            </div>

            <div class="mb-4 text-muted">

                Created:
                {{ $blog->created_at->format('d M Y h:i A') }}

            </div>
            
            <div class="mb-4 text-muted">

                Category:
                {{ $blog->category->name }}

            </div>

            <div>

                {!! nl2br($blog->content) !!}

            </div>

        </div>

    </div>

</div>

@endsection