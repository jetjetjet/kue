<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuditTrailController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BoardController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MenuCategoryController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ImageUploadController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PromoController;
use App\Http\Controllers\ShowcaseController;
use App\Http\Controllers\NotifController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('login', [LoginController::class, 'index']);
Route::get('logout', [LoginController::class, 'getLogoff']);
Route::post('login', [LoginController::class, 'postLogin']);
Route::get('init-app', [SettingController::class, 'initAppSetup']);
Route::get('setup-cafe', [SettingController::class, 'indexSetup']);
Route::get('/order-cust',[OrderController::class, 'indexCustomer']);
Route::get('/dapur/lists', [App\Http\Controllers\DapurController::class, 'getLists']);
Route::get('/meja/cariTersedia/{id?}', [BoardController::class, 'searchAvailable']);
Route::post('init-setting-setup', [SettingController::class, 'postSettingSetup']);
Route::get('/dapur', [App\Http\Controllers\DapurController::class, 'index']);

Route::get('/product/grid', [ProductController::class, 'getGrid']);
  

Route::group(array('middleware' => 'auth'), function ()
{
  Route::get('/', [DashboardController::class, 'index']);
  Route::get('/dash', [DashboardController::class, 'getChart']);

  Route::get('/log', [AuditTrailController::class, 'index'])->middleware('can:log_lihat');
  Route::get('/log/grid', [AuditTrailController::class, 'grid'])->middleware('can:log_lihat');
  
  Route::get('/jabatan', [RoleController::class, 'index'])->middleware('can:jabatan_lihat');
  Route::get('/jabatan/grid', [RoleController::class, 'getLists'])->middleware('can:jabatan_lihat');
  Route::get('/jabatan/detail/{id?}', [RoleController::class, 'getById'])->middleware('can:jabatan_lihat');
  Route::post('/jabatan/simpan', [RoleController::class, 'save'])->middleware('can:jabatan_simpan');
  Route::post('/jabatan/hapus/{id}', [RoleController::class, 'deleteById'])->middleware('can:jabatan_hapus');

  Route::get('/laporan', [ReportController::class, 'index'])->middleware('can:laporan_lihat');
  Route::get('/laporan-pengeluaran', [ReportController::class, 'exIndex'])->middleware('can:laporan_lihat');
  Route::get('/laporan-shift', [ReportController::class, 'shiftReport'])->middleware('can:laporan_lihat');
  Route::get('/laporan-menu', [ReportController::class, 'menuReport'])->middleware('can:laporan_lihat');
  
  
  Route::get('/meja', [BoardController::class, 'index'])->middleware('can:meja_lihat');
  Route::get('/meja/grid', [BoardController::class, 'getLists'])->middleware('can:meja_lihat');
  Route::get('/meja/detail/{id?}', [BoardController::class, 'getById'])->middleware('can:meja_lihat');
  Route::post('/meja/simpan', [BoardController::class, 'save'])->middleware('can:meja_simpan');
  Route::post('/meja/hapus/{id}', [BoardController::class, 'deleteById'])->middleware('can:meja_hapus');

  Route::get('/menu', [MenuController::class, 'index'])->middleware('can:menu_lihat');
  Route::get('/menu/grid', [MenuController::class, 'getLists'])->middleware('can:menu_lihat');
  Route::get('/menu/detail/{id?}', [MenuController::class, 'getById'])->middleware('can:menu_lihat,menu_simpan');
  Route::get('/menu/menuorder', [ MenuController::class, 'menuOrder']);
  Route::get('/menu/search', [MenuController::class, 'searchMenu']);
  Route::post('/menu/simpan', [MenuController::class, 'save'])->middleware('can:menu_simpan');
  Route::post('/menu/hapus/{id}', [MenuController::class, 'deleteById'])->middleware('can:menu_hapus');
  
  Route::get('/menu-category/search', [MenuCategoryController::class, 'search']);
  Route::post('/menu-category/save', [MenuCategoryController::class, 'save'])->middleware('can:menu_simpan');
  Route::post('/menu-category/delete/{id?}', [MenuCategoryController::class, 'delete'])->middleware('can:menu_simpan');
  
  Route::get('/notif/icon', [NotifController::class, 'notifIcon']);

  Route::get('/setting', [SettingController::class, 'index'])->middleware('can:pengaturan_lihat');
  Route::get('/setting/grid', [SettingController::class, 'getLists'])->middleware('can:pengaturan_lihat');
  Route::get('/setting/detail/{id?}', [SettingController::class, 'getById'])->middleware('can:pengaturan_lihat');
  Route::get('/setting/aboutus', [SettingController::class, 'aboutus']);
  Route::get('/setting/hotkey', [SettingController::class, 'hotkey']);
  Route::get('/setting/notif', [SettingController::class, 'initSocket']);
  Route::get('/setting/start-notif', [SettingController::class, 'startSocket']);
  Route::get('/setting/backupdb', [SettingController::class, 'backupDb'])->middleware('can:pengaturan_backupdb');
  Route::post('/setting/simpan', [SettingController::class, 'save'])->middleware('can:pengaturan_edit');
  
  Route::get('/order/index', [OrderController::class, 'index'])->middleware('can:order_lihat');
  Route::get('/order/index/grid', [OrderController::class, 'grid'])->middleware('can:order_lihat');
  Route::get('/order/preorder', [OrderController::class, 'preOrder'])->middleware('can:order_lihat');
  Route::get('/order/preorder/grid', [OrderController::class, 'getPO'])->middleware('can:order_lihat');
  Route::get('/order/{id?}', [OrderController::class, 'order'])->middleware('can:order_lihat');
  Route::get('/order/detail/{id?}', [ OrderController::class, 'detail' ])->middleware('can:order_lihat');
  Route::get('/order/detail/grid/{idOrder}', [ OrderController::class, 'getDetail' ]);
  Route::get('/order/cetak/struk/{idOrder}', [OrderController::class, 'orderReceipt']);
  Route::post('/order/bayar/cetak/{idOrder}', [OrderController::class, 'orderReceiptkasir']);
  Route::post('/open/drawer', [OrderController::class, 'opendrawer'])->middleware('can:order_pembayaran');
  Route::post('/cek/printer', [OrderController::class, 'ping']);
  Route::post('/open/drawerauth', [OrderController::class, 'opendraweraudit'])->middleware('can:tambahan_bukalaci');
  Route::post('/order/save/{id?}', [OrderController::class, 'save'])->middleware('can:order_simpan');
  Route::post('/order/api-save/{id?}', [OrderController::class, 'apiSave'])->middleware('can:order_pelayan');
  Route::post('/order/hapus/{id}', [OrderController::class, 'deleteById'])->middleware('can:order_hapus');
  Route::post('/order/batal/{id}', [OrderController::class, 'voidById'])->middleware('can:order_batal');
  Route::post('/order/bayar/{id}', [OrderController::class, 'paidById'])->middleware('can:order_pembayaran');
  Route::post('/order/selesai/{id}', [OrderController::class, 'completeById'])->middleware('can:order_pembayaran');
  Route::post('/order/delivered/{id}/{idSub}', [OrderController::class, 'deliver'])->middleware('can:order_simpan');

  Route::get('/pengeluaran', [ExpenseController::class, 'index'])->middleware('can:pengeluaran_lihat,pengeluaran_simpan');
  Route::get('/pengeluaran/grid', [ExpenseController::class, 'getLists'])->middleware('can:pengeluaran_lihat');
  Route::get('/pengeluaran/detail/{id?}', [ExpenseController::class, 'getById'])->middleware('can:pengeluaran_lihat');
  Route::post('/pengeluaran/simpan', [ExpenseController::class, 'save'])->middleware('can:pengeluaran_simpan');
  Route::post('/pengeluaran/hapus/{id}', [ExpenseController::class, 'deleteById'])->middleware('can:pengeluaran_hapus');
  Route::post('/pengeluaran/proses/{id}', [ExpenseController::class, 'proceedById'])->middleware('can:pengeluaran_proses');

  Route::get('/profile/{id}', [UserController::class, 'getProfile']);
  Route::post('/profile/simpan/{id}', [UserController::class, 'saveProfile']);
  Route::post('/profile/ubah-password/{id}', [UserController::class, 'changeProfilePassword']);

  Route::get('/promo', [PromoController::class, 'index'])->middleware('can:promo_lihat,promo_simpan');
  Route::get('/promo/grid', [PromoController::class, 'getLists'])->middleware('can:promo_lihat');
  Route::get('/promo/detail/{id?}', [PromoController::class, 'getById'])->middleware('can:promo_lihat');
  Route::post('/promo/simpan', [PromoController::class, 'save'])->middleware('can:promo_simpan');
  Route::post('/promo/hapus/{id}', [PromoController::class, 'deleteById'])->middleware('can:promo_hapus');
  Route::post('/promo/hapus-sub/{idSub}', [PromoController::class, 'deleteSub'])->middleware('can:promo_hapus');
  
  Route::get('/product', [ProductController::class, 'index'])->middleware('can:product_lihat');
  Route::get('/api/product/detail/{id}', [ProductController::class, 'apiGetDetail'])->middleware('can:product_lihat');
  Route::get('/api/product/showcase-code/{id}', [ProductController::class, 'apiGetShowcaseCode'])->middleware('can:product_lihat');
  Route::get('/product/search-showcase', [ProductController::class, 'searchProductShowcase'])->middleware('can:product_lihat');
  Route::get('/product/detail/{id?}', [ProductController::class, 'getById'])->middleware('can:promo_lihat');
  Route::get('/product/search', [ProductController::class, 'searchProducts']);
  Route::post('/product/simpan', [ProductController::class, 'save'])->middleware('can:promo_simpan');
  Route::post('/product/hapus/{id}', [ProductController::class, 'deleteById'])->middleware('can:promo_hapus');
  
  Route::get('/product-category/search', [ProductCategoryController::class, 'search']);
  Route::post('/product-category/save', [ProductCategoryController::class, 'save'])->middleware('can:product_simpan');
  Route::post('/product-category/delete/{id?}', [ProductCategoryController::class, 'delete'])->middleware('can:product_simpan');

  Route::get('/purchase', [ProductCategoryController::class, 'index']);
  Route::get('/purchase/edit/{id?}', [PurchaseController::class, 'edit'])->middleware('can:purchase_simpan');

  Route::get('/shift', [ShiftController::class, 'index'])->middleware('can:shift_lihat');
  Route::get('/shift/grid', [ShiftController::class, 'getLists'])->middleware('can:shift_lihat');
  Route::get('/shift/detail/{id?}', [ShiftController::class, 'getById'])->middleware('can:shift_lihat');
  Route::get('/shift/close/{id}', [ShiftController::class, 'getClose'])->middleware('can:shift_tutup');
  Route::get('/shift/edit/{id}', [ShiftController::class, 'getEdit'])->middleware('can:shift_simpan');
  Route::post('/shift/simpan', [ShiftController::class, 'save'])->middleware('can:shift_simpan');
  Route::post('/shift/edit', [ShiftController::class, 'edit'])->middleware('can:shift_simpan');
  Route::post('/shift/close', [ShiftController::class, 'close'])->middleware('can:shift_tutup');
  Route::post('/shift/hapus/{id}', [ShiftController::class, 'deleteById'])->middleware('can:shift_hapus');
  
  Route::get('/showcase', [ShowcaseController::class, 'index'])->middleware('can:showcase_lihat');
  Route::get('/showcase/grid', [ShowcaseController::class, 'getLists'])->middleware('can:shift_lihat');
  Route::get('/showcase/detail/{id?}', [ShowcaseController::class, 'getById'])->middleware('can:showcase_lihat');
  Route::post('/showcase/simpan', [ShowcaseController::class, 'save'])->middleware('can:showcase_simpan');
  Route::post('/showcase/hapus/{id}', [ShowcaseController::class, 'deleteById'])->middleware('can:showcase_hapus');
  Route::post('/showcase/expired/{id}', [ShowcaseController::class, 'expiredById'])->middleware('can:showcase_hapus');
  
  Route::get('/user', [UserController::class, 'index'])->middleware('can:user_lihat');
  Route::get('/user/grid', [UserController::class, 'getLists'])->middleware('can:user_lihat');
  Route::get('/user/detail/{id?}', [UserController::class, 'getById'])->middleware('can:user_lihat','can:user_simpan');
  Route::get('/user/cari', [UserController::class, 'searchUser']);
  Route::post('/user/simpan', [UserController::class, 'save'])->middleware('can:user_simpan');
  Route::post('/user/ubahpassword/{id}',[UserController::class, 'changePassword'])->middleware('can:user_simpan');
  Route::post('/user/hapus/{id}', [UserController::class, 'deleteById'])->middleware('can:user_hapus');
});


