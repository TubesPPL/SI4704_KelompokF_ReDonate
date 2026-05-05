<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;

class ItemController extends Controller
{
    //  GET ALL ITEMS 
    public function index()
    {
        $items = Item::with(['category', 'user'])->latest()->get();

        return response()->json([
            'message' => 'List of items',
            'data' => $items
        ]);
    }

    //  CREATE
    public function store(Request $request)
    {
        $request->validate([
            'item_name' => 'required',
            'category_id' => 'required',
            'condition' => 'required',
            'description' => 'required',
            'location' => 'required',
            'image' => 'image|mimes:jpg,png,jpeg|max:2048'
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('items', 'public');
        }

        $item = Item::create([
            'user_id' => 1, // sementara
            'category_id' => $request->category_id,
            'item_name' => $request->item_name,
            'description' => $request->description,
            'condition' => $request->condition,
            'location' => $request->location,
            'image_url' => $imagePath,
            'status' => 'available'
        ]);

        return response()->json([
            'message' => 'Item created',
            'data' => $item
        ], 201);
    }

    //  READ DETAIL
    public function show($id)
    {
        $item = Item::with(['category', 'user'])->findOrFail($id);

        return response()->json([
            'message' => 'Item detail',
            'data' => $item
        ]);
    }

    //  UPDATE
    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);

        $request->validate([
            'item_name' => 'required',
            'category_id' => 'required',
            'location' => 'required',
            'condition' => 'required',
            'description' => 'required'
        ]);

        // update image kalau ada
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('items', 'public');
            $item->image_url = $imagePath;
        }

        $item->update([
            'item_name' => $request->item_name,
            'category_id' => $request->category_id,
            'location' => $request->location,
            'description' => $request->description,
            'condition' => $request->condition
        ]);

        return response()->json([
            'message' => 'Item updated',
            'data' => $item
        ]);
    }

    //  DELETE (Soft Delete)
    public function destroy($id)
    {
        $item = Item::findOrFail($id);
        $item->delete();

        return response()->json([
            'message' => 'Item deleted'
        ]);
    }

    //  GET CATEGORIES (untuk dropdown frontend)
    public function categories()
    {
        $categories = Category::all();

        return response()->json([
            'message' => 'List of categories',
            'data' => $categories
        ]);
    }
}