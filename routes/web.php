<?php

use App\Models\ApMstr;
use App\Models\PoMstr;
use App\Models\Measurement;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PriceController;
use App\Http\Controllers\SoDetController;
use App\Http\Controllers\TsDetController;
use App\Http\Controllers\ApMstrController;
use App\Http\Controllers\ArMstrController;
use App\Http\Controllers\PoMstrController;
use App\Http\Controllers\PrMstrController;
use App\Http\Controllers\SaMstrController;
use App\Http\Controllers\SoMstrController;
use App\Http\Controllers\SrMstrController;
use App\Http\Controllers\StocksController;
use App\Http\Controllers\TsMstrController;
use App\Http\Controllers\BpbMstrController;
use App\Http\Controllers\LocMstrController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SuppMstrController;
use App\Http\Controllers\AppayMstrController;
use App\Http\Controllers\ArpayMstrController;
use App\Http\Controllers\BatchMstrController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SalesMstrController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProductCatController;
use App\Http\Controllers\MeasurementController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\StoreProfileController;
use App\Http\Controllers\ProductBundleController;
use App\Http\Controllers\CashierSessionController;
use App\Http\Controllers\CustTransactionController;
use App\Http\Controllers\FinancialRecordController;
use App\Http\Controllers\ProductPlacementController;
use App\Http\Controllers\StockTransactionController;
use App\Http\Controllers\ReceivablePaymentController;
use App\Http\Controllers\ExpenseTransactionController;
use App\Http\Controllers\ProductMeasurementController;
use App\Http\Controllers\ProductTransactionController;

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::resource('UserMstr', UserController::class);
    Route::put('/UserMstr/{id}/update-inline', [UserController::class, 'updateInline'])->name('UserMstr.updateInline');

    Route::resource('RoleMstr', RoleController::class);
    Route::get('RoleMstr/{idRole}/assignRole', [RoleController::class, 'assignRole'])->name('RoleMstr.assignRole');
    Route::put('RoleMstr/{idRole}/update', [RoleController::class, 'update'])->name('RoleMstr.update');

    Route::resource('PermissionMstr', PermissionController::class);

    Route::resource('ProductMstr', ProductController::class);
    Route::post('ProductMstr/updateMeasurement', [ProductController::class, 'updateMeasurement'])->name('updateMeasurement');
    Route::get('ProductMstr/{idProduct}/EditPrdMeasurement', [ProductController::class, 'EditPrdMeasurement'])->name('EditPrdMeasurement');
    Route::get('ProductBundle/{idProduct}/edit-data', [ProductController::class, 'getEditData'])->name('getEditData');


    Route::resource('MeasurementMstr', MeasurementController::class);

    Route::resource('Transaction', TransactionController::class);

    Route::resource('CustMstr', CustomerController::class);

    Route::resource('CustTransaction', CustTransactionController::class);

    Route::resource('ProductTransaction', ProductTransactionController::class);

    Route::resource('StockTransaction', StockTransactionController::class);

    Route::resource('ProductMeasurement', ProductMeasurementController::class);

    Route::resource('PriceMstr', PriceController::class);
    Route::get('/price/{id}', [PriceController::class, 'edit']);
    Route::post('/price/update', [PriceController::class, 'update']);

    Route::resource('FinancialRecord', FinancialRecordController::class);

    Route::resource('ExpenseTransaction', ExpenseTransactionController::class);

    Route::resource('Stock', StocksController::class);
    Route::get('Stock/{transId}/history', [StocksController::class, 'stockHistory'])->name('stockHistory');

    Route::resource('ReceivablePayment', ReceivablePaymentController::class);

    Route::resource('PurchaseOrder', PoMstrController::class);
    Route::get('/priceHistory', [PoMstrController::class, 'priceHistory'])->name('PurchaseOrder.priceHistory');
    Route::delete('PurchaseOrder/{id}/destroy', [PoMstrController::class, 'destroy'])->name('PurchaseOrderList.destroy');
    Route::get('/PurchaseOrder/product/{id}/ums', [PoMstrController::class, 'getProductUms']);



    Route::resource('BpbMstr', BpbMstrController::class);
    // Route::get('BpbMstr/create', [BpbMstrController::class, 'create'])->name('BpbMstr.create');
    // Route::post('BpbMstr', [BpbMstrController::class, 'store'])->name('BpbMstr.store');
    Route::get('BpbMstr/getPoItems/{poid}', [BpbMstrController::class, 'getPoItems'])->name('BpbMstr.getPoItems');
    Route::get('BpbMstr/{poid}/destroy', [BpbMstrController::class, 'destroy'])->name('BpbMstrList.destroy');
    Route::get('/po/{id}/detail', [BpbMstrController::class, 'getPoDetail'])->name('po.detail');
    Route::get('/BpbMstr/getPriceHistory/{productId}', [BpbMstrController::class, 'getPriceHistory'])->name('BpbMstr.getPriceHistory');
    Route::get('/BpbMstr/getMeasurementPrices/{productId}', [BpbMstrController::class, 'getMeasurementPrices']);
    Route::post('/BpbMstr/updateSellPrices', [BpbMstrController::class, 'updateSellPrices'])->name('BpbMstr.updateSellPrices');

    Route::resource('ApMstr', ApMstrController::class);
    Route::get('SupplierStatement', [ApMstrController::class, 'suppstatement'])->name('ApMstr.SuppStatement');
    Route::get('ApAgingHutang', [ApMstrController::class, 'ApAgingHutang'])->name('ApMstr.AgingHutang');
    Route::resource('AppayMstr', AppayMstrController::class);
    Route::get('/ajax/ap-by-supplier/{id}', [AppayMstrController::class, 'getApBySupplier']);

    Route::resource('ArMstr', ArMstrController::class);
    Route::get('CustStatement', [ArMstrController::class, 'custstatement'])->name('ArMstr.CustStatement');
    Route::get('AgingHutang', [ArMstrController::class, 'AgingHutang'])->name('ArMstr.AgingHutang');
    Route::get('/ajax/ar-by-customer/{id}', [ArpayMstrController::class, 'getArByCustomer']);
    Route::resource('ArpayMstr', ArpayMstrController::class);

    Route::resource('ProductBundle', ProductBundleController::class);
    Route::get('/products/single', [ProductBundleController::class, 'products']);
    Route::get('/product-measurements/{productId}', [ProductBundleController::class, 'measurements']);

    Route::resource('ProductCat', ProductCatController::class)->except('create');

    // Route::get('/ArMstrList/{$id}/Payment', [ApMstrController::class, 'ApPayment'])->name('ApMstrList.payment');

    Route::resource('LocMstr', LocMstrController::class);
    Route::get('LocMstr/{id}/delete', [LocMstrController::class, 'destroy'])->name('LocMstrList.destroy');

    Route::resource('ProductPlacement', ProductPlacementController::class);


    Route::resource('SupplierMstr', SuppMstrController::class);
    Route::get('SuppMstr/{id}/delete', [SuppMstrController::class, 'destroy'])->name('SuppMstrList.destroy');

    Route::resource('SalesMstr', SalesMstrController::class);
    Route::get('/Cashier', [SalesMstrController::class, 'cashierv2'])->name('SalesMstr.cashier');
    Route::get('/CashierV2', [SalesMstrController::class, 'cashier'])->name('SalesMstr.cashierV2');
    Route::get('/sales/print/{id}', [SalesMstrController::class, 'print'])->name('SalesMstr.print');
    Route::get('/sales/hold/list', [SalesMstrController::class, 'listHold']);
    Route::get('/sales/hold/{id}/resume', [SalesMstrController::class, 'resumeHold']);
    Route::post('/sales/hold/{id}/cancel', [SalesMstrController::class, 'cancelHold']);
    Route::get('/history-racik', [SalesMstrController::class, 'getHistoryRacik']);
    Route::resource('SrMstr', SrMstrController::class);
    Route::get('SrMstr/{id}/create', [SrMstrController::class, 'create'])->name('SrMstr.create');

    Route::get('PrMstr/', [PrMstrController::class, 'index'])->name('PrMstr.index');
    Route::get('PrMstr/create/{id}', [PrMstrController::class, 'create'])->name('PrMstr.create');
    Route::get('PrMstr/bpb-items', [PrMstrController::class, 'getBpbItems']);
    Route::post('PrMstr/', [PrMstrController::class, 'store'])->name('PrMstr.store');
    Route::get('PrMstr/{id}', [PrMstrController::class, 'show'])->name('PrMstr.show');
    Route::delete('PrMstr/{id}', [PrMstrController::class, 'destroy'])->name('PrMstr.destroy');

    Route::resource('SoMstr', SoMstrController::class);
    Route::post('/ApproveSoMstr/{id}', [SoMstrController::class, 'approve'])->name('SoMstr.approve');
    Route::get('/ApproveSo/{id}', [SoMstrController::class, 'viewApprove'])->name('SoMstr.viewApprove');
    Route::delete('/stock-opname/{so}/details', [SoMstrController::class, 'deleteDetails'])->name('deleteDetails');

    // SO - MANAGE ITEMS
    Route::get('/so/{so}/items', [SoDetController::class, 'index'])->name('so.items');
    Route::post('/so/{so}/items', [SoDetController::class, 'store'])->name('so.items.store');
    Route::put('/so/{so}/items', [SoDetController::class, 'update'])->name('so.items.update');
    Route::delete('/so/items/{detail}', [SoDetController::class, 'destroy'])->name('so.items.destroy');

    // SO - APPROVE
    Route::get('/so/{so}/approve', [SoMstrController::class, 'show'])->name('so.approve.view');
    Route::post('/so/{so}/approve', [SoMstrController::class, 'approve'])->name('so.approve');

    // SA MSTR
    Route::resource('SaMstr', SaMstrController::class);
    // Route::get('/SaMstr/{id}/delete', [SaMstrController::class, 'destroy'])->name('SaMstr.destroy');
    Route::post('/SaMstr/{id}/Post', [SaMstrController::class, 'post'])->name('SaMstr.post');
    Route::post('/SaMstr/{id}/Reverse', [SaMstrController::class, 'reverse'])->name('SaMstr.reverse');
    Route::get('/stock/qty', [SaMstrController::class, 'getQty'])
        ->name('stock.qty');
    Route::get('/batches/by-product', [SaMstrController::class, 'byProduct'])
        ->name('batches.by-product');

    Route::resource('TsMstr', TsMstrController::class);
    Route::get('/getItemTs', [TsMstrController::class, 'items'])->name('TsMstr.items');
    Route::get('/getBatchTs', [TsMstrController::class, 'batches'])->name('TsMstr.batches');
    Route::post('/TsMstr/{id}/Post', [TsMstrController::class, 'post'])->name('TsMstr.post');
    Route::delete('/TsMstr/{id}/delete', [TsMstrController::class, 'destroy'])->name('TsMstrList.destroy');
    Route::post('/TsMstr/{id}/Cancelpost', [TsMstrController::class, 'cancelpost'])->name('TsMstr.cancelpost');
    Route::put('/ts/detail/{detId}', [TsDetController::class, 'updateDetail']);
    Route::delete('/ts/detail/{detId}', [TsDetController::class, 'destroyDetail']);

    Route::post('/CashierSession/open', [CashierSessionController::class, 'open'])->name('CashierSession.open');
    Route::post('/CashierSession/close', [CashierSessionController::class, 'close'])->name('CashierSession.close');
    Route::get('/CashierSession/print/{id}', [CashierSessionController::class, 'print'])->name('CashierSession.print');

    Route::resource('StoreProfile', StoreProfileController::class);
    Route::resource('dashboard', DashboardController::class);
    Route::get('/printInvoice/{id}', [StoreProfileController::class, 'printInvoice'])->name('StoreProfile.printInvoice');
    Route::get('/StoreProfile/{id}/printNotaBeli', [StoreProfileController::class, 'printNota'])->name('StoreProfile.printNota');

    // Route::get('Transaction/{idProduct}/get-satuans', TransactionController::class);
    Route::get('/get-product', [TransactionController::class, 'getProduct']);
    Route::get('/get-satuans/{product}', [TransactionController::class, 'getSatuans']);
    Route::get('/get-harga/{product}/{satuan}', [TransactionController::class, 'getHarga']);

    Route::get('/settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/settings/update', [SettingsController::class, 'update'])->name('settings.update');
});

// Route::get('/printNota/{id}', function () {
//     return view('print.nota');
// })->middleware(['auth', 'verified'])->name('StoreProfile.printNota');

require __DIR__ . '/auth.php';
