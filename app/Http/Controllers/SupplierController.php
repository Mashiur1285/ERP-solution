<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreSupplierRequest;
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
        $data= $request->validated();
;
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
        dd($request->all());
        $data=$request->validated();
        return $this->depositRepository->update($data, id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
