<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Purchase;
use App\Models\Journal;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::query();

        $start_date = request()->get('start_date') ?? '';
        $end_date = request()->get('end_date') ?? '';
        $ket = request()->get('ket') ?? '';

        if ($start_date && $end_date) {
            $query->whereBetween('tanggal_payment', [$start_date, $end_date]);
        } elseif ($start_date) {
            $query->where('tanggal_payment', '>=', $start_date);
        } elseif ($end_date) {
            $query->where('tanggal_payment', '<=', $end_date);
        }

        if ($ket) {
            $query->where('ket_payment', 'LIKE', '%' . $ket . '%');
        }

        $totalShippingCost = Payment::where('kode_payment', 'LIKE', '5102%')->sum('cost_payment');
        $totalOperationCost = Payment::where(function($query) {
            $query->where('kode_payment', 'LIKE', '6101%')
                  ->orWhere('kode_payment', 'LIKE', '6301%')
                  ->orWhere('kode_payment', 'LIKE', '6601%')
                  ->orWhere('kode_payment', 'LIKE', '6701%')
                  ->orWhere('kode_payment', 'LIKE', '6901%')
                  ->orWhere('kode_payment', 'LIKE', '6201%');
        })->sum('cost_payment');
        $totalTaxNInterestCost = Payment::where(function($query) {
            $query->where('kode_payment', 'LIKE', '6501%')
                  ->orWhere('kode_payment', 'LIKE', '6801%');
        })->sum('cost_payment');
        $totalCost = Payment::sum('cost_payment');

        // Handle reset filter
        if ($request->has('reset_filter')) {
            return redirect()->route('admin.payment.index');
        }

        $payment = $query->orderBy('tanggal_payment', 'desc')->paginate(7);

        // $totalShippingCost = Payment::where('kode_payment', 'LIKE', '1502%')->sum('cost_payment');
        // $totalOperationCost = Payment::whereIn('kode_payment', ['6101%', '6301%', '6601%', '6701%', '6901%', '6201%'])->sum('cost_payment');
        // $totalTaxNInterestCost = Payment::whereIn('kode_payment', ['6501%', '6801%'])->sum('cost_payment');
        // $totalCost = Payment::sum('cost_payment');
        // $totalShippingCost = $query->where('kode_payment', 'LIKE', '1502%')->sum('cost_payment');
        // $totalOperationCost = $query->where('kode_payment', ['6101%', '6301%', '6601%', '6701%', '6901%', '6201%'])->sum('cost_payment');
        // $totalTaxNInterestCost = $query->where('kode_payment', ['6501%', '6801%'])->sum('cost_payment');
        // $totalCost = $query->sum('cost_payment');

        return view('admin.display.payment', [
            'payment' => $payment,
            'totalShippingCost' => $totalShippingCost,
            'totalOperationCost' => $totalOperationCost,
            'totalTaxNInterestCost' => $totalTaxNInterestCost,
            'totalCost' => $totalCost,
        ]);
    }

    public function store(Request $request)
    {
        $message = [
            'required' => ':attribute harus diisi',
            'numeric' => ':attribute harus berupa angka',
            'min' => ':attribute harus lebih dari 0',
        ];

        $validated = $request->validate([
            'tanggal_payment' => 'required',
            'ket_payment' => 'required',
            'keterangan' => 'required',
            'cost_payment' => 'required|numeric|min:1',
        ], $message);

        $payment = Payment::create($validated);

        // Check if the prefix of kode_payment is '5102'
        if (substr($payment->kode_payment, 0, 4) === '5102') {
            return redirect()->route('admin.payment.index')->with('success', 'Payment added successfully without journal entry');
        }

        $debit_acc_id = match ($validated['ket_payment']) {
            'Listrik, Air, dan Telepon' => 16,
            'Sewa' => 18,
            'Pajak' => 19,
            'Asuransi' => 22,
            'Pemasaran' => 20,
            'Bunga' => 21,
            'Gaji Karyawan' => 17,
            default => 23,
        };

        Journal::create([
            'tanggal_jurnal' => $validated['tanggal_payment'],
            'debit_acc_id' => $debit_acc_id,
            'kredit_acc_id' => 3,
            'debit_jurnal' => $validated['cost_payment'],
            'kredit_jurnal' => $validated['cost_payment'],
            'ket_jurnal' => $validated['keterangan'],
        ]);

        return redirect()->route('admin.payment.index')->with('success', 'Payment added successfully');
    }

    public function edit($id)
    {
        $payment = Payment::findOrFail($id);
        return view('admin.edit.editpayment', compact('payment'));
    }

    public function update(Request $request, $id)
    {
        $message = [
            'required' => ':attribute harus diisi',
            'numeric' => ':attribute harus berupa angka',
            'min' => ':attribute harus lebih dari 0',
        ];

        $validated = $request->validate([
            'tanggal_payment' => 'required',
            'ket_payment' => 'required',
            'keterangan' => 'required',
            'cost_payment' => 'required|numeric|min:1',
        ], $message);

        $payment = Payment::findOrFail($id);
        $originalCostPayment = $payment->cost_payment;
        $payment->update($validated);

        if ($payment->ket_payment == 'Pengiriman') {
            $purchase = Purchase::where('biaya_kirim', $originalCostPayment)->first();
            if ($purchase) {
                $purchase->biaya_kirim = $validated['cost_payment'];
                $purchase->save();
            }
        }

        $debit_acc_id = match ($validated['ket_payment']) {
            'Listrik, Air, dan Telepon' => 16,
            'Sewa' => 18,
            'Pajak' => 19,
            'Asuransi' => 22,
            'Pemasaran' => 20,
            'Bunga' => 21,
            'Gaji Karyawan' => 17,
            default => 23,
        };

        $journal = Journal::where('ket_jurnal', $payment->keterangan)->first();
        if ($journal) {
            $journal->update([
                'tanggal_jurnal' => $validated['tanggal_payment'],
                'debit_acc_id' => $debit_acc_id,
                'kredit_acc_id' => 3,
                'debit_jurnal' => $validated['cost_payment'],
                'kredit_jurnal' => $validated['cost_payment'],
                'ket_jurnal' => $validated['keterangan'],
            ]);
        }

        return redirect()->route('admin.payment.index')->with('success', 'Payment updated successfully');
    }

    public function destroy($id)
    {
        $payment = Payment::findOrFail($id);
        if ($payment->ket_payment == 'Pengiriman') {
            $purchase = Purchase::where('biaya_kirim', $payment->cost_payment)->first();
            if ($purchase) {
                $purchase->biaya_kirim = 0;
                $purchase->save();
            }
        }
        $payment->delete();

        return redirect()->route('admin.payment.index')->with('success', 'Payment deleted successfully');
    }
}
