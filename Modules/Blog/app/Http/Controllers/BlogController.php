<?php

namespace Modules\Blog\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Models\Blog;
use App\Models\BlogCategory;

class BlogController extends Controller
{
    /**
     * Dashboard
     */
    public function index()
    {
        $totalBlogs = Blog::count();

        $publishedBlogs = Blog::where('status', 'published')->count();

        $draftBlogs = Blog::where('status', 'draft')->count();

        $recentBlogs = Blog::latest()->take(10)->get();

        return view('blog::admin.dashboard', compact(
            'totalBlogs',
            'publishedBlogs',
            'draftBlogs',
            'recentBlogs'
        ));
    }

    /**
     * Blog List
     */
    public function list()
    {
        $blogs = Blog::latest()->paginate(10);

        return view('blog::admin.blog.list', compact('blogs'));
    }

    /**
     * Create Blog Page
     */
    public function create()
    {
        $categories = BlogCategory::all();
        return view('blog::admin.blog.create', ['categories' => $categories]);
    }

    /**
     * Store Blog
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title'           => 'required|max:255',
            'content'         => 'required',
            'status'          => 'required',
            'featured_image'  => 'nullable|image|mimes:jpg,jpeg,png,webp',
        ]);

        $imagePath = null;

        if ($request->hasFile('featured_image')) {

            $image = $request->file('featured_image');

            $imageName = time() . '.' . $image->getClientOriginalExtension();

            $destinationPath = public_path('uploads/blogs');

            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }

            $image->move($destinationPath, $imageName);

            $imagePath = 'uploads/blogs/' . $imageName;
        }

        Blog::create([
            'title'            => $request->title,
            'slug'             => $request->filled('slug') ? Str::slug($request->slug) : Str::slug($request->title),
            'content'          => $request->content,
            'status'           => $request->status,
            'featured_image'   => $imagePath,
            'meta_title'       => $request->meta_title ?? null,
            'meta_description' => $request->meta_description ?? null,
            'kaywords'         => $request->meta_keywords ?? null,
            'created_by'       => Auth::id(),
        ]);

        return redirect()
            ->route('blog.list')
            ->with('success', 'Blog created successfully.');
    }

    /**
     * Show Blog
     */
    public function show($id)
    {
        $blog = Blog::findOrFail($id);

        return view('blog::admin.blog.show', compact('blog'));
    }

    /**
     * Edit Blog
     */
    public function edit($id)
    {
        $blog = Blog::findOrFail($id);
        $categories = BlogCategory::all();

        return view('blog::admin.blog.edit', compact('blog', 'categories'));
    }

    /**
     * Update Blog
     */
    public function update(Request $request, $id): RedirectResponse
    {
        $blog = Blog::findOrFail($id);

        $request->validate([
            'title'           => 'required|max:255',
            'content'         => 'required',
            'status'          => 'required',
            'featured_image'  => 'nullable|image|mimes:jpg,jpeg,png,webp',
        ]);

        $imagePath = $blog->featured_image;

        if ($request->hasFile('featured_image')) {

            if ($blog->featured_image && File::exists(public_path($blog->featured_image))) {

                File::delete(public_path($blog->featured_image));
            }

            $image = $request->file('featured_image');

            $imageName = time() . '.' . $image->getClientOriginalExtension();

            $destinationPath = public_path('uploads/blogs');

            if (!File::exists($destinationPath)) {

                File::makeDirectory($destinationPath, 0755, true);
            }

            $image->move($destinationPath, $imageName);

            $imagePath = 'uploads/blogs/' . $imageName;
        }

        $blog->update([
            'category_id'     => $request->category ?? null,
            'title'           => $request->title,
            'slug'            => $request->slug ?? Str::slug($request->title),
            'content'         => $request->content,
            'status'          => $request->status,
            'featured_image'  => $imagePath,
            
            'meta_title'       => $request->meta_title ?? null,
            'meta_description' => $request->meta_description ?? null,
            'kaywords'         => $request->meta_keywords ?? null,
        ]);

        return redirect()
            ->route('blog.list')
            ->with('success', 'Blog updated successfully.');
    }

    /**
     * Delete Blog
     */
    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);

        if ($blog->featured_image && File::exists(public_path($blog->featured_image))) {

            File::delete(public_path($blog->featured_image));
        }

        $blog->delete();

        return redirect()
            ->route('blog.list')
            ->with('success', 'Blog deleted successfully.');
    }
}