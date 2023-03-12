<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        // Get all categories
        $category = Category::all();
        return CategoryResource::collection($category);
    }

    public function store(CategoryRequest $request)
    {
        // Create a new category
        $category = Category::create($request->validated());
        return new CategoryResource($category);
    }

    public function show(Category $category)
    {
        // Get a single category
        return new CategoryResource($category);
    }

    public function update(CategoryRequest $request, Category $category)
    {
        // Update a category
        $category->update($request->validated());
        return new CategoryResource($category);
    }

    public function destroy(Category $category)
    {
        // Delete a category
        $category->delete();
        return response(null, 204);
    }
}
