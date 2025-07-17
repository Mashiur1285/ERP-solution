<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Contracts\BrandContract;
use App\Http\Requests\StoreBrandRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BrandController extends Controller
{
    public function __construct(protected BrandContract $brandRepository)
    {
        // Constructor logic if needed

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Brand/Index', [
            'brands' => $this->brandRepository->all(),
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
    public function store(StoreBrandRequest $request)
    {
        $data = $request->validated();
        $data['brand_name'] = $data['name'];
        unset($data['name']);

        $this->brandRepository->create($data);

        return redirect()->route('brands.index')->with('success', 'Brand created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Brand $brand)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Brand $brand)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreBrandRequest $request, int $id)
    {
        $data = $request->validated();
        $data['brand_name'] = $data['name'];
        unset($data['name']);
        $this->brandRepository->update($data, $id);

        return redirect()->route('brands.index')->with('success', 'Brand updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Brand $brand)
    {
        //
    }
}
