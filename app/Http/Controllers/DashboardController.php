<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Purchase;
use App\Models\Sales;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $bulan = request()->get('bulan') ?? '';


        // SALES OVERVIEW
        $datasales = Sales::selectRaw('DATE_FORMAT(date_sales, "%m-%Y") as formatted_month, MIN(date_sales) as min_date_sales')
            ->groupBy('formatted_month')
            ->orderBy('min_date_sales', 'asc')
            ->get();

        $data = [];
        $categories = [];

        if ($datasales->isNotEmpty()) {
            if (empty($bulan)) {
                $latestRecord = Sales::selectRaw('DATE_FORMAT(date_sales, "%m-%Y") as formatted_month')
                    ->orderBy('date_sales', 'desc')
                    ->first();

                if ($latestRecord) {
                    $bulan = $latestRecord->formatted_month;
                }
            }

            $bulan2 = explode('-', $bulan);
            $bulan_dipilih = $bulan2[0];
            $tahun_dipilih = $bulan2[1];

            $sales = Sales::selectRaw('DATE_FORMAT(date_sales, "%d/%m") as formatted_date, SUM(total_sales) as total_sales_sum')
                ->whereMonth('date_sales', $bulan_dipilih)
                ->whereYear('date_sales', $tahun_dipilih)
                ->groupBy('formatted_date')
                ->orderBy('formatted_date', 'asc')
                ->get();

            // Split the results into two arrays
            $data = $sales->pluck('total_sales_sum');
            $categories = $sales->pluck('formatted_date');
        }


        // YEARLY BREAKUP
        // Get sales data for the last three years
        $years = [Carbon::now()->year, Carbon::now()->subYear()->year, Carbon::now()->subYears(2)->year];
        $salesByYear = Sales::selectRaw('YEAR(date_sales) as tahun, SUM(total_sales) as total_sales_sum')
            ->whereIn(DB::raw('YEAR(date_sales)'), $years)
            ->groupBy('tahun')
            ->orderBy('tahun', 'desc')
            ->get();

        // Extract sales data and percentage change from the result
        $salesData = $salesByYear->pluck('total_sales_sum')->toArray();
        $tahun_sebelumnya = $salesData[1] ?? 0;
        if ($tahun_sebelumnya) {
            $percentageChangeBreakup = round((($salesData[0] - $tahun_sebelumnya) / $tahun_sebelumnya) * 100);
        } else {
            $percentageChangeBreakup = 0; // Avoid division by zero
        }


        // MONTHLY EARNING
        // Get the current month and year
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;
        $previousYear = Carbon::now()->subYear()->year;

        // Get total earnings for the current month and year
        $currentMonthEarnings = Sales::whereYear('date_sales', $currentYear)
            ->whereMonth('date_sales', $currentMonth)
            ->sum('total_sales');

        // Get total earnings for the same month in the previous year
        $previousMonthEarnings = Sales::whereYear('date_sales', $previousYear)
            ->whereMonth('date_sales', $currentMonth)
            ->sum('total_sales');

        // Calculate percentage change
        if ($previousMonthEarnings != 0) {
            $percentageChange = (($currentMonthEarnings - $previousMonthEarnings) / $previousMonthEarnings) * 100;
        } else {
            $percentageChange = 0; // Avoid division by zero
        }

        // Format percentage change
        $percentageChangeFormatted = number_format($percentageChange, 2);

        // Get monthly earnings data for the last 12 months
        $monthlyEarnings = Sales::selectRaw('MONTH(date_sales) as month, YEAR(date_sales) as year, SUM(total_sales) as total_sales_sum')
            ->whereBetween('date_sales', [Carbon::now()->subMonths(11)->startOfMonth(), Carbon::now()->endOfMonth()])
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->pluck('total_sales_sum')
            ->toArray();


        // BEST SELLER
        $bestSellers = Sales::select('kode_barang', 'nama_barang', DB::raw('SUM(jumlah_sales) as total_sales_sum'))
            ->groupBy('kode_barang', 'nama_barang')
            ->orderBy('total_sales_sum', 'desc')
            ->limit(6)
            ->get();


        // TOP VENDOR
        // Fetch the top vendors based on the number of transactions
        $topVendors = Purchase::select('vendor_id', DB::raw('COUNT(*) as total_transactions'))
            ->with('vendor')
            ->groupBy('vendor_id')
            ->orderBy('total_transactions', 'desc')
            ->limit(6)
            ->get();

        // STOK PRODUCT
        // $kode = ['132', '122'];
        // $inventory = Inventory::where(function ($query) use ($kode) {
        //     foreach ($kode as $kode) {
        //         $query->where('kode_barang', 'not like', $kode . '%');
        //     }
        // })->where('jumlah_barang', '<=', '2')
        //     ->orderBy('jumlah_barang', 'asc')->get();

        // STOK PRODUCT
        $kode = ['HV/***/124', 'HV/***/105'];
        $inventory = Inventory::where(function ($query) use ($kode) {
            foreach ($kode as $k) {
                $query->where('kode_barang', 'not like', str_replace('*', '%', $k));
            }
        })->where('jumlah_barang', '<=', '2')
        ->orderBy('jumlah_barang', 'asc')
        ->get();


        return view('admin.index', compact(
            'data',
            'categories',
            'bulan',
            'datasales',
            'salesByYear',
            'salesData',
            'percentageChangeBreakup',
            'currentMonthEarnings',
            'percentageChangeFormatted',
            'monthlyEarnings',
            'bestSellers',
            'topVendors',
            'inventory',
        ));
    }
}
