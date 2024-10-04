<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CategoryAccountController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\BalanceSheetController;
use App\Http\Controllers\LedgerController;
use App\Http\Controllers\DetailController;
use Illuminate\Support\Facades\Route;

Route::get('/', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::group(['prefix' => 'admin/report', 'middleware' => ['auth', 'checkRole:Staff']], function () {
    Route::get('/labaRugi', [ReportController::class, 'laba_rugi'])->name('admin.labaRugi');
    Route::get('/labaRugi/{dari}/{sampai}/cetak', [ReportController::class, 'cetak_labarugi'])->name('admin.labaRugi.cetak');
    Route::get('/arusKas', [ReportController::class, 'kas'])->name('admin.arusKas');
    Route::get('/arusKas/{sampai}/cetak', [ReportController::class, 'cetak_aruskas'])->name('admin.arusKas.cetak');
    Route::get('/ekuitas', [ReportController::class, 'ekuitas'])->name('admin.ekuitas');
    Route::get('/ekuitas/{sampai}/cetak', [ReportController::class, 'cetak_ekuitas'])->name('admin.ekuitas.cetak');
    Route::get('/neraca', [ReportController::class, 'neraca'])->name('admin.neraca');
    Route::get('/neraca/{sampai}/cetak', [ReportController::class, 'cetak_neraca'])->name('admin.neraca.cetak');
    // Route::get('/bukuBesar', [LedgerController::class, 'buku_besar'])->name('admin.bukuBesar');
    // Route::get('/neracaAwal', [ReportController::class, 'neraca_awal'])->name('admin.neracaAwal');
    // Route::view('/invoiceSheet', 'admin.report.invoiceSheet')->name('admin.invoiceSheet');
});

Route::prefix('/admin')->group(function () {
    Route::prefix('/add')->name('admin.')->group(function () {
        Route::view('/vendor', 'admin.form.addVendor')->name('addVendor');
        Route::get('/sales', [SalesController::class, 'create'])->name('addSales');
        Route::view('/pembayaran', 'admin.form.addPembayaran')->name('addPembayaran');
        Route::get('/purchase', [PurchaseController::class, 'create'])->name('addPurchase');
        Route::view('/payment', 'admin.form.addPayment')->name('addPayment');
        Route::get('/account', [AccountController::class, 'create'])->name('addAccount');
        Route::get('/journal', [JournalController::class, 'create'])->name('addJournal');
        Route::get('/detail', [DetailController::class, 'create'])->name('addDetail');
        // Route::view('/account', 'admin.form.addAccount')->name('addAccount');
        Route::view('/invoiceSheet', 'admin.report.invoiceSheet')->name('invoiceSheet');
    });

    Route::prefix('/purchase')->name('admin.purchases.')->middleware(['auth', 'checkRole:Staff'])->group(function () {
        Route::get('/', [PurchaseController::class, 'index'])->name('index');
        Route::get('/{id}/edit', [PurchaseController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PurchaseController::class, 'update'])->name('update');
        Route::delete('/{id}', [PurchaseController::class, 'destroy'])->name('destroy');
        Route::post('/store', [PurchaseController::class, 'store'])->name('store');
    });

    Route::prefix('/sales')->name('admin.sales.')->group(function () {
        Route::get('/', [SalesController::class, 'index'])->name('index');
        Route::get('/{id}/edit', [SalesController::class, 'edit'])->name('edit');
        Route::put('/{id}', [SalesController::class, 'update'])->name('update');
        Route::delete('/{id}', [SalesController::class, 'destroy'])->name('destroy');
        Route::post('/store', [SalesController::class, 'store'])->name('store');
        Route::post('/bulk-delete', [SalesController::class, 'bulkDelete'])->name('bulk-delete');
    });

    Route::prefix('/vendor')->name('admin.vendors.')->group(function () {
        Route::get('/', [VendorController::class, 'index'])->name('index');
        Route::get('/{id}/edit', [VendorController::class, 'edit'])->name('edit');
        Route::put('/{id}', [VendorController::class, 'update'])->name('update');
        Route::delete('/{id}', [VendorController::class, 'destroy'])->name('destroy');
        Route::post('/store', [VendorController::class, 'store'])->name('store');
    });

    Route::prefix('/account')->name('admin.accounts.')->middleware(['auth', 'checkRole:Staff'])->group(function () {
        Route::get('/', [AccountController::class, 'index'])->name('index');
        Route::get('/{id}/edit', [AccountController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AccountController::class, 'update'])->name('update');
        Route::delete('/{id}', [AccountController::class, 'destroy'])->name('destroy');
        Route::post('/store', [AccountController::class, 'store'])->name('store');
    });

    Route::prefix('/journal')->name('admin.journals.')->middleware(['auth', 'checkRole:Staff'])->group(function () {
        Route::get('/', [JournalController::class, 'index'])->name('index');
        Route::get('/{id}/edit', [JournalController::class, 'edit'])->name('edit');
        Route::put('/{id}', [JournalController::class, 'update'])->name('update');
        Route::delete('/{id}', [JournalController::class, 'destroy'])->name('destroy');
        Route::post('/store', [JournalController::class, 'store'])->name('store');
    });

    Route::prefix('/detail')->name('admin.details.')->middleware(['auth', 'checkRole:Staff'])->group(function () {
        Route::get('/', [DetailController::class, 'index'])->name('index');
        Route::get('/{id}/edit', [DetailController::class, 'edit'])->name('edit');
        Route::put('/{id}', [DetailController::class, 'update'])->name('update');
        Route::delete('/{id}', [DetailController::class, 'destroy'])->name('destroy');
        Route::post('/store', [DetailController::class, 'store'])->name('store');
    });

    Route::prefix('/category')->name('admin.categories.')->middleware(['auth', 'checkRole:Staff'])->group(function () {
        Route::get('/', [CategoryAccountController::class, 'index'])->name('index');
        Route::get('/{id}/edit', [CategoryAccountController::class, 'edit'])->name('edit');
        Route::put('/{id}', [CategoryAccountController::class, 'update'])->name('update');
        Route::delete('/{id}', [CategoryAccountController::class, 'destroy'])->name('destroy');
        Route::post('/store', [CategoryAccountController::class, 'store'])->name('store');
    });

    Route::prefix('/balance')->name('admin.balances.')->middleware(['auth', 'checkRole:Staff'])->group(function () {
        Route::get('/', [BalanceSheetController::class, 'index'])->name('index');
        Route::get('/{id}/edit', [BalanceSheetController::class, 'edit'])->name('edit');
        Route::put('/{id}', [BalanceSheetController::class, 'update'])->name('update');
        Route::delete('/{id}', [BalanceSheetController::class, 'destroy'])->name('destroy');
        Route::post('/store', [BalanceSheetController::class, 'store'])->name('store');
    });

    Route::prefix('/ledger')->name('admin.ledgers.')->middleware(['auth', 'checkRole:Staff'])->group(function () {
        Route::get('/', [LedgerController::class, 'index'])->name('index');
        Route::get('/{id}/edit', [LedgerController::class, 'edit'])->name('edit');
        Route::put('/{id}', [LedgerController::class, 'update'])->name('update');
        Route::delete('/{id}', [LedgerController::class, 'destroy'])->name('destroy');
        Route::post('/store', [LedgerController::class, 'store'])->name('store');
    });

    Route::prefix('/user')->name('admin.users.')->middleware(['auth', 'checkRole:Staff'])->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit');
        Route::put('/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy');
        Route::post('/store', [UserController::class, 'store'])->name('store');
    });

    Route::prefix('/role')->name('admin.roles.')->middleware(['auth', 'checkRole:Staff'])->group(function () {
        Route::get('/', [RoleController::class, 'index'])->name('index');
        Route::get('/{id}/edit', [RoleController::class, 'edit'])->name('edit');
        Route::put('/{id}', [RoleController::class, 'update'])->name('update');
        Route::delete('/{id}', [RoleController::class, 'destroy'])->name('destroy');
        Route::post('/store', [RoleController::class, 'store'])->name('store');
    });

    Route::prefix('/inventories')->name('admin.inventories.')->group(function () {
        Route::get('/', [InventoryController::class, 'index'])->name('index');
    });

    // coba untuk admin
    // Route::prefix('/inventories')->name('admin.inventories.')->middleware(['auth', 'checkRole:Admin'])->group(function () {
    //     Route::get('/', [InventoryController::class, 'index'])->name('index');
    // });

        Route::prefix('/payment')->name('admin.payment.')->middleware(['auth', 'checkRole:Staff'])->group(function () {
        Route::get('/', [PaymentController::class, 'index'])->name('index');
        Route::get('/{id}/edit', [PaymentController::class, 'edit'])->name('edit');
        Route::put('/{id}', [PaymentController::class, 'update'])->name('update');
        Route::delete('/{id}', [PaymentController::class, 'destroy'])->name('destroy');
        Route::post('/store', [PaymentController::class, 'store'])->name('store');
    });

    Route::get('/asset', [InventoryController::class, 'peralatan'])->name('admin.asset');
    Route::get('/assetPerlengkapan', [InventoryController::class, 'perlengkapan'])->name('admin.assetPerlengkapan');
});


require __DIR__ . '/auth.php';
