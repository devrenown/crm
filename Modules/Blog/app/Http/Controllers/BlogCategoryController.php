<?php

namespace Modules\Blog\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\BlogCategory;

class BlogCategoryController extends Controller
{
    /**
     * Category List
     */
    public function index()
    {
        $categories = BlogCategory::latest()->paginate(10);

        return view('blog::admin.category.index', compact('categories'));
    }

    /**
     * Create Page
     */
    public function create()
    {
        return view('blog::admin.category.create');
    }

    /**
     * Store Category
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:blog_categories,name',
            'status' => 'required',
        ]);

        BlogCategory::create([

            'name' => $request->name,

            'slug' => $request->filled('slug')
                        ? Str::slug($request->slug)
                        : Str::slug($request->name),
                        
            'category_id' => $request->category,

            'status' => $request->status,

        ]);

        return redirect()
                ->route('blog.categories')
                ->with('success', 'Category created successfully.');
    }

    /**
     * Show Category
     */
    public function show($id)
    {
        $category = BlogCategory::findOrFail($id);

        return view('blog::admin.category.show', compact('category'));
    }

    /**
     * Edit Page
     */
    public function edit($id)
    {
        $category = BlogCategory::findOrFail($id);

        return view('blog::admin.category.edit', compact('category'));
    }

    /**
     * Update Category
     */
    public function update(Request $request, $id)
    {
        $category = BlogCategory::findOrFail($id);

        $request->validate([
            'name' => 'required|unique:blog_categories,name,' . $id,
            'status' => 'required',
        ]);

        $category->update([

            'name' => $request->name,

            'slug' => $request->filled('slug')
                        ? Str::slug($request->slug)
                        : Str::slug($request->name),

            'status' => $request->status,

        ]);

        return redirect()
                ->route('blog.categories')
                ->with('success', 'Category updated successfully.');
    }

    /**
     * Delete Category
     */
    public function destroy($id)
    {
        $category = BlogCategory::findOrFail($id);

        $category->delete();

        return redirect()
                ->back()
                ->with('success', 'Category deleted successfully.');
    }
}