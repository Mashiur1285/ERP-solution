<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contracts\CategoryContract;
use App\Http\Requests\StoreCategoryRequest;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function __construct(
        protected CategoryContract $categoryRepository,

    ) {

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Category/Index', [
            'categories' => $this->categoryRepository->all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $data = $request->Validated();
        if ($this->categoryRepository->create($data)) {
            return redirect()->route('categories.index')
                ->with('success', 'Category created successfully.');
        }
        return redirect()->back()
            ->with('error', 'Failed to create category.');
    }

    public function quickStore(StoreCategoryRequest $request)
    {
        $data = $request->validated();
        $category = $this->categoryRepository->create($data);

        if (!$category) {
            return response()->json(['message' => 'Failed to create'], 422);
        }

        return response()->json(['category' => $category], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $category = $this->CategoryRepository->find($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreCategoryRequest $request, string $id)
    {
        $data = $request->validated();
        $this->categoryRepository->update($data, $id);

        return redirect()->route('categories.index')->with('success', 'Category updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
