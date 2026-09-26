<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminCategoryController extends Controller
{
    public function index()
    {
        return view('admin.categories.index', [
            'categories' => Category::withCount('products')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'icon' => ['nullable', 'string', 'max:10'],
        ]);

        $base = Str::slug($data['name']) ?: 'category';
        $slug = $base;
        $i = 2;
        while (Category::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $i++;
        }

        Category::create($data + ['slug' => $slug]);

        return back()->with('success', 'Category added.');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->exists()) {
            return back()->with('error', 'This category has products, so it cannot be deleted.');
        }

        $category->delete();

        return back()->with('success', 'Category deleted.');
    }
}