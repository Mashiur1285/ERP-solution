<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
       
       $suppliers = $this->supplierRepository->all();
       dd($suppliers);
       return Inertia::render('Suppliers/Index', [
           'suppliers' => $suppliers,
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
    public function store(Request $request)
    {
        $data = [
            'company_name' => 'Acme Corporation',
            'branch_name' => 'Dhaka Branch',
            'phone_number' => '+8801712345678',
            'emergency_phone_number' => '+8801812345678',
            'address' => '123 Business Road, Commercial Area, Dhaka',
            'email' => 'dhaka@acme-corp.com',
            'country' => 'Bangladesh',
            'city' => 'Dhaka',
            'website' => 'https://acme-corp.com',
            'notes' => 'Primary supplier for office supplies'
];
        $this->supplierRepository->create($data);
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
