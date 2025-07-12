<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contracts\DepositContract;
use App\Contracts\SupplierContract;
use Inertia\Inertia;

class DepositController extends Controller
{

    public function __construct(
        protected DepositContract $depositRepository,
        protected SupplierContract $supplierRepository
    ) {
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('DepositManagement/Deposit', [
            'suppliers' => $this->supplierRepository->all(),
            'depositHistory' => $this->depositRepository->depositHistory(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $data['balance_remaining'] = $data['balance_deposited'];
        $this->depositRepository->create($data);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        $data = $request->validated();
        $depositReport = $this->depositRepository->update($data, $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
