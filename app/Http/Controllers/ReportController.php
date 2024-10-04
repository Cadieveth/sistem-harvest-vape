<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Purchase;
use App\Models\Sales;
use App\Models\BalanceSheet;
use App\Models\Journal;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function kas(Request $request)
    {
        $sales = Sales::query();
        $payment = Payment::query();
        $payment2 = Payment::query();
        $purchase = Purchase::query();
        $purchase2 = Purchase::query();

        $sampai = request()->get('sampai') ?? '';

        if ($sampai) {
            $sales->where('date_sales', '<=', $sampai);
            $payment->where('tanggal_payment', '<=', $sampai);
            $payment2->where('tanggal_payment', '<=', $sampai);
            $purchase->where('tanggal_pembelian', '<=', $sampai);
            $purchase2->where('tanggal_pembelian', '<=', $sampai);
        }

        $sales = $sales->get();
        $totalSales = $sales->sum('total_sales');

        $saldoAwalKas = BalanceSheet::where('id_akun', 3)->value('nominal') ?? 0;

        // Pembelian persediaan barang
        $kode = '1624';
        $purchase2 = $purchase2->select('ket_purchase', DB::raw('SUM(total_purchase) as total_purchase_sum'))
            ->where('kode_purchase', 'like', $kode . '%')
            ->groupBy('ket_purchase')->get();
        $totalPurchase2 = $purchase2->sum('total_purchase_sum');

        // filter payment yang bukan pajak dan bunga
        $pembayaran = ['6501', '6801', '5102'];
        $payment = $payment->where(function ($query) use ($pembayaran) {
            foreach ($pembayaran as $kode) {
                $query->where('kode_payment', 'not like', $kode . '%');
            }
        })->get();
        $groupedPayments = $payment->groupBy('ket_payment')->map(function ($group) {
            return [
                'ket_payment' => $group->first()->ket_payment,
                'cost_payment' => $group->sum('cost_payment')
            ];
        });

        $totalPayment = $groupedPayments->sum('cost_payment');

        // filter payment pajak dan bunga
        $pembayaran2 = ['6501', '6801'];
        $payment2 = $payment2->where(function ($query2) use ($pembayaran2) {
            foreach ($pembayaran2 as $kode) {
                $query2->orWhere('kode_payment', 'like', $kode . '%');
            }
        })->get();
        $groupedPayments2 = $payment2->groupBy('ket_payment')->map(function ($group) {
            return [
                'ket_payment' => $group->first()->ket_payment,
                'cost_payment' => $group->sum('cost_payment')
            ];
        });

        $totalPayment2 = $groupedPayments2->sum('cost_payment');

        // filter purchase yang termasuk perlengkapan dan peralatan
        $list = ['1625', '1626'];
        $purchase = $purchase->select('ket_purchase', DB::raw('SUM(total_purchase) as total_purchase_sum'))
            ->where(function ($query) use ($list) {
                foreach ($list as $kode) {
                    $query->orWhere('kode_purchase', 'like', $kode . '%');
                }
            })->groupBy('ket_purchase')->get();
        $totalPurchase = $purchase->sum('total_purchase_sum');

        // New Update
        $discount = $sales->sum('harga_potongan');
        $netSales = $saldoAwalKas + ($totalSales - $discount);
        $sales = $totalSales - $discount;

        // $totalOperasional = $netSales - ($totalPayment + $totalPurchase2);
        $totalOperasional = $totalPayment + $totalPurchase2;
        $totalOperasional2 = $totalOperasional + $totalPayment2;
        $kasOperasional = $netSales - $totalOperasional2;
        $totalInvestasi = $kasOperasional - $totalPurchase;

        return view('admin.report.aruskas', compact(
            'sampai',
            'totalSales',
            'groupedPayments',
            'totalOperasional',
            'groupedPayments2',
            'totalOperasional2',
            'purchase',
            'totalPurchase',
            'totalInvestasi',
            'purchase2',
            'netSales',
            'saldoAwalKas',
            'totalPayment',
            'totalPayment2',
            'kasOperasional',
            'sales',
        ));
    }

    public function cetak_aruskas($sampai)
    {
        $sales = Sales::query();
        $payment = Payment::query();
        $payment2 = Payment::query();
        $purchase = Purchase::query();
        $purchase2 = Purchase::query();

        if ($sampai) {
            $sales->where('date_sales', '<=', $sampai);
            $payment->where('tanggal_payment', '<=', $sampai);
            $payment2->where('tanggal_payment', '<=', $sampai);
            $purchase->where('tanggal_pembelian', '<=', $sampai);
            $purchase2->where('tanggal_pembelian', '<=', $sampai);
        }

        $sales = $sales->get();
        $totalSales = $sales->sum('total_sales');

        $saldoAwalKas = BalanceSheet::where('id_akun', 3)->value('nominal') ?? 0;

        // Pembelian persediaan barang
        $kode = '1624';
        $purchase2 = $purchase2->select('ket_purchase', DB::raw('SUM(total_purchase) as total_purchase_sum'))
            ->where('kode_purchase', 'like', $kode . '%')
            ->groupBy('ket_purchase')->get();
        $totalPurchase2 = $purchase2->sum('total_purchase_sum');

        // filter payment yang bukan pajak dan bunga
        $pembayaran = ['6501', '6801', '5102'];
        $payment = $payment->where(function ($query) use ($pembayaran) {
            foreach ($pembayaran as $kode) {
                $query->where('kode_payment', 'not like', $kode . '%');
            }
        })->get();
        $groupedPayments = $payment->groupBy('ket_payment')->map(function ($group) {
            return [
                'ket_payment' => $group->first()->ket_payment,
                'cost_payment' => $group->sum('cost_payment')
            ];
        });

        $totalPayment = $groupedPayments->sum('cost_payment');

        // filter payment pajak dan bunga
        $pembayaran2 = ['6501', '6801'];
        $payment2 = $payment2->where(function ($query2) use ($pembayaran2) {
            foreach ($pembayaran2 as $kode) {
                $query2->orWhere('kode_payment', 'like', $kode . '%');
            }
        })->get();
        $groupedPayments2 = $payment2->groupBy('ket_payment')->map(function ($group) {
            return [
                'ket_payment' => $group->first()->ket_payment,
                'cost_payment' => $group->sum('cost_payment')
            ];
        });

        $totalPayment2 = $groupedPayments2->sum('cost_payment');

        // filter purchase yang termasuk perlengkapan dan peralatan
        $list = ['1625', '1626'];
        $purchase = $purchase->select('ket_purchase', DB::raw('SUM(total_purchase) as total_purchase_sum'))
            ->where(function ($query) use ($list) {
                foreach ($list as $kode) {
                    $query->orWhere('kode_purchase', 'like', $kode . '%');
                }
            })->groupBy('ket_purchase')->get();
        $totalPurchase = $purchase->sum('total_purchase_sum');

        // New Update
        $discount = $sales->sum('harga_potongan');
        $netSales = $saldoAwalKas + ($totalSales - $discount);
        $sales = $totalSales - $discount;

        // $totalOperasional = $netSales - ($totalPayment + $totalPurchase2);
        $totalOperasional = $totalPayment + $totalPurchase2;
        $totalOperasional2 = $totalOperasional + $totalPayment2;
        $kasOperasional = $netSales - $totalOperasional2;
        $totalInvestasi = $kasOperasional - $totalPurchase;

        // logo
        $imagePath = public_path('backend/asset/img/logos/Logo HV.png');
        if (!File::exists($imagePath)) {
            return "Logo tidak ditemukan";
        }
        $imageData = File::get($imagePath);
        if ($imageData === false) {
            return "Gagal membaca file image";
        }
        $base64String = base64_encode($imageData);
        $base64Image = 'data:image/png;base64,' . $base64String;

        $pdf = Pdf::loadview('admin.report.cetak_aruskas', compact(
            'sampai',
            'totalSales',
            'groupedPayments',
            'totalOperasional',
            'groupedPayments2',
            'totalOperasional2',
            'purchase',
            'totalPurchase',
            'totalInvestasi',
            'purchase2',
            'netSales',
            'saldoAwalKas',
            'totalPayment',
            'totalPayment2',
            'kasOperasional',
            'sales',
            'base64Image',
        ));
        $pdf->setPaper('a4', 'potrait');
        return $pdf->stream('laporan-aruskas-' . $sampai . '.pdf');
    }

    public function laba_rugi(Request $request)
    {
        $dari = $request->input('dari');
        $sampai = $request->input('sampai');

        $sales = Sales::query();
        $payment = Payment::query();

        if ($dari && $sampai) {
            $sales->whereBetween('date_sales', [$dari, $sampai]);
            $payment->whereBetween('tanggal_payment', [$dari, $sampai]);
        }

        $sales = $sales->get();
        $totalSales = $sales->sum('total_sales');

        // hitung pembelian bersih
        $totalPurchase = 0;
        foreach ($sales as $sale) {
            $hargaPokok = $sale->harga_barang / (1.4);
            $hpp = $hargaPokok * $sale->jumlah_sales;
            $totalPurchase += $hpp;
        }

        // filter payment yang bukan pajak dan bunga
        $pembayaran = ['6501', '6801', '5102'];
        $filteredPaymentQuery = clone $payment; // Clone the original query
        $filteredPayment = $filteredPaymentQuery->where(function ($query) use ($pembayaran) {
            foreach ($pembayaran as $kode) {
                $query->where('kode_payment', 'not like', $kode . '%');
            }
        })->get();

        // Menggabungkan payment dengan ket_payment yang sama
        $groupedPayments = $filteredPayment->groupBy('ket_payment')->map(function ($group) {
            return [
                'ket_payment' => $group->first()->ket_payment,
                'cost_payment' => $group->sum('cost_payment')
            ];
        });

        $totalPayment = $groupedPayments->sum('cost_payment');

        // New Update
        $totalDiscount = $sales->sum('harga_potongan');
        $netSales = $totalSales - $totalDiscount;

        $totalHPP = $totalPurchase;

        // filter payment untuk pajak dan bunga
        $pembayaranTax = ['6101', '6301', '6601', '6701', '6901', '5102', '6201'];
        $filteredTaxPaymentQuery = clone $payment; // Clone the original query
        $filteredTaxPayment = $filteredTaxPaymentQuery->where(function ($query) use ($pembayaranTax) {
            foreach ($pembayaranTax as $kodeTax) {
                $query->where('kode_payment', 'not like', $kodeTax . '%');
            }
        })->get();

        // Menggabungkan payment pajak dengan ket_payment yang sama
        $groupedTaxPayments = $filteredTaxPayment->groupBy('ket_payment')->map(function ($group) {
            return [
                'ket_payment' => $group->first()->ket_payment,
                'cost_payment' => $group->sum('cost_payment')
            ];
        });

        $totalTaxPayment = $groupedTaxPayments->sum('cost_payment');

        $totalBeban = $totalPayment + $totalTaxPayment;

        $laba = ($netSales - $totalHPP) - $totalBeban;

        return view('admin.report.labarugi', compact(
            'dari',
            'sampai',
            'totalSales',
            'groupedPayments',
            'groupedTaxPayments',
            'totalPurchase',
            'totalBeban',
            'laba',
            'totalDiscount',
            'netSales',
            'totalHPP',
            'totalPayment',
            'filteredTaxPayment',
            'totalTaxPayment',
        ));
    }

    public function cetak_labarugi($dari, $sampai)
    {
        $sales = Sales::query();
        $payment = Payment::query();

        if ($dari && $sampai) {
            $sales->whereBetween('date_sales', [$dari, $sampai]);
            $payment->whereBetween('tanggal_payment', [$dari, $sampai]);
        }

        $sales = $sales->get();
        $totalSales = $sales->sum('total_sales');

        $totalPurchase = 0;
        foreach ($sales as $sale) {
            $hargaPokok = $sale->harga_barang / (1.4);
            $hpp = $hargaPokok * $sale->jumlah_sales;
            $totalPurchase += $hpp;
        }

        $pembayaran = ['6501', '6801', '5102'];
        $filteredPaymentQuery = clone $payment;
        $filteredPayment = $filteredPaymentQuery->where(function ($query) use ($pembayaran) {
            foreach ($pembayaran as $kode) {
                $query->where('kode_payment', 'not like', $kode . '%');
            }
        })->get();

        $groupedPayments = $filteredPayment->groupBy('ket_payment')->map(function ($group) {
            return [
                'ket_payment' => $group->first()->ket_payment,
                'cost_payment' => $group->sum('cost_payment')
            ];
        });

        $totalPayment = $groupedPayments->sum('cost_payment');
        $totalDiscount = $sales->sum('harga_potongan');
        $netSales = $totalSales - $totalDiscount;

        $totalHPP = $totalPurchase;

        $pembayaranTax = ['6101', '6301', '6601', '6701', '6901', '5102', '6201'];
        $filteredTaxPaymentQuery = clone $payment;
        $filteredTaxPayment = $filteredTaxPaymentQuery->where(function ($query) use ($pembayaranTax) {
            foreach ($pembayaranTax as $kodeTax) {
                $query->where('kode_payment', 'not like', $kodeTax . '%');
            }
        })->get();

        $groupedTaxPayments = $filteredTaxPayment->groupBy('ket_payment')->map(function ($group) {
            return [
                'ket_payment' => $group->first()->ket_payment,
                'cost_payment' => $group->sum('cost_payment')
            ];
        });

        $totalTaxPayment = $groupedTaxPayments->sum('cost_payment');

        $totalBeban = $totalPayment + $totalTaxPayment;

        $laba = ($netSales - $totalHPP) - $totalBeban;

        // Handle logo
        $imagePath = public_path('backend/asset/img/logos/Logo HV.png');
        if (!File::exists($imagePath)) {
            return "Logo tidak ditemukan";
        }
        $imageData = File::get($imagePath);
        if ($imageData === false) {
            return "Gagal membaca file image";
        }
        $base64String = base64_encode($imageData);
        $base64Image = 'data:image/png;base64,' . $base64String;

        $pdf = Pdf::loadview('admin.report.cetak_labarugi', compact(
            'dari',
            'sampai',
            'totalSales',
            'filteredPayment',
            'totalPurchase',
            'totalBeban',
            'laba',
            'totalDiscount',
            'netSales',
            'totalHPP',
            'totalPayment',
            'filteredTaxPayment',
            'totalTaxPayment',
            'groupedPayments',
            'groupedTaxPayments',
            'base64Image',
        ));
        $pdf->setPaper('a4', 'potrait');
        return $pdf->stream('laporan-laba-rugi-' . $dari . '-sampai-' . $sampai . '.pdf');
    }

    public function ekuitas(Request $request)
    {
        $sampai = $request->input('sampai');

        $sales = Sales::query();
        $payment = Payment::query();
        $purchase = Purchase::query();

        if ($sampai) {
            $sales->where('date_sales', '<=', $sampai);
            $payment->where('tanggal_payment', '<=', $sampai);
            $purchase->where('tanggal_pembelian', '<=', $sampai);
        }

        $sales = $sales->get();
        $totalSales = $sales->sum('total_sales');

        // hitung pembelian bersih
        $totalPurchase = 0;
        foreach ($sales as $sale) {
            $hargaPokok = $sale->harga_barang / (1.4);
            $hpp = $hargaPokok * $sale->jumlah_sales;
            $totalPurchase += $hpp;
        }

        // filter payment yang bukan pajak dan bunga
        $pembayaran = ['6501', '6801', '5102'];
        $filteredPaymentQuery = clone $payment; // Clone the original query
        $filteredPayment = $filteredPaymentQuery->where(function ($query) use ($pembayaran) {
            foreach ($pembayaran as $kode) {
                $query->where('kode_payment', 'not like', $kode . '%');
            }
        })->get();

        // Menggabungkan payment dengan ket_payment yang sama
        $groupedPayments = $filteredPayment->groupBy('ket_payment')->map(function ($group) {
            return [
                'ket_payment' => $group->first()->ket_payment,
                'cost_payment' => $group->sum('cost_payment')
            ];
        });

        $totalPayment = $groupedPayments->sum('cost_payment');

        // New Update
        $totalDiscount = $sales->sum('harga_potongan');
        $netSales = $totalSales - $totalDiscount;

        $totalHPP = $totalPurchase;

        // filter payment untuk pajak dan bunga
        $pembayaranTax = ['6101', '6301', '6601', '6701', '6901', '5102', '6201'];
        $filteredTaxPaymentQuery = clone $payment; // Clone the original query
        $filteredTaxPayment = $filteredTaxPaymentQuery->where(function ($query) use ($pembayaranTax) {
            foreach ($pembayaranTax as $kodeTax) {
                $query->where('kode_payment', 'not like', $kodeTax . '%');
            }
        })->get();

        // Menggabungkan payment pajak dengan ket_payment yang sama
        $groupedTaxPayments = $filteredTaxPayment->groupBy('ket_payment')->map(function ($group) {
            return [
                'ket_payment' => $group->first()->ket_payment,
                'cost_payment' => $group->sum('cost_payment')
            ];
        });

        $totalTaxPayment = $groupedTaxPayments->sum('cost_payment');

        $totalBeban = $totalPayment + $totalTaxPayment;

        $laba = ($netSales - $totalHPP) - $totalBeban;

        // Logic untuk menghitung totalModal
        $balance = BalanceSheet::query();

        $balance = $balance->get();

        $aktiva = $balance->filter(function ($item) {
            return preg_match('/^1\d{2}$|^5\d{2}$/', $item->account->kode_akun);
        });

        $liabilitasEkuitas = $balance->filter(function ($item) {
            return preg_match('/^2\d{2}$|^3\d{2}$|^4\d{2}$/', $item->account->kode_akun);
        });

        $modal = $liabilitasEkuitas->sum('nominal');
        $totalNominal = $balance->sum('nominal');
        $totalModalAwal = $totalNominal - $modal;

        $total = $totalModalAwal + $laba;

        return view('admin.report.ekuitas', compact(
            'sampai',
            'totalModalAwal',
            'laba',
            'total',
        ));
    }

    public function cetak_ekuitas($sampai)
    {
        $sales = Sales::query();
        $payment = Payment::query();
        $purchase = Purchase::query();

        if ($sampai) {
            $sales->where('date_sales', '<=', $sampai);
            $payment->where('tanggal_payment', '<=', $sampai);
            $purchase->where('tanggal_pembelian', '<=', $sampai);
        }

        $sales = $sales->get();
        $totalSales = $sales->sum('total_sales');

        // hitung pembelian bersih
        $totalPurchase = 0;
        foreach ($sales as $sale) {
            $hargaPokok = $sale->harga_barang / (1.4);
            $hpp = $hargaPokok * $sale->jumlah_sales;
            $totalPurchase += $hpp;
        }

        // filter payment yang bukan pajak dan bunga
        $pembayaran = ['6501', '6801', '5102'];
        $filteredPaymentQuery = clone $payment; // Clone the original query
        $filteredPayment = $filteredPaymentQuery->where(function ($query) use ($pembayaran) {
            foreach ($pembayaran as $kode) {
                $query->where('kode_payment', 'not like', $kode . '%');
            }
        })->get();

        // Menggabungkan payment dengan ket_payment yang sama
        $groupedPayments = $filteredPayment->groupBy('ket_payment')->map(function ($group) {
            return [
                'ket_payment' => $group->first()->ket_payment,
                'cost_payment' => $group->sum('cost_payment')
            ];
        });

        $totalPayment = $groupedPayments->sum('cost_payment');

        // New Update
        $totalDiscount = $sales->sum('harga_potongan');
        $netSales = $totalSales - $totalDiscount;

        $totalHPP = $totalPurchase;

        // filter payment untuk pajak dan bunga
        $pembayaranTax = ['6101', '6301', '6601', '6701', '6901', '5102', '6201'];
        $filteredTaxPaymentQuery = clone $payment; // Clone the original query
        $filteredTaxPayment = $filteredTaxPaymentQuery->where(function ($query) use ($pembayaranTax) {
            foreach ($pembayaranTax as $kodeTax) {
                $query->where('kode_payment', 'not like', $kodeTax . '%');
            }
        })->get();

        // Menggabungkan payment pajak dengan ket_payment yang sama
        $groupedTaxPayments = $filteredTaxPayment->groupBy('ket_payment')->map(function ($group) {
            return [
                'ket_payment' => $group->first()->ket_payment,
                'cost_payment' => $group->sum('cost_payment')
            ];
        });

        $totalTaxPayment = $groupedTaxPayments->sum('cost_payment');

        $totalBeban = $totalPayment + $totalTaxPayment;

        $laba = ($netSales - $totalHPP) - $totalBeban;

        // Logic untuk menghitung totalModal
        $balance = BalanceSheet::query();

        $balance = $balance->get();

        $aktiva = $balance->filter(function ($item) {
            return preg_match('/^1\d{2}$|^5\d{2}$/', $item->account->kode_akun);
        });

        $liabilitasEkuitas = $balance->filter(function ($item) {
            return preg_match('/^2\d{2}$|^3\d{2}$|^4\d{2}$/', $item->account->kode_akun);
        });

        $modal = $liabilitasEkuitas->sum('nominal');
        $totalNominal = $balance->sum('nominal');
        $totalModalAwal = $totalNominal - $modal;

        $total = $totalModalAwal + $laba;

        // Handle logo
        $imagePath = public_path('backend/asset/img/logos/Logo HV.png');
        if (!File::exists($imagePath)) {
            return "Logo tidak ditemukan";
        }
        $imageData = File::get($imagePath);
        if ($imageData === false) {
            return "Gagal membaca file image";
        }
        $base64String = base64_encode($imageData);
        $base64Image = 'data:image/png;base64,' . $base64String;

        $pdf = Pdf::loadview('admin.report.cetak_ekuitas', compact(
            'sampai',
            'totalModalAwal',
            'laba',
            'total',
            'base64Image',
        ));
        $pdf->setPaper('a4', 'potrait');
        return $pdf->stream('laporan-ekuitas-' . $sampai . '.pdf');
    }

    public function neraca(Request $request)
    {
        $sampai = $request->input('sampai');

        $totalModal = $this->calculateModal();
        $laba = $this->calculateLaba($sampai);
        $accountBalances = $this->calculateAccountBalances($sampai);

        return view('admin.report.neraca', compact(
            'sampai',
            'totalModal',
            'laba',
            'accountBalances',
        ));
    }

    private function calculateAccountBalances($sampai)
    {
        // Validasi dan parsing tanggal menggunakan Carbon
        try {
            $sampai = \Carbon\Carbon::parse($sampai)->format('Y-m-d');
        } catch (\Exception $e) {
            // Jika parsing tanggal gagal, kembalikan array kosong atau lakukan penanganan kesalahan yang sesuai
            return [];
        }

        $accounts = Account::where(function ($query) {
            $query->where('kode_akun', 'like', '1%')
                ->orWhere('kode_akun', 'like', '2%')
                ->orWhere('kode_akun', 'like', '3%');
        })->get();

        $accountBalances = [];

        foreach ($accounts as $account) {
            $journals = Journal::where(function ($query) use ($account) {
                    $query->where('debit_acc_id', $account->id)
                        ->orWhere('kredit_acc_id', $account->id);
                })
                ->whereDate('tanggal_jurnal', '<=', $sampai)
                ->orderBy('tanggal_jurnal', 'asc')
                ->get();

            $totalDebit = $journals->where('debit_acc_id', $account->id)->sum('debit_jurnal');
            $totalKredit = $journals->where('kredit_acc_id', $account->id)->sum('kredit_jurnal');

            $saldoAwal = BalanceSheet::where('id_akun', $account->id)->first();
            $saldoAkhir = ($saldoAwal ? $saldoAwal->nominal : 0) + ($totalDebit - $totalKredit);

            // if ($account->id == 7 || $account->kode_akun == 102) {
            //     $barangKeluar = Sales::sum('total_sales') / 1.4;
            //     $saldoAkhir -= $barangKeluar;
            // }

            if ($saldoAkhir != 0) {
                $accountBalances[] = [
                    'account' => $account,
                    'saldoAkhir' => $saldoAkhir
                ];
            }
        }

        usort($accountBalances, function ($a, $b) {
            return $a['account']->kode_akun <=> $b['account']->kode_akun;
        });

        return $accountBalances;
    }

    private function calculateLaba($sampai)
    {
        $sales = Sales::query();
        $payment = Payment::query();

        if ($sampai) {
            $sales->where('date_sales', '<=', $sampai);
            $payment->where('tanggal_payment', '<=', $sampai);
        }

        $sales = $sales->get();
        $totalSales = $sales->sum('total_sales');

        // hitung pembelian bersih
        $totalPurchase = 0;
        foreach ($sales as $sale) {
            $hargaPokok = $sale->harga_barang / (1.4);
            $hpp = $hargaPokok * $sale->jumlah_sales;
            $totalPurchase += $hpp;
        }

        // filter payment yang bukan pajak dan bunga
        $pembayaran = ['6501', '6801', '5102'];
        $filteredPaymentQuery = clone $payment; // Clone the original query
        $filteredPayment = $filteredPaymentQuery->where(function ($query) use ($pembayaran) {
            foreach ($pembayaran as $kode) {
                $query->where('kode_payment', 'not like', $kode . '%');
            }
        })->get();

        // Menggabungkan payment dengan ket_payment yang sama
        $groupedPayments = $filteredPayment->groupBy('ket_payment')->map(function ($group) {
            return [
                'ket_payment' => $group->first()->ket_payment,
                'cost_payment' => $group->sum('cost_payment')
            ];
        });

        $totalPayment = $groupedPayments->sum('cost_payment');

        // New Update
        $totalDiscount = $sales->sum('harga_potongan');
        $netSales = $totalSales - $totalDiscount;

        $totalHPP = $totalPurchase;

        // filter payment untuk pajak dan bunga
        $pembayaranTax = ['6101', '6301', '6601', '6701', '6901', '5102', '6201'];
        $filteredTaxPaymentQuery = clone $payment; // Clone the original query
        $filteredTaxPayment = $filteredTaxPaymentQuery->where(function ($query) use ($pembayaranTax) {
            foreach ($pembayaranTax as $kodeTax) {
                $query->where('kode_payment', 'not like', $kodeTax . '%');
            }
        })->get();

        // Menggabungkan payment pajak dengan ket_payment yang sama
        $groupedTaxPayments = $filteredTaxPayment->groupBy('ket_payment')->map(function ($group) {
            return [
                'ket_payment' => $group->first()->ket_payment,
                'cost_payment' => $group->sum('cost_payment')
            ];
        });

        $totalTaxPayment = $groupedTaxPayments->sum('cost_payment');

        $totalBeban = $totalPayment + $totalTaxPayment;

        $laba = ($netSales - $totalHPP) - $totalBeban;

        return $laba;
    }

    private function calculateModal()
    {
        $query = BalanceSheet::query();

        // Filter akun dengan kode 1**, 2**, dan 5**
        $account = Account::where(function ($query) {
            $query->where('kode_akun', 'like', '1%')
                ->orWhere('kode_akun', 'like', '2%')
                ->orWhere('kode_akun', 'like', '3%');
        })->get();

        $neracaAwal = $query->paginate(7);

        // Filter kode_akun 1** dan 5** dengan pola regular expression
        $aktiva = $neracaAwal->filter(function ($item) {
            return preg_match('/^1\d{2}$|^5\d{2}$/', $item->account->kode_akun);
        });

        // Filter kode_akun 2**, 3**, dan 4**
        $liabilitasEkuitas = $neracaAwal->filter(function ($item) {
            return preg_match('/^2\d{2}$|^3\d{2}$|^4\d{2}$/', $item->account->kode_akun);
        });

        $modal = $liabilitasEkuitas->sum('nominal');
        $totalNominal = $neracaAwal->sum('nominal');
        $totalModal = $totalNominal - $modal;

        return $totalModal;
    }

    public function cetak_neraca($sampai)
    {
        $totalModal = $this->calculateModal();
        $laba = $this->calculateLaba($sampai);
        $accountBalances = $this->calculateAccountBalances($sampai);

        // logo
        $imagePath = public_path('backend/asset/img/logos/Logo HV.png');
        if (!File::exists($imagePath)) {
            return "Logo tidak ditemukan";
        }
        $imageData = File::get($imagePath);
        if ($imageData === false) {
            return "Gagal membaca file image";
        }
        $base64String = base64_encode($imageData);
        $base64Image = 'data:image/png;base64,' . $base64String;

        $pdf = Pdf::loadview('admin.report.cetak_neraca', compact(
            'sampai',
            'totalModal',
            'laba',
            'accountBalances',
            'base64Image',
        ));
        $pdf->setPaper('a4', 'potrait');
        return $pdf->stream('laporan-neraca-' . $sampai . '.pdf');
    }
}
