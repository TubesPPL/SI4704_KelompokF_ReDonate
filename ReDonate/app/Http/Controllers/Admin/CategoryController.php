<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('items')->latest()->paginate(20);
        return view('admin.categories.index', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100|unique:categories,name',
            'icon'        => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        Category::create($validated);

        return back()->with('success', "Kategori \"{$validated['name']}\" berhasil ditambahkan.");
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100|unique:categories,name,' . $category->id,
            'icon'        => 'nullable|string|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        if ($category->name !== $validated['name']) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $category->update($validated);

        return back()->with('success', "Kategori berhasil diperbarui.");
    }

    public function destroy(Category $category)
    {
        if ($category->items()->count() > 0) {
            return back()->with('error', "Kategori tidak bisa dihapus karena masih ada {$category->items()->count()} barang yang menggunakannya.");
        }

        $name = $category->name;
        $category->delete();

        return back()->with('success', "Kategori \"{$name}\" berhasil dihapus.");
    }
}
