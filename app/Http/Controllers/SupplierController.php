<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Requests\StoreSupplierRequest;
use App\Models\Supplier;
use App\Models\SaleItem;
use App\Contracts\SupplierContract;

use Inertia\Inertia;

class SupplierController extends Controller
{

   public function __construct(protected SupplierContract $supplierRepository)
        {
        }    


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)

    {
       
        $suppliers= $this->supplierRepository->all();
       return Inertia::render('Suppliers/Index', [
           'suppliers' => $suppliers,
       ]);

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

       return Inertia::render('Suppliers/Create');
    
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSupplierRequest $request)
    {
        $data = $request->validated();

        $this->supplierRepository->create($data);

        return to_route('suppliers.index')->with('success', 'Supplier created successfully');
    }

    public function quickStore(StoreSupplierRequest $request)
    {
        $data = $request->validated();
        $supplier = $this->supplierRepository->create($data);

        if (!$supplier) {
            return response()->json(['message' => 'Failed to create'], 422);
        }

        // Ensure remaining_deposit is present for UI rendering
        $supplier->remaining_deposit = $supplier->remaining_deposit ?? 0;

        return response()->json(['supplier' => $supplier], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $supplier= $this->supplierRepository->find($id);
        return Inertia::render('Suppliers/Edit', [
            'supplier' => $supplier,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Validate before saving so a duplicate phone number (or other bad input)
        // returns a friendly validation error instead of a 500 from the DB's
        // unique constraint. The unique rule ignores the supplier being edited.
        $data = $request->validate([
            'company_name' => 'required|string|max:40',
            'branch_name' => 'nullable|string|max:20',
            'phone_number' => ['required', 'string', 'max:20', Rule::unique('suppliers', 'phone_number')->ignore($id)],
            'emergency_phone_number' => 'nullable|string|max:20',
            'address' => 'required|string',
            'email' => 'nullable|email|max:255',
            'country' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:20',
            'website' => 'nullable|url|max:255',
            'notes' => 'nullable|string',
        ]);

        if ($this->supplierRepository->update($data, $id) instanceof Supplier) {
            return back()->with('success', 'Supplier updated successfully');
        }
        return back()->with('error', 'Unable to update');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $hasSales = SaleItem::where('supplier_id', $id)->exists();
        if ($hasSales) {
            $message = 'This supplier cannot be deleted because they have associated sale records.';
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $message], 422);
            }
            return back()->with('error', $message);
        }

        $this->supplierRepository->delete((int) $id);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Supplier deleted.']);
        }
        return back()->with('success', 'Supplier deleted.');
    }
}
