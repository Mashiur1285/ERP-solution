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
     * Quick store deposit via JSON API (used from Lift page).
     */
    public function quickStore(Request $request)
    {
        $data = $request->all();
        $data['balance_remaining'] = $data['balance_deposited'];
        $this->depositRepository->create($data);
        return response()->json(['success' => true]);
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
        $data = $request->validate([
            'balance_deposited' => 'required|numeric|min:0.01',
        ]);

        $deposit = $this->depositRepository->find($id);
        abort_unless($deposit, 404);

        $usedAmount = (float) ($deposit->balance_used ?? 0);
        $newDepositedAmount = round((float) $data['balance_deposited'], 2);

        if ($newDepositedAmount < $usedAmount) {
            return back()->withErrors([
                'balance_deposited' => 'Deposit amount cannot be less than already used amount.',
            ]);
        }

        $this->depositRepository->update([
            'balance_deposited' => $newDepositedAmount,
            'balance_remaining' => round($newDepositedAmount - $usedAmount, 2),
            'is_used' => round($newDepositedAmount - $usedAmount, 2) <= 0,
        ], $id);

        return redirect()->route('deposits.index')->with('success', 'Deposit updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
