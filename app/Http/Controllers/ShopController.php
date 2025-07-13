<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Contracts\ShopContract;
use App\Repositories\ShopRepository;
use App\Http\Requests\StoreShopRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ShopController extends Controller
{

    public function __construct(protected ShopContract $shopRepository)
    {

    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shops = $this->shopRepository->all();
        return Inertia::render('SalesManagement/Index', [
            'shops' => $shops,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('SalesManagement/CreateShop');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreShopRequest $request)
    {
        if ($this->shopRepository->create($request->validated())) {
            return redirect()->route('shops.index')->with('success', 'Shop created successfully.');
        }
        return redirect()->back()->with('error', 'Failed to create shop.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Shop $shop)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $shop = $this->shopRepository->find($id);
        return Inertia::render('SalesManagement/Edit', [
            'shop' => $shop,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = $request->all();
        if ($this->shopRepository->update($data, $id) instanceof Shop) {
            return redirect()->route('shops.index')->with('success', 'Shop updated successfully.');
        }
        return redirect()->back()->with('error', 'Failed to update shop.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Shop $shop)
    {
        //
    }
}
