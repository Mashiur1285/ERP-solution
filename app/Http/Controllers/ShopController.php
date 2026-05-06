<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use App\Contracts\ShopContract;
use App\Repositories\ShopRepository;
use App\Http\Requests\StoreShopRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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
        $shop = $this->shopRepository->create($request->validated());
        if ($shop) {
            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'shop' => $shop, 'message' => 'Shop created successfully.']);
            }
            return redirect()->route('shops.index')->with('success', 'Shop created successfully.');
        }
        
        if ($request->wantsJson()) {
            return response()->json(['success' => false, 'message' => 'Failed to create shop.'], 400);
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
        $data = $request->validate([
            'shop_name' => 'required|string|max:255',
            'road' => 'nullable|string|max:100',
            'owner_name' => 'nullable|string|max:255',
            'shop_address' => 'nullable|string|max:255',
            'phone_number' => [
                'required',
                'string',
                'max:20',
                Rule::unique('shops', 'phone_number')->ignore($id),
            ],
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('shops', 'email')->ignore($id),
            ],
            'website' => 'nullable|url|max:255',
            'national_id' => 'nullable|string|max:50',
            'trade_license' => 'nullable|string|max:50',
            'tax_id' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
        ]);

        if ($this->shopRepository->update($data, $id) instanceof Shop) {
            return redirect()->route('shops.index')->with('success', 'Shop updated successfully.');
        }
        return redirect()->back()->with('error', 'Failed to update shop.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        if ($this->shopRepository->delete((int) $id)) {
            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Shop deleted successfully.']);
            }
            return redirect()->route('shops.index')->with('success', 'Shop deleted successfully.');
        }

        if ($request->wantsJson()) {
            return response()->json(['success' => false, 'message' => 'Failed to delete shop.'], 400);
        }
        return redirect()->back()->with('error', 'Failed to delete shop.');
    }
}
