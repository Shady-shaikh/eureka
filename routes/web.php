<?php

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\backend\AdminController;
use App\Http\Controllers\backend\AccountController;
use App\Http\Controllers\backend\PermissionController;
use App\Http\Controllers\backend\GstvalueController;
use App\Http\Controllers\backend\BackendmenuController;
use App\Http\Controllers\backend\BackendsubmenuController;
use App\Http\Controllers\backend\AdminusersController;
use App\Http\Controllers\backend\RolesController;
use App\Http\Controllers\backend\ExternalusersController;
use App\Http\Controllers\backend\InternalUsersController;
use App\Http\Controllers\backend\BussinessParatnerController;
use App\Http\Controllers\backend\ClaimsController;
use App\Http\Controllers\backend\MasterDropdownController;
use App\Http\Controllers\backend\ProductsController;
use App\Http\Controllers\backend\CategoriesController;
use App\Http\Controllers\backend\SubcategoriesController;
use App\Http\Controllers\backend\BrandsController;
use App\Http\Controllers\backend\PricingsController;
use App\Http\Controllers\backend\ExpensecategoriesController;
use App\Http\Controllers\backend\ExpensesubcategoriesController;
use App\Http\Controllers\backend\ExpensesController;
use App\Http\Controllers\backend\ReturnInvoiceController;
use App\Http\Controllers\backend\CreditnoteController;
use App\Http\Controllers\backend\UomsController;
use App\Http\Controllers\backend\HsncodesController;
use App\Http\Controllers\backend\MarginSchemesController;
use App\Http\Controllers\backend\InvoiceController;
use App\Http\Controllers\backend\GSTController;
use App\Http\Controllers\backend\PurchaseorderController;
use App\Http\Controllers\backend\SchemesController;
use App\Http\Controllers\backend\OrderbookingController;
use App\Http\Controllers\backend\OrderfulfilmentController;
use App\Http\Controllers\backend\GoodsservicereceiptsController;
use App\Http\Controllers\backend\ApinvoiceController;
use App\Http\Controllers\backend\ProformaController;
use App\Http\Controllers\backend\StoragelocationsController;
use App\Http\Controllers\backend\StockcountadjustmentController;
use App\Http\Controllers\backend\StockonhandController;
use App\Http\Controllers\backend\CompanyController;
use App\Http\Controllers\backend\AreaController;
use App\Http\Controllers\backend\RouteController;
use App\Http\Controllers\backend\BeatController;
use App\Http\Controllers\backend\SelllingpricingController;
use App\Http\Controllers\backend\DropdownController;
use App\Http\Controllers\backend\BeatCalenderController;
use App\Http\Controllers\backend\FocusmapController;
use App\Http\Controllers\backend\IncentivesController;
use App\Http\Controllers\backend\BillbookingController;
use App\Http\Controllers\backend\BankingpaymentController;
use App\Http\Controllers\backend\BsplcategoryController;
use App\Http\Controllers\backend\BsplsubcategoryController;
use App\Http\Controllers\backend\BsplheadsController;
use App\Http\Controllers\backend\BspltypeController;
use App\Http\Controllers\backend\ExpensemasterController;
use App\Http\Controllers\backend\BankingreceiptController;
use App\Http\Controllers\backend\ArinvoiceController;
use App\Http\Controllers\backend\SeriesmasterController;
use App\Http\Controllers\backend\LogController;
use App\Http\Controllers\backend\ProductshistoryController;
use App\Http\Controllers\backend\TrafficsourceController;
use App\Http\Controllers\backend\BinController;
use App\Http\Controllers\backend\StockManagementController;
use App\Http\Controllers\backend\BintypeController;
use App\Http\Controllers\backend\YearManagementController;
use App\Http\Controllers\backend\GoodsreceiptController;
use App\Http\Controllers\backend\ReportsController;
use App\Http\Controllers\backend\InventoryController;
use App\Http\Controllers\backend\GoodsissueController;
use App\Http\Controllers\backend\OutletController;
use App\Http\Controllers\backend\CountryController;
use App\Http\Controllers\backend\StateController;
use App\Http\Controllers\backend\CityController;
use App\Http\Controllers\backend\ZonesController; //27-02-2024
use App\Http\Controllers\backend\BpgroupController; //11-03-2024
use Illuminate\Support\Facades\Artisan;

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


Route::get('/clear-cache', function () {
    $exitCode = Artisan::call('cache:clear');
    // return what you want
});
//Clear configurations:
Route::get('/config-clear', function () {
    $status = Artisan::call('config:clear');
    return '<h1>Configurations cleared</h1>';
});

//Clear cache:
Route::get('/cache-clear', function () {
    $status = Artisan::call('cache:clear');
    return '<h1>Cache cleared</h1>';
});

//Clear configuration cache:
Route::get('/config-cache', function () {
    $status = Artisan::call('config:cache');
    return '<h1>Configurations cache cleared</h1>';
});

//Clear route cache:
Route::get('/route-clear', function () {
    $status = Artisan::call('route:clear');
    return '<h1>Route cache cleared</h1>';
});

//Clear view cache:
Route::get('/view-clear', function () {
    $status = Artisan::call('view:clear');
    return '<h1>View cache cleared</h1>';
});

//dump autoload:
Route::get('/dump-autoload', function () {
    $status = Artisan::call('dump-autoload');
    return '<h1>Dumped Autoload</h1>';
});

// usama_13-02-2024 fetch project name dynamic and default red to admin
$url = Request::url(); // Get the current URL
$segments = explode('/', parse_url($url, PHP_URL_PATH)); // Split the URL by slashes
$projectName = $segments[1];

Route::redirect('/', '/' . $projectName . '/admin');

// Route::get('/', 'HomeController@index');
// Route::get('/', function () {
//     return view('welcome');
// });


Route::prefix('admin')->group(function () {

    Route::group(['middleware' => 'admin.guest'], function () {
        Route::get('/login', [AccountController::class, 'showLoginForm'])->name('admin.login');
        Route::post('/login', [AccountController::class, 'login'])->name('admin.login.submit');
    });



    Route::group(['middleware' => 'admin.auth'], function () {
        // Route::get('/', [AdminController::class,'index'])->name('admin');
        Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
        Route::get('/profile', [AdminController::class, 'myProfile'])->name('admin.profile');
        Route::get('/get_parent_roles', [AdminController::class, 'get_parent_roles'])->name('admin.get_parent_roles');
        Route::post('/update_profile', [AdminController::class, 'updateProfile'])->name('admin.update_profile');
        Route::get('/changepassword', [AdminController::class, 'changePassword'])->name('admin.change_password');
        Route::post('/updatepassword', [AdminController::class, 'updatePassword'])->name('admin.update_password');
        Route::get('/logout', [AccountController::class, 'logout'])->name('admin.logout');
        Route::get('/permissions', [PermissionController::class, 'index'])->name('admin.permissions');
        Route::get('/permissions/create', [PermissionController::class, 'create'])->name('admin.permissions.create');
        Route::post('/permissions/store', [PermissionController::class, 'store'])->name('admin.permissions.store');
        Route::get('/permissions/edit/{id}', [PermissionController::class, 'edit'])->name('admin.permissions.edit');
        Route::post('/permissions/update', [PermissionController::class, 'update'])->name('admin.permissions.update');
        Route::get('/permissions/delete/{id}', [PermissionController::class, 'destroy'])->name('admin.permissions.delete');
        Route::resource('admin/permission', 'PermissionController');
        Route::get('/backendmenu', [BackendmenuController::class, 'index'])->name('admin.backendmenu');
        Route::get('/backendmenu/create', [BackendmenuController::class, 'create'])->name('admin.backendmenu.create');
        Route::post('/backendmenu/store', [BackendmenuController::class, 'store'])->name('admin.backendmenu.store');
        Route::get('/backendmenu/edit/{id}', [BackendmenuController::class, 'edit'])->name('admin.backendmenu.edit');
        Route::post('/backendmenu/update', [BackendmenuController::class, 'update'])->name('admin.backendmenu.update');
        Route::get('/backendmenu/delete/{id}', [BackendmenuController::class, 'destroy'])->name('admin.backendmenu.delete');
        Route::get('/backendmenu/view/{id}', [BackendmenuController::class, 'show'])->name('admin.backendmenu.view');
        Route::resource('admin/backendmenu', 'BackendmenuController');
        Route::get('/backendsubmenu', [BackendsubmenuController::class, 'index'])->name('admin.backendsubmenu');
        Route::get('/backendsubmenu/menu/{menu_id}', [BackendsubmenuController::class, 'menu'])->name('admin.backendsubmenu.menu');
        Route::get('/backendsubmenu/create/{menu_id?}', [BackendsubmenuController::class, 'create'])->name('admin.backendsubmenu.create');
        Route::post('/backendsubmenu/store', [BackendsubmenuController::class, 'store'])->name('admin.backendsubmenu.store');
        Route::get('/backendsubmenu/edit/{id}', [BackendsubmenuController::class, 'edit'])->name('admin.backendsubmenu.edit');
        Route::post('/backendsubmenu/update', [BackendsubmenuController::class, 'update'])->name('admin.backendsubmenu.update');
        Route::get('/backendsubmenu/delete/{id}', [BackendsubmenuController::class, 'destroy'])->name('admin.backendsubmenu.delete');
        Route::get('/backendsubmenu/view/{id}', [BackendsubmenuController::class, 'show'])->name('admin.backendsubmenu.view');
        Route::resource('admin/backendsubmenu', 'BackendsubmenuController');

        Route::get('/adminusers', [AdminusersController::class, 'index'])->name('admin.adminusers');
        Route::get('/adminusers/create', [AdminusersController::class, 'create'])->name('admin.adminusers.create');
        Route::post('/adminusers/store', [AdminusersController::class, 'store'])->name('admin.adminusers.store');
        Route::get('/adminusers/edit/{id}', [AdminusersController::class, 'edit'])->name('admin.adminusers.edit');
        Route::post('/adminusers/update', [AdminusersController::class, 'update'])->name('admin.adminusers.update');
        Route::get('/adminusers/delete/{id}', [AdminusersController::class, 'destroy'])->name('admin.adminusers.delete');
        Route::get('/adminusers/view/{id}', [AdminusersController::class, 'show'])->name('admin.adminusers.view');
        Route::get('/adminusers/editstatus/{id}', [AdminusersController::class, 'editstatus'])->name('admin.adminusers.editstatus');
        Route::post('/adminusers/updatestatus', [AdminusersController::class, 'updatestatus'])->name('admin.adminusers.updatestatus');
        Route::resource('admin/adminusers', 'AdminusersController');

        // for testing cache
        Route::get('/getemp', [AdminusersController::class, 'getemp'])->name('admin.getemp');


        //admin.roles
        Route::get('/roles', [RolesController::class, 'index'])->name('admin.roles');
        Route::get('/roles/create', [RolesController::class, 'create'])->name('admin.roles.create');
        Route::post('/roles/store', [RolesController::class, 'store'])->name('admin.roles.store');
        Route::get('/roles/edit/{id}', [RolesController::class, 'edit'])->name('admin.roles.edit');
        Route::post('/roles/update', [RolesController::class, 'update'])->name('admin.roles.update');
        Route::get('/roles/delete/{id}', [RolesController::class, 'destroy'])->name('admin.roles.delete');
        Route::get('/roles/view/{id}', [RolesController::class, 'show'])->name('admin.roles.view');
        Route::resource('admin/roles', 'RolesController');
        //admin external usrs
        Route::get('/externalusers', [ExternalusersController::class, 'index'])->name('admin.externalusers');
        Route::get('/externalusers/create', [ExternalusersController::class, 'create'])->name('admin.externalusers.create');
        Route::post('/externalusers/store', [ExternalusersController::class, 'store'])->name('admin.externalusers.store');
        Route::get('/externalusers/edit/{id}', [ExternalusersController::class, 'edit'])->name('admin.externalusers.edit');
        Route::post('/externalusers/update', [ExternalusersController::class, 'update'])->name('admin.externalusers.update');
        Route::get('/externalusers/delete/{id}', [ExternalusersController::class, 'destroy'])->name('admin.externalusers.delete');
        Route::get('/externalusers/view/{id}', [ExternalusersController::class, 'show'])->name('admin.externalusers.view');
        Route::resource('admin/externalusers', 'ExternalusersController');
        Route::get('/externalusers/editstatus/{id}', [ExternalusersController::class, 'editstatus'])->name('admin.externalusers.editstatus');
        //   Route::post('/externalusers/updatestatus', [ExternalusersController::class,'updatestatus'])->name('admin.externalusers.updatestatus');

        //admin.internalusers  //September
        //Route::get('/internalusers', [InternalUsersController::class,'index'])->name('admin.internalusers');
        Route::get('/users', [AdminController::class, 'showusers'])->name('admin.users');
        Route::get('/create', [AdminController::class, 'create'])->name('admin.create');
        Route::post('/user/store', [AdminController::class, 'store'])->name('admin.store');
        Route::get('/user/delete/{id}', [AdminController::class, 'destroyUser'])->name('admin.user.delete');
        Route::get('/user/edit/{id}', [AdminController::class, 'edit'])->name('admin.user.edit');
        Route::post('/user/update', [AdminController::class, 'update'])->name('admin.user.update');
        Route::post('/user/status', [AdminController::class, 'updatestatus'])->name('admin.user.status');

        // common dropdwon
        Route::post('/areas', [DropdownController::class, 'get_areas'])->name('admin.areas');
        Route::post('/routes', [DropdownController::class, 'get_routes'])->name('admin.routes');
        Route::post('/beat', [DropdownController::class, 'get_beat'])->name('admin.beats');
        Route::post('/contactperson', [DropdownController::class, 'get_contact_person'])->name('admin.contactperson');
        Route::post('/shipfrom', [DropdownController::class, 'get_ship_from_data'])->name('admin.shipfrom');
        Route::post('/billto', [DropdownController::class, 'get_billto_data'])->name('admin.billto');

        Route::post('/category', [DropdownController::class, 'get_category_data'])->name('admin.category');
        Route::post('/subcategory', [DropdownController::class, 'get_subcategory_data'])->name('admin.subcategory');
        Route::post('/type', [DropdownController::class, 'get_type_data'])->name('admin.type');
        Route::post('/bank', [DropdownController::class, 'get_bank_data'])->name('admin.bank');
        Route::post('/bankacc', [DropdownController::class, 'get_bank_acc_data'])->name('admin.bankacc');

        // admin.area
        Route::get('/area', [AreaController::class, 'index'])->name('admin.area');
        Route::get('/area/create', [AreaController::class, 'create'])->name('admin.area.create');
        Route::post('/area/store', [AreaController::class, 'store'])->name('admin.area.store');
        Route::get('/area/delete/{id}', [AreaController::class, 'destroyArea'])->name('admin.area.delete');
        Route::get('/area/edit/{id}', [AreaController::class, 'edit'])->name('admin.area.edit');
        Route::post('/area/update', [AreaController::class, 'update'])->name('admin.area.update');

        // admin.route
        Route::get('/route', [RouteController::class, 'index'])->name('admin.route');
        Route::get('/route/create', [RouteController::class, 'create'])->name('admin.route.create');
        Route::post('/route/store', [RouteController::class, 'store'])->name('admin.route.store');
        Route::get('/route/delete/{id}', [RouteController::class, 'destroyRoute'])->name('admin.route.delete');
        Route::get('/route/edit/{id}', [RouteController::class, 'edit'])->name('admin.route.edit');
        Route::post('/route/update', [RouteController::class, 'update'])->name('admin.route.update');

        // admin.beat
        Route::get('/beat', [BeatController::class, 'index'])->name('admin.beat');
        Route::get('/beat/create', [BeatController::class, 'create'])->name('admin.beat.create');
        Route::post('/beat/store', [BeatController::class, 'store'])->name('admin.beat.store');
        Route::get('/beat/delete/{id}', [BeatController::class, 'destroyBeat'])->name('admin.beat.delete');
        Route::get('/beat/edit/{id}', [BeatController::class, 'edit'])->name('admin.beat.edit');
        Route::post('/beat/update', [BeatController::class, 'update'])->name('admin.beat.update');

        // admin.beatcalender
        Route::get('/beatcalender', [BeatCalenderController::class, 'index'])->name('admin.beatcalender');
        Route::get('/beatcalender/create', [BeatCalenderController::class, 'create'])->name('admin.beatcalender.create');
        Route::post('/beatcalender/store', [BeatCalenderController::class, 'store'])->name('admin.beatcalender.store');
        Route::get('/beatcalender/delete/{id}', [BeatCalenderController::class, 'destroyBeatCalender'])->name('admin.beatcalender.delete');
        Route::get('/beatcalender/edit/{id}', [BeatCalenderController::class, 'edit'])->name('admin.beatcalender.edit');
        Route::post('/beatcalender/update', [BeatCalenderController::class, 'update'])->name('admin.beatcalender.update');
        
        // admin.focusmap
        Route::get('/focusmap', [FocusmapController::class, 'index'])->name('admin.focusmap');
        Route::get('/focusmap/create', [FocusmapController::class, 'create'])->name('admin.focusmap.create');
        Route::post('/focusmap/store', [FocusmapController::class, 'store'])->name('admin.focusmap.store');
        Route::get('/focusmap/delete/{id}', [FocusmapController::class, 'destroyfocusmap'])->name('admin.focusmap.delete');
        Route::get('/focusmap/edit/{id}', [FocusmapController::class, 'edit'])->name('admin.focusmap.edit');
        Route::post('/focusmap/update', [FocusmapController::class, 'update'])->name('admin.focusmap.update');

        // admin.incentives
        Route::get('/incentives', [IncentivesController::class, 'index'])->name('admin.incentives');
        Route::get('/incentives/create', [IncentivesController::class, 'create'])->name('admin.incentives.create');
        Route::post('/incentives/store', [IncentivesController::class, 'store'])->name('admin.incentives.store');
        Route::get('/incentives/delete/{id}', [IncentivesController::class, 'destroyincentives'])->name('admin.incentives.delete');
        Route::get('/incentives/edit/{id}', [IncentivesController::class, 'edit'])->name('admin.incentives.edit');
        Route::post('/incentives/update', [IncentivesController::class, 'update'])->name('admin.incentives.update');

        //admin.billbooking
        Route::get('/billbooking', [BillbookingController::class, 'index'])->name('admin.billbooking');
        Route::get('/billbooking/create', [BillbookingController::class, 'create'])->name('admin.billbooking.create');
        Route::post('/billbooking/store', [BillbookingController::class, 'store'])->name('admin.billbooking.store');
        Route::get('/billbooking/delete/{id}', [BillbookingController::class, 'destroyBillBooking'])->name('admin.billbooking.delete');
        Route::get('/billbooking/edit/{id}', [BillbookingController::class, 'edit'])->name('admin.billbooking.edit');
        Route::post('/billbooking/update', [BillbookingController::class, 'update'])->name('admin.billbooking.update');

        //admin.bankingpr
        Route::get('/bankingpayment', [BankingpaymentController::class, 'index'])->name('admin.bankingpayment');
        Route::get('/bankingpayment/create', [BankingpaymentController::class, 'create'])->name('admin.bankingpayment.create');
        Route::post('/bankingpayment/store', [BankingpaymentController::class, 'store'])->name('admin.bankingpayment.store');
        Route::get('/bankingpayment/delete/{id}', [BankingpaymentController::class, 'destroyBankingpayment'])->name('admin.bankingpayment.delete');
        Route::get('/bankingpayment/edit/{id}', [BankingpaymentController::class, 'edit'])->name('admin.bankingpayment.edit');
        Route::get('/bankingpayment/view/{id}', [BankingpaymentController::class, 'show'])->name('admin.bankingpayment.view');
        Route::post('/bankingpayment/update', [BankingpaymentController::class, 'update'])->name('admin.bankingpayment.update');

        // admin.bankingreceipt
        Route::get('/bankingreceipt', [BankingreceiptController::class, 'index'])->name('admin.bankingreceipt');
        Route::get('/bankingreceipt/create', [BankingreceiptController::class, 'create'])->name('admin.bankingreceipt.create');
        Route::post('/bankingreceipt/store', [BankingreceiptController::class, 'store'])->name('admin.bankingreceipt.store');
        Route::get('/bankingreceipt/delete/{id}', [BankingreceiptController::class, 'destroyBankingreceipt'])->name('admin.bankingreceipt.delete');
        Route::get('/bankingreceipt/edit/{id}', [BankingreceiptController::class, 'edit'])->name('admin.bankingreceipt.edit');
        Route::post('/bankingreceipt/update', [BankingreceiptController::class, 'update'])->name('admin.bankingreceipt.update');
        Route::get('/bankingreceipt/view/{id}', [BankingreceiptController::class, 'show'])->name('admin.bankingreceipt.view');


        //admin.bsplcat
        Route::get('/bsplcat', [BsplcategoryController::class, 'index'])->name('admin.bsplcat');
        Route::get('/bsplcat/create', [BsplcategoryController::class, 'create'])->name('admin.bsplcat.create');
        Route::post('/bsplcat/store', [BsplcategoryController::class, 'store'])->name('admin.bsplcat.store');
        Route::get('/bsplcat/delete/{id}', [BsplcategoryController::class, 'destroyBsplCat'])->name('admin.bsplcat.delete');
        Route::get('/bsplcat/edit/{id}', [BsplcategoryController::class, 'edit'])->name('admin.bsplcat.edit');
        Route::post('/bsplcat/update', [BsplcategoryController::class, 'update'])->name('admin.bsplcat.update');

        //admin.bsplsubcat
        Route::get('/bsplsubcat', [BsplsubcategoryController::class, 'index'])->name('admin.bsplsubcat');
        Route::get('/bsplsubcat/bsplcat/{id}', [BsplsubcategoryController::class, 'bsplcategory'])->name('admin.bsplsubcat.bsplcat');
        Route::get('/bsplsubcat/create/{id}', [BsplsubcategoryController::class, 'create'])->name('admin.bsplsubcat.create');
        Route::post('/bsplsubcat/store', [BsplsubcategoryController::class, 'store'])->name('admin.bsplsubcat.store');
        Route::get('/bsplsubcat/delete/{id}', [BsplsubcategoryController::class, 'destroyBsplSubCat'])->name('admin.bsplsubcat.delete');
        Route::get('/bsplsubcat/edit/{id}', [BsplsubcategoryController::class, 'edit'])->name('admin.bsplsubcat.edit');
        Route::post('/bsplsubcat/update', [BsplsubcategoryController::class, 'update'])->name('admin.bsplsubcat.update');

        //admin.bsplheads
        Route::get('/bsplheads', [BsplheadsController::class, 'index'])->name('admin.bsplheads');
        Route::get('/bsplheads/create', [BsplheadsController::class, 'create'])->name('admin.bsplheads.create');
        Route::post('/bsplheads/store', [BsplheadsController::class, 'store'])->name('admin.bsplheads.store');
        Route::get('/bsplheads/delete/{id}', [BsplheadsController::class, 'destroyBsplHeads'])->name('admin.bsplheads.delete');
        Route::get('/bsplheads/edit/{id}', [BsplheadsController::class, 'edit'])->name('admin.bsplheads.edit');
        Route::post('/bsplheads/update', [BsplheadsController::class, 'update'])->name('admin.bsplheads.update');

        // admin.bspltype
        Route::get('/bspltype', [BspltypeController::class, 'index'])->name('admin.bspltype');
        Route::get('/bspltype/create', [BspltypeController::class, 'create'])->name('admin.bspltype.create');
        Route::post('/bspltype/store', [BspltypeController::class, 'store'])->name('admin.bspltype.store');
        Route::get('/bspltype/delete/{id}', [BspltypeController::class, 'destroyBsplType'])->name('admin.bspltype.delete');
        Route::get('/bspltype/edit/{id}', [BspltypeController::class, 'edit'])->name('admin.bspltype.edit');
        Route::post('/bspltype/update', [BspltypeController::class, 'update'])->name('admin.bspltype.update');

        // admin.seriesmaster
        Route::get('/seriesmaster', [SeriesmasterController::class, 'index'])->name('admin.seriesmaster');
        Route::get('/seriesmaster/create', [SeriesmasterController::class, 'create'])->name('admin.seriesmaster.create');
        Route::post('/seriesmaster/store', [SeriesmasterController::class, 'store'])->name('admin.seriesmaster.store');
        Route::get('/seriesmaster/delete/{id}', [SeriesmasterController::class, 'destroyseriesmaster'])->name('admin.seriesmaster.delete');
        Route::get('/seriesmaster/edit/{id}', [SeriesmasterController::class, 'edit'])->name('admin.seriesmaster.edit');
        Route::post('/seriesmaster/update', [SeriesmasterController::class, 'update'])->name('admin.seriesmaster.update');

        // admin.outlet
        Route::get('/outlet', [OutletController::class, 'index'])->name('admin.outlet');
        Route::get('/outlet/create', [OutletController::class, 'create'])->name('admin.outlet.create');
        Route::post('/outlet/store', [OutletController::class, 'store'])->name('admin.outlet.store');
        Route::get('/outlet/delete/{id}', [OutletController::class, 'destroyoutlet'])->name('admin.outlet.delete');
        Route::get('/outlet/edit/{id}', [OutletController::class, 'edit'])->name('admin.outlet.edit');
        Route::post('/outlet/update', [OutletController::class, 'update'])->name('admin.outlet.update');


        // admin.expensemaster
        Route::get('/expensemaster', [ExpensemasterController::class, 'index'])->name('admin.expensemaster');
        Route::get('/expensemaster/create', [ExpensemasterController::class, 'create'])->name('admin.expensemaster.create');
        Route::post('/expensemaster/store', [ExpensemasterController::class, 'store'])->name('admin.expensemaster.store');
        Route::get('/expensemaster/delete/{id}', [ExpensemasterController::class, 'destroyExpenseMaster'])->name('admin.expensemaster.delete');
        Route::get('/expensemaster/edit/{id}', [ExpensemasterController::class, 'edit'])->name('admin.expensemaster.edit');
        Route::post('/expensemaster/update', [ExpensemasterController::class, 'update'])->name('admin.expensemaster.update');



        //admin.bussiness partner  //September
        Route::get('/bussinesspartner', [BussinessParatnerController::class, 'index'])->name('admin.bussinesspartner');
        Route::get('/bussinesspartner/create', [BussinessParatnerController::class, 'create'])->name('admin.bussinesspartner.create');
        Route::post('/bussinesspartner/store', [BussinessParatnerController::class, 'store'])->name('admin.bussinesspartner.store');
        Route::get('/bussinesspartner/delete/{id}', [BussinessParatnerController::class, 'delete'])->name('admin.bussinesspartner.delete');
        Route::get('/bussinesspartner/edit/{id}', [BussinessParatnerController::class, 'edit'])->name('admin.bussinesspartner.edit');
        Route::post('/bussinesspartner/update/{id?}', [BussinessParatnerController::class, 'update'])->name('admin.bussinesspartner.update');
        Route::get('/bussinesspartner/address/{id}', [BussinessParatnerController::class, 'address'])->name('admin.bussinesspartner.address');
        Route::get('/bussinesspartner/createaddress/{id}', [BussinessParatnerController::class, 'addaddress'])->name('admin.bussinesspartner.createaddress');
        Route::post('/bussinesspartner/storeaddress', [BussinessParatnerController::class, 'storeaddress'])->name('admin.bussinesspartner.storeaddress');
        Route::get('/bussinesspartner/editaddress/{id}', [BussinessParatnerController::class, 'editaddress'])->name('admin.bussinesspartner.editaddress');
        Route::post('/bussinesspartner/updateddress', [BussinessParatnerController::class, 'updateaddress'])->name('admin.bussinesspartner.updateaddress');
        Route::get('/bussinesspartner/deleteaddress/{id}', [BussinessParatnerController::class, 'deleteaddress'])->name('admin.bussinesspartner.deleteaddress');
        Route::post('/updateBpmaster/update', [BussinessParatnerController::class, 'updateBpmaster'])->name('admin.bussinesspartner.updateBpmaster');
        
        //admin.claims usama_06-05-2024
        Route::get('/claims', [ClaimsController::class, 'index'])->name('admin.claims');
        Route::get('/claims/create', [ClaimsController::class, 'create'])->name('admin.claims.create');
        Route::get('/claims/attachments/{id}', [ClaimsController::class, 'attachments'])->name('admin.claims.attachments');
        Route::post('/claims/store', [ClaimsController::class, 'store'])->name('admin.claims.store');
        Route::post('/claims/update_status', [ClaimsController::class, 'update_status'])->name('admin.claims.update_status');
        Route::post('/claims/attachments_store', [ClaimsController::class, 'attachments_store'])->name('admin.claims.attachments_store');
        Route::get('/claims/submission_approval/{id}/{role}', [ClaimsController::class, 'submission_approval'])->name('admin.claims.submission_approval');
        Route::get('/claims/delete/{id}', [ClaimsController::class, 'delete'])->name('admin.claims.delete');
        Route::get('/claims/edit/{id}', [ClaimsController::class, 'edit'])->name('admin.claims.edit');
        Route::get('/claims/view/{id}', [ClaimsController::class, 'view'])->name('admin.claims.view');
        Route::post('/claims/update/{id?}', [ClaimsController::class, 'update'])->name('admin.claims.update');

        // usama 29-08-2023
        Route::post('/master/store_category/', [MasterDropdownController::class, 'store_category'])->name('admin.bp_category');
        Route::post('/master/store_group/', [MasterDropdownController::class, 'store_group'])->name('admin.store_group');
        Route::post('/master/store_users/', [MasterDropdownController::class, 'store_users'])->name('admin.store_users');
        Route::post('/master/store_area/', [MasterDropdownController::class, 'store_area'])->name('admin.store_area');
        Route::post('/master/store_route/', [MasterDropdownController::class, 'store_route'])->name('admin.store_route');
        Route::post('/master/store_beat/', [MasterDropdownController::class, 'store_beat'])->name('admin.store_beat');
        Route::post('/master/store_brand/', [MasterDropdownController::class, 'store_brand'])->name('admin.store_brand');
        Route::post('/master/store_variant/', [MasterDropdownController::class, 'store_variant'])->name('admin.store_variant');
        Route::post('/master/store_product_category/', [MasterDropdownController::class, 'store_product_category'])->name('admin.store_product_category');
        Route::post('/master/store_product_sub_category/', [MasterDropdownController::class, 'store_product_sub_category'])->name('admin.store_product_sub_category');
        Route::post('/master/store_hsn/', [MasterDropdownController::class, 'store_hsn'])->name('admin.store_hsn');
        Route::post('/master/store_gl/', [MasterDropdownController::class, 'store_gl'])->name('admin.store_gl');
        Route::post('/master/get_expense/', [MasterDropdownController::class, 'get_expense'])->name('admin.get_expense');
        Route::post('/master/get_doc_number/', [MasterDropdownController::class, 'get_doc_number'])->name('admin.get_doc_number');
        Route::post('/master/get_bill_bookings/', [MasterDropdownController::class, 'get_bill_bookings'])->name('admin.get_bill_bookings');
        Route::post('/master/get_doc_numbers/', [MasterDropdownController::class, 'get_doc_numbers'])->name('admin.get_doc_numbers');
        Route::post('/master/get_series/', [MasterDropdownController::class, 'get_series'])->name('admin.get_series');
        Route::get('/master/autocomplete/', [MasterDropdownController::class, 'autocomplete'])->name('admin.autocomplete');
        Route::get('/master/get_customer/', [MasterDropdownController::class, 'get_customer'])->name('admin.get_customer');
        Route::get('/master/change_fy/', [MasterDropdownController::class, 'change_fy'])->name('admin.change_fy');
        Route::get('/master/getSalesOfficers/', [MasterDropdownController::class, 'getSalesOfficers'])->name('admin.getSalesOfficers');
        Route::get('/master/getSalesmen/', [MasterDropdownController::class, 'getSalesmen'])->name('admin.getSalesmen');
        Route::get('/master/getAse/', [MasterDropdownController::class, 'getAse'])->name('admin.getAse');
        Route::get('/master/getAsm/', [MasterDropdownController::class, 'getAsm'])->name('admin.getAsm');
        Route::get('/master/getHsnCodes/', [MasterDropdownController::class, 'getHsnCodes'])->name('admin.getHsnCodes');
        Route::get('/master/getEanBarCodes/', [MasterDropdownController::class, 'getEanBarCodes'])->name('admin.getEanBarCodes');
        Route::get('/master/getGst', [MasterDropdownController::class, 'getGst'])->name('admin.getGst');
        Route::get('/master/getStates/', [MasterDropdownController::class, 'getStates'])->name('admin.getStates');
        Route::get('/master/getCities/', [MasterDropdownController::class, 'getCities'])->name('admin.getCities');
        Route::get('/master/getGroups/', [MasterDropdownController::class, 'getGroups'])->name('admin.getGroups');
        Route::get('/master/getMargins/', [MasterDropdownController::class, 'getMargins'])->name('admin.getMargins');
        Route::get('/master/getScheme/', [MasterDropdownController::class, 'getScheme'])->name('admin.getScheme');
        Route::get('/master/getChannels/', [MasterDropdownController::class, 'getChannels'])->name('admin.getChannels');
        Route::get('/master/get_company/', [MasterDropdownController::class, 'get_company'])->name('admin.get_company');
        Route::get('/master/get_company_details/', [MasterDropdownController::class, 'get_company_details'])->name('admin.get_company_details');
        Route::get('/master/get_companies/', [MasterDropdownController::class, 'get_companies'])->name('admin.get_companies');
        Route::get('/master/getPricing/', [MasterDropdownController::class, 'getPricing'])->name('admin.getPricing');
        Route::post('/master/store_combitype/', [MasterDropdownController::class, 'store_combitype'])->name('admin.store_combitype');
        Route::get('/master/getPricingPurchase/', [MasterDropdownController::class, 'getPricingPurchase'])->name('admin.getPricingPurchase');
        Route::get('/master/getPricingSale/', [MasterDropdownController::class, 'getPricingSale'])->name('admin.getPricingSale');
        Route::get('/master/get_format/', [MasterDropdownController::class, 'get_format'])->name('admin.get_format');
        Route::get('/master/get_product/', [MasterDropdownController::class, 'get_product'])->name('admin.get_product');
        Route::get('/master/get_categories/', [MasterDropdownController::class, 'get_categories'])->name('admin.get_categories');
        Route::get('/master/get_ar_invoices/', [MasterDropdownController::class, 'get_ar_invoices'])->name('admin.get_ar_invoices');
        Route::get('/master/send_email/', [MasterDropdownController::class, 'send_email'])->name('admin.send_email');


        //contact details
        Route::get('/bussinesspartner/contact/{id}', [BussinessParatnerController::class, 'contactdetails'])->name('admin.bussinesspartner.contact');
        Route::get('/bussinesspartner/createcontact/{id}', [BussinessParatnerController::class, 'createcontact'])->name('admin.bussinesspartner.createcontact');
        Route::post('/bussinesspartner/storecontact', [BussinessParatnerController::class, 'storecontact'])->name('admin.bussinesspartner.storecontact');
        Route::get('/bussinesspartner/editcontact/{id}', [BussinessParatnerController::class, 'editcontact'])->name('admin.bussinesspartner.editcontact');
        Route::get('/bussinesspartner/deletecontact/{id}', [BussinessParatnerController::class, 'deletecontact'])->name('admin.bussinesspartner.deletecontact');

        //banking
        Route::get('/bussinesspartner/banking/{id}', [BussinessParatnerController::class, 'banking'])->name('admin.bussinesspartner.banking');
        Route::get('/bussinesspartner/addbank/{id}', [BussinessParatnerController::class, 'addbank'])->name('admin.bussinesspartner.addbank');
        Route::post('/bussinesspartner/storebank', [BussinessParatnerController::class, 'bankstore'])->name('admin.bussinesspartner.storebank');
        Route::get('/bussinesspartner/editbank/{id}', [BussinessParatnerController::class, 'editbank'])->name('admin.bussinesspartner.editbank');
        Route::post('/bussinesspartner/updatebank/', [BussinessParatnerController::class, 'banking'])->name('admin.bussinesspartner.updatebank');
        Route::get('/bussinesspartner/deletebank/{id}', [BussinessParatnerController::class, 'deletebank'])->name('admin.bussinesspartner.deletebank');


        Route::get('/loginmanagement', [LoginmanagementController::class, 'index'])->name('admin.loginmanagement');
        Route::get('/loginmanagement/create', [LoginmanagementController::class, 'create'])->name('admin.loginmanagement.create');
        Route::post('/loginmanagement/store', [LoginmanagementController::class, 'store'])->name('admin.loginmanagement.store');
        Route::get('/loginmanagement/edit/{id}', [LoginmanagementController::class, 'edit'])->name('admin.loginmanagement.edit');
        Route::post('/loginmanagement/update', [LoginmanagementController::class, 'update'])->name('admin.loginmanagement.update');
        Route::get('/loginmanagement/delete/{id}', [LoginmanagementController::class, 'destroy'])->name('admin.loginmanagement.delete');
        Route::get('/loginmanagement/view/{id}', [LoginmanagementController::class, 'show'])->name('admin.loginmanagement.view');
        Route::resource('admin/loginmanagement', 'LoginmanagementController');

        //   Route::get('/sitemanagement/up', [SitemanagementController::class,'up'])->name('admin.sitemanagement.up');//07-07-2022
        //   Route::get('/sitemanagement/down', [SitemanagementController::class,'down'])->name('admin.sitemanagement.down');//07-07-2022


        //roles Routes
        Route::get('/roles', [RolesController::class, 'index'])->name('admin.roles');
        Route::get('/roles/create', [RolesController::class, 'create'])->name('admin.roles.create');
        Route::post('/roles/store', [RolesController::class, 'store'])->name('admin.roles.store');
        Route::get('/roles/edit/{id}', [RolesController::class, 'edit'])->name('admin.roles.edit');
        Route::post('/roles/update', [RolesController::class, 'update'])->name('admin.roles.update');
        Route::get('/roles/delete/{id}', [RolesController::class, 'destroy'])->name('admin.roles.delete');
        Route::get('/roles/view/{id}', [RolesController::class, 'show'])->name('admin.roles.view');
        Route::resource('admin/roles', 'RolesController');

        Route::resource('/gst_value',GstValueController::class);

        //products Routes
        Route::get('/products', [ProductsController::class, 'index'])->name('admin.products');
        Route::get('/products/create', [ProductsController::class, 'create'])->name('admin.products.create');
        Route::post('/products/store', [ProductsController::class, 'store'])->name('admin.products.store');
        Route::get('/products/edit/{id}', [ProductsController::class, 'edit'])->name('admin.products.edit');
        Route::post('/products/update', [ProductsController::class, 'update'])->name('admin.products.update');
        Route::get('/products/delete/{id}', [ProductsController::class, 'destroy'])->name('admin.products.delete');
        Route::get('/products/view/{id}', [ProductsController::class, 'show'])->name('admin.products.view');
        Route::post('/products/getsubcategory', [ProductsController::class, 'getsubcategory'])->name('admin.products.getsubcategory');
        Route::post('/updateProduct/update', [ProductsController::class, 'updateProduct'])->name('admin.products.updateProduct');
        Route::resource('admin/products', 'ProductsController');

        Route::get('/categories', [CategoriesController::class, 'index'])->name('admin.categories');
        Route::get('/categories/create', [CategoriesController::class, 'create'])->name('admin.categories.create');
        Route::post('/categories/store', [CategoriesController::class, 'store'])->name('admin.categories.store');
        Route::get('/categories/edit/{id}', [CategoriesController::class, 'edit'])->name('admin.categories.edit');
        Route::post('/categories/update', [CategoriesController::class, 'update'])->name('admin.categories.update');
        Route::get('/categories/delete/{id}', [CategoriesController::class, 'destroy'])->name('admin.categories.delete');
        Route::get('/categories/view/{id}', [CategoriesController::class, 'show'])->name('admin.categories.view');
        Route::resource('admin/categories', 'CategoriesController');

        Route::get('/subcategories', [SubcategoriesController::class, 'index'])->name('admin.subcategories');
        Route::get('/subcategories/category/{category_id}', [SubcategoriesController::class, 'category'])->name('admin.subcategories.category');
        Route::get('/subcategories/create/{category_id?}', [SubcategoriesController::class, 'create'])->name('admin.subcategories.create');
        Route::post('/subcategories/store', [SubcategoriesController::class, 'store'])->name('admin.subcategories.store');
        Route::get('/subcategories/edit/{id}', [SubcategoriesController::class, 'edit'])->name('admin.subcategories.edit');
        Route::post('/subcategories/update', [SubcategoriesController::class, 'update'])->name('admin.subcategories.update');
        Route::get('/subcategories/delete/{id}', [SubcategoriesController::class, 'destroy'])->name('admin.subcategories.delete');
        Route::get('/subcategories/view/{id}', [SubcategoriesController::class, 'show'])->name('admin.subcategories.view');
        Route::resource('admin/subcategories', 'SubcategoriesController');

        Route::get('/brands', [BrandsController::class, 'index'])->name('admin.brands');
        Route::get('/brands/index', [BrandsController::class, 'index']);
        Route::get('/brands/create', [BrandsController::class, 'create'])->name('admin.brands.create');
        Route::post('/brands/store', [BrandsController::class, 'store'])->name('admin.brands.store');
        Route::get('/brands/edit/{id}', [BrandsController::class, 'edit'])->name('admin.brands.edit');
        Route::post('/brands/update', [BrandsController::class, 'update'])->name('admin.brands.update');
        Route::get('/brands/delete/{id}', [BrandsController::class, 'destroy'])->name('admin.brands.delete');
        Route::get('/brands/view/{id}', [BrandsController::class, 'show'])->name('admin.brands.view');
        Route::resource('admin/brands', 'BrandsController');

        //price master Routes
        Route::get('/pricings', [PricingsController::class, 'index'])->name('admin.pricings');
        Route::get('/pricings/create', [PricingsController::class, 'create'])->name('admin.pricings.create');
        Route::post('/pricings/store', [PricingsController::class, 'store'])->name('admin.pricings.store');
        Route::get('/pricings/edit/{id}', [PricingsController::class, 'edit'])->name('admin.pricings.edit');
        Route::post('/pricings/update', [PricingsController::class, 'update'])->name('admin.pricings.update');
        Route::get('/pricings/delete/{id}', [PricingsController::class, 'destroy'])->name('admin.pricings.delete');
        Route::get('/pricings/view/{id}', [PricingsController::class, 'show'])->name('admin.pricings.view');
        Route::any('/pricings/setpricing/{id}', [PricingsController::class, 'setpricing'])->name('admin.pricings.setpricing');
        Route::any('/pricings/item_codes', [PricingsController::class, 'item_codes'])->name('admin.pricings.item_codes');
        Route::any('/pricings/get_product', [PricingsController::class, 'get_product'])->name('admin.pricings.get_product');
        Route::post('/updatepricings/update', [PricingsController::class, 'updatepricings'])->name('admin.pricings.updatepricings');
        Route::resource('admin/pricings', 'PricingsController');

        //price master Routes
        Route::get('/sellingpricing', [SelllingpricingController::class, 'index'])->name('admin.sellingpricing');
        Route::get('/sellingpricing/create', [SelllingpricingController::class, 'create'])->name('admin.sellingpricing.create');
        Route::post('/sellingpricing/store', [SelllingpricingController::class, 'store'])->name('admin.sellingpricing.store');
        Route::get('/sellingpricing/edit/{id}', [SelllingpricingController::class, 'edit'])->name('admin.sellingpricing.edit');
        Route::post('/sellingpricing/update', [SelllingpricingController::class, 'update'])->name('admin.sellingpricing.update');
        Route::get('/sellingpricing/delete/{id}', [SelllingpricingController::class, 'destroy'])->name('admin.sellingpricing.delete');
        Route::get('/sellingpricing/view/{id}', [SelllingpricingController::class, 'show'])->name('admin.sellingpricing.view');
        Route::any('/sellingpricing/setpricing/{id}', [SelllingpricingController::class, 'setpricing'])->name('admin.sellingpricing.setpricing');
        Route::any('/sellingpricing/item_codes', [SelllingpricingController::class, 'item_codes'])->name('admin.sellingpricing.item_codes');
        Route::any('/sellingpricing/get_product', [SelllingpricingController::class, 'get_product'])->name('admin.sellingpricing.get_product');
        Route::post('/updatesellingpricing/update', [SelllingpricingController::class, 'updatesellingpricing'])->name('admin.sellingpricing.updatepricings');
        Route::resource('admin/sellingpricing', 'SelllingpricingController');

        //inventory Routes
        Route::get('/inventory', [InventoryController::class, 'index'])->name('admin.inventory');
        Route::get('/inventory/create', [InventoryController::class, 'create'])->name('admin.inventory.create');
        Route::post('/inventory/store', [InventoryController::class, 'store'])->name('admin.inventory.store');
        Route::get('/inventory/edit/{id}', [InventoryController::class, 'edit'])->name('admin.inventory.edit');
        Route::post('/inventory/update', [InventoryController::class, 'update'])->name('admin.inventory.update');
        Route::get('/inventory/delete/{id}', [InventoryController::class, 'destroy'])->name('admin.inventory.delete');
        Route::get('/inventory/view/{id}', [InventoryController::class, 'show'])->name('admin.inventory.view');
        Route::resource('admin/inventory', 'InventoryController');


        Route::get('/expensecategories', [ExpensecategoriesController::class, 'index'])->name('admin.expensecategories');
        Route::get('/expensecategories/create', [ExpensecategoriesController::class, 'create'])->name('admin.expensecategories.create');
        Route::post('/expensecategories/store', [ExpensecategoriesController::class, 'store'])->name('admin.expensecategories.store');
        Route::get('/expensecategories/edit/{id}', [ExpensecategoriesController::class, 'edit'])->name('admin.expensecategories.edit');
        Route::post('/expensecategories/update', [ExpensecategoriesController::class, 'update'])->name('admin.expensecategories.update');
        Route::get('/expensecategories/delete/{id}', [ExpensecategoriesController::class, 'destroy'])->name('admin.expensecategories.delete');
        Route::get('/expensecategories/view/{id}', [ExpensecategoriesController::class, 'show'])->name('admin.expensecategories.view');

        Route::resource('admin/expensecategories', 'ExpensecategoriesController');

        Route::get('/expensesubcategories', [ExpensesubcategoriesController::class, 'index'])->name('admin.expensesubcategories');
        Route::get('/expensesubcategories/expensecategory/{category_id}', [ExpensesubcategoriesController::class, 'expensecategory'])->name('admin.expensesubcategories.expensecategory');
        Route::get('/expensesubcategories/create/{category_id?}', [ExpensesubcategoriesController::class, 'create'])->name('admin.expensesubcategories.create');
        Route::post('/expensesubcategories/store', [ExpensesubcategoriesController::class, 'store'])->name('admin.expensesubcategories.store');
        Route::get('/expensesubcategories/edit/{id}', [ExpensesubcategoriesController::class, 'edit'])->name('admin.expensesubcategories.edit');
        Route::post('/expensesubcategories/update', [ExpensesubcategoriesController::class, 'update'])->name('admin.expensesubcategories.update');
        Route::get('/expensesubcategories/delete/{id}', [ExpensesubcategoriesController::class, 'destroy'])->name('admin.expensesubcategories.delete');
        Route::get('/expensesubcategories/view/{id}', [ExpensesubcategoriesController::class, 'show'])->name('admin.expensesubcategories.view');
        Route::resource('admin/expensesubcategories', 'ExpensesubcategoriesController');

        Route::get('/expenses', [ExpensesController::class, 'index'])->name('admin.expenses');
        Route::get('/expenses/create', [ExpensesController::class, 'create'])->name('admin.expenses.create');
        Route::post('/expenses/store', [ExpensesController::class, 'store'])->name('admin.expenses.store');
        Route::get('/expenses/edit/{id}', [ExpensesController::class, 'edit'])->name('admin.expenses.edit');
        Route::post('/expenses/update', [ExpensesController::class, 'update'])->name('admin.expenses.update');
        Route::get('/expenses/delete/{id}', [ExpensesController::class, 'destroy'])->name('admin.expenses.delete');
        Route::get('/expenses/view/{id}', [ExpensesController::class, 'show'])->name('admin.expenses.view');
        Route::post('/expenses/getexpensesubcategory', [ExpensesController::class, 'getexpensesubcategory'])->name('admin.expenses.getexpensesubcategory');
        Route::resource('admin/expenses', 'ExpensesController');

        Route::get('/uoms', [UomsController::class, 'index'])->name('admin.uoms');
        Route::get('/uoms/index', [UomsController::class, 'index']);
        Route::get('/uoms/create', [UomsController::class, 'create'])->name('admin.uoms.create');
        Route::post('/uoms/store', [UomsController::class, 'store'])->name('admin.uoms.store');
        Route::get('/uoms/edit/{id}', [UomsController::class, 'edit'])->name('admin.uoms.edit');
        Route::post('/uoms/update', [UomsController::class, 'update'])->name('admin.uoms.update');
        Route::get('/uoms/delete/{id}', [UomsController::class, 'destroy'])->name('admin.uoms.delete');
        Route::get('/uoms/view/{id}', [UomsController::class, 'show'])->name('admin.uoms.view');
        Route::resource('admin/uoms', 'UomsController');

        Route::get('/hsncodes', [HsncodesController::class, 'index'])->name('admin.hsncodes');
        Route::get('/hsncodes/index', [HsncodesController::class, 'index']);
        Route::get('/hsncodes/create', [HsncodesController::class, 'create'])->name('admin.hsncodes.create');
        Route::post('/hsncodes/store', [HsncodesController::class, 'store'])->name('admin.hsncodes.store');
        Route::get('/hsncodes/edit/{id}', [HsncodesController::class, 'edit'])->name('admin.hsncodes.edit');
        Route::post('/hsncodes/update', [HsncodesController::class, 'update'])->name('admin.hsncodes.update');
        Route::get('/hsncodes/delete/{id}', [HsncodesController::class, 'destroy'])->name('admin.hsncodes.delete');
        Route::get('/hsncodes/view/{id}', [HsncodesController::class, 'show'])->name('admin.hsncodes.view');
        Route::resource('admin/hsncodes', 'HsncodesController');


        Route::get('/invoice', [InvoiceController::class, 'index'])->name('admin.invoice');
        Route::get('/invoice/create', [InvoiceController::class, 'create'])->name('admin.invoice.create');
        Route::post('/invoice/store', [InvoiceController::class, 'store'])->name('admin.invoice.store');
        Route::get('/invoice/edit/{id}', [InvoiceController::class, 'edit'])->name('admin.invoice.edit');
        Route::get('/invoice/view/{id}', [InvoiceController::class, 'show'])->name('admin.invoice.view');
        Route::post('/invoice/update', [InvoiceController::class, 'update'])->name('admin.invoice.update');
        Route::get('/invoice/delete/{id}', [InvoiceController::class, 'destroy'])->name('admin.invoice.delete');
        Route::get('/invoice/amountinwords/{number}', [InvoiceController::class, 'amountinwords'])->name('admin.invoice.amountinwords');
        Route::get('/invoice/partydetails/{partyid}', [InvoiceController::class, 'partydetails'])->name('admin.invoice.partydetails');
        Route::get('autocomplete', [InvoiceController::class, 'autocomplete'])->name('autocomplete');
        Route::get('/invoice/download/{id}', [InvoiceController::class, 'download'])->name('admin.invoice.download');
        Route::resource('admin/invoice', 'InvoiceController');

        Route::get('/purchaseorder', [PurchaseorderController::class, 'index'])->name('admin.purchaseorder');
        Route::get('/purchaseorder/create', [PurchaseorderController::class, 'create'])->name('admin.purchaseorder.create');
        Route::post('/purchaseorder/store', [PurchaseorderController::class, 'store'])->name('admin.purchaseorder.store');
        Route::get('/purchaseorder/edit/{id}', [PurchaseorderController::class, 'edit'])->name('admin.purchaseorder.edit');
        Route::get('/purchaseorder/view/{id}', [PurchaseorderController::class, 'show'])->name('admin.purchaseorder.view');
        Route::post('/purchaseorder/update', [PurchaseorderController::class, 'update'])->name('admin.purchaseorder.update');
        Route::get('/purchaseorder/delete/{id}', [PurchaseorderController::class, 'destroy'])->name('admin.purchaseorder.delete');
        Route::get('/purchaseorder/amountinwords/{number}', [PurchaseorderController::class, 'amountinwords'])->name('admin.purchaseorder.amountinwords');
        Route::get('/purchaseorder/partydetails/{partyid}', [PurchaseorderController::class, 'partydetails'])->name('admin.purchaseorder.partydetails');
        Route::get('/purchaseorder/autocomplete', [PurchaseorderController::class, 'autocomplete'])->name('purchaseorder.autocomplete');
        Route::get('/purchaseorder/download/{id}', [PurchaseorderController::class, 'download'])->name('admin.purchaseorder.download');
        Route::get('/purchaseorder/creategr/{id}', [PurchaseorderController::class, 'creategr'])->name('admin.purchaseorder.creategr');
        Route::get('/goodsservicereceipts/createapinvoice/{id}', [GoodsservicereceiptsController::class, 'createapinvoice'])->name('admin.goodsservicereceipts.createapinvoice');
        Route::get('/proforma/createarinvoice/{id}', [ProformaController::class, 'createarinvoice'])->name('admin.proforma.createarinvoice');
        Route::resource('admin/purchaseorder', 'PurchaseorderController');

        Route::get('/orderbooking', [OrderbookingController::class, 'index'])->name('admin.orderbooking');
        Route::get('/orderbooking/create', [OrderbookingController::class, 'create'])->name('admin.orderbooking.create');
        Route::post('/orderbooking/store', [OrderbookingController::class, 'store'])->name('admin.orderbooking.store');
        Route::get('/orderbooking/edit/{id}', [OrderbookingController::class, 'edit'])->name('admin.orderbooking.edit');
        Route::get('/orderbooking/view/{id}', [OrderbookingController::class, 'show'])->name('admin.orderbooking.view');
        Route::get('/orderbooking/plist/{id}', [OrderbookingController::class, 'plist'])->name('admin.orderbooking.plist');
        Route::get('/orderbooking/close_of_status/{id}', [OrderbookingController::class, 'close_of_status'])->name('admin.orderbooking.close_of_status');
        Route::post('/orderbooking/update', [OrderbookingController::class, 'update'])->name('admin.orderbooking.update');
        Route::get('/orderbooking/delete/{id}', [OrderbookingController::class, 'destroy'])->name('admin.orderbooking.delete');
        Route::get('/orderbooking/amountinwords/{number}', [OrderbookingController::class, 'amountinwords'])->name('admin.orderbooking.amountinwords');
        Route::get('/orderbooking/partydetails/{partyid}', [OrderbookingController::class, 'partydetails'])->name('admin.orderbooking.partydetails');
        Route::get('/orderbooking/autocomplete', [OrderbookingController::class, 'autocomplete'])->name('orderbooking.autocomplete');
        Route::get('/orderbooking/download/{id}', [OrderbookingController::class, 'download'])->name('admin.orderbooking.download');
        Route::get('/orderbooking/creategr/{id}', [OrderbookingController::class, 'creategr'])->name('admin.orderbooking.creategr');
        Route::get('/orderfulfilment/createarinvoice/{id}', [OrderfulfilmentController::class, 'createarinvoice'])->name('admin.orderfulfilment.createarinvoice');

        Route::resource('admin/orderbooking', 'OrderbookingController');

        Route::get('/orderfulfilment', [OrderfulfilmentController::class, 'index'])->name('admin.orderfulfilment');
        Route::get('/orderfulfilment/create', [OrderfulfilmentController::class, 'create'])->name('admin.orderfulfilment.create');
        Route::post('/orderfulfilment/store', [OrderfulfilmentController::class, 'store'])->name('admin.orderfulfilment.store');
        Route::get('/orderfulfilment/edit/{id}', [OrderfulfilmentController::class, 'edit'])->name('admin.orderfulfilment.edit');
        Route::get('/orderfulfilment/view/{id}', [OrderfulfilmentController::class, 'show'])->name('admin.orderfulfilment.view');
        Route::post('/orderfulfilment/update', [OrderfulfilmentController::class, 'update'])->name('admin.orderfulfilment.update');
        Route::get('/orderfulfilment/delete/{id}', [OrderfulfilmentController::class, 'destroy'])->name('admin.orderfulfilment.delete');
        Route::get('/orderfulfilment/amountinwords/{number}', [OrderfulfilmentController::class, 'amountinwords'])->name('admin.orderfulfilment.amountinwords');
        Route::get('/orderfulfilment/partydetails/{partyid}', [OrderfulfilmentController::class, 'partydetails'])->name('admin.orderfulfilment.partydetails');
        Route::get('/orderfulfilment/autocomplete', [OrderfulfilmentController::class, 'autocomplete'])->name('orderfulfilment.autocomplete');
        Route::get('/orderfulfilment/download/{id}', [OrderfulfilmentController::class, 'download'])->name('admin.orderfulfilment.download');
        Route::resource('admin/orderfulfilment', 'OrderfulfilmentController');

        Route::get('/goodsservicereceipts', [GoodsservicereceiptsController::class, 'index'])->name('admin.goodsservicereceipts');
        Route::get('/goodsservicereceipts/create', [GoodsservicereceiptsController::class, 'create'])->name('admin.goodsservicereceipts.create');
        Route::post('/goodsservicereceipts/store', [GoodsservicereceiptsController::class, 'store'])->name('admin.goodsservicereceipts.store');
        Route::get('/goodsservicereceipts/edit/{id}', [GoodsservicereceiptsController::class, 'edit'])->name('admin.goodsservicereceipts.edit');
        Route::get('/goodsservicereceipts/view/{id}', [GoodsservicereceiptsController::class, 'show'])->name('admin.goodsservicereceipts.view');
        Route::post('/goodsservicereceipts/update', [GoodsservicereceiptsController::class, 'update'])->name('admin.goodsservicereceipts.update');
        Route::get('/goodsservicereceipts/delete/{id}', [GoodsservicereceiptsController::class, 'destroy'])->name('admin.goodsservicereceipts.delete');
        Route::get('/goodsservicereceipts/amountinwords/{number}', [GoodsservicereceiptsController::class, 'amountinwords'])->name('admin.goodsservicereceipts.amountinwords');
        Route::get('/goodsservicereceipts/partydetails/{partyid}', [GoodsservicereceiptsController::class, 'partydetails'])->name('admin.goodsservicereceipts.partydetails');
        Route::get('/goodsservicereceipts/autocomplete', [GoodsservicereceiptsController::class, 'autocomplete'])->name('goodsservicereceipts.autocomplete');
        Route::get('/goodsservicereceipts/download/{id}', [GoodsservicereceiptsController::class, 'download'])->name('admin.goodsservicereceipts.download');
        Route::resource('admin/goodsservicereceipts', 'GoodsservicereceiptsController');

        Route::get('/apinvoice', [ApinvoiceController::class, 'index'])->name('admin.apinvoice');
        Route::get('/apinvoice/create', [ApinvoiceController::class, 'create'])->name('admin.apinvoice.create');
        Route::post('/apinvoice/store', [ApinvoiceController::class, 'store'])->name('admin.apinvoice.store');
        Route::get('/apinvoice/edit/{id}', [ApinvoiceController::class, 'edit'])->name('admin.apinvoice.edit');
        Route::get('/apinvoice/view/{id}', [ApinvoiceController::class, 'show'])->name('admin.apinvoice.view');
        Route::post('/apinvoice/update', [ApinvoiceController::class, 'update'])->name('admin.apinvoice.update');
        Route::get('/apinvoice/delete/{id}', [ApinvoiceController::class, 'destroy'])->name('admin.apinvoice.delete');
        Route::get('/apinvoice/amountinwords/{number}', [ApinvoiceController::class, 'amountinwords'])->name('admin.apinvoice.amountinwords');
        Route::get('/apinvoice/partydetails/{partyid}', [ApinvoiceController::class, 'partydetails'])->name('admin.apinvoice.partydetails');
        Route::get('/apinvoice/autocomplete', [ApinvoiceController::class, 'autocomplete'])->name('apinvoice.autocomplete');
        Route::get('/apinvoice/download/{id}', [ApinvoiceController::class, 'download'])->name('admin.apinvoice.download');
        Route::resource('admin/apinvoice', 'ApinvoiceController');

        Route::get('/arinvoice', [ArinvoiceController::class, 'index'])->name('admin.arinvoice');
        Route::get('/arinvoice/create', [ArinvoiceController::class, 'create'])->name('admin.arinvoice.create');
        Route::post('/arinvoice/store', [ArinvoiceController::class, 'store'])->name('admin.arinvoice.store');
        Route::get('/arinvoice/edit/{id}', [ArinvoiceController::class, 'edit'])->name('admin.arinvoice.edit');
        Route::get('/arinvoice/view/{id}', [ArinvoiceController::class, 'show'])->name('admin.arinvoice.view');
        Route::post('/arinvoice/update', [ArinvoiceController::class, 'update'])->name('admin.arinvoice.update');
        Route::get('/arinvoice/delete/{id}', [ArinvoiceController::class, 'destroy'])->name('admin.arinvoice.delete');
        Route::get('/arinvoice/amountinwords/{number}', [ArinvoiceController::class, 'amountinwords'])->name('admin.arinvoice.amountinwords');
        Route::get('/arinvoice/partydetails/{partyid}', [ArinvoiceController::class, 'partydetails'])->name('admin.arinvoice.partydetails');
        Route::get('/arinvoice/autocomplete', [ArinvoiceController::class, 'autocomplete'])->name('arinvoice.autocomplete');
        Route::get('/arinvoice/download/{id}', [ArinvoiceController::class, 'download'])->name('admin.arinvoice.download');
        Route::resource('admin/arinvoice', 'ArinvoiceController');

        // usama_13-03-2024 return invoice
        Route::get('/returninvoice', [ReturnInvoiceController::class, 'index'])->name('admin.returninvoice');
        Route::get('/returninvoice/index', [ReturnInvoiceController::class, 'index']);
        Route::get('/returninvoice/create', [ReturnInvoiceController::class, 'create'])->name('admin.returninvoice.create');
        Route::post('/returninvoice/update', [ReturnInvoiceController::class, 'update'])->name('admin.returninvoice.update');
        Route::get('/returninvoice/inv_data/{id}', [ReturnInvoiceController::class, 'inv_data'])->name('admin.returninvoice.inv_data');
        Route::resource('admin/returninvoice', 'ReturnInvoiceController');

        // usama_16-04-2024
        Route::get('/creditnote', [CreditnoteController::class, 'index'])->name('admin.creditnote');
        Route::get('/creditnote/index', [CreditnoteController::class, 'index']);
        Route::get('/creditnote/create', [CreditnoteController::class, 'create'])->name('admin.creditnote.create');
        Route::post('/creditnote/update', [CreditnoteController::class, 'update'])->name('admin.creditnote.update');
        Route::get('/creditnote/inv_data/{id}', [CreditnoteController::class, 'inv_data'])->name('admin.creditnote.inv_data');
        Route::resource('admin/creditnote', 'CreditnoteController');

        // usama_28-03-2024
        Route::get('/partnerledger', [ReturnInvoiceController::class, 'partnerledger'])->name('admin.partnerledger');

        // usmaa_14-03-2024-for offers
        Route::get('/schemes', [SchemesController::class, 'index'])->name('admin.schemes');
        Route::get('/schemes/create', [SchemesController::class, 'create'])->name('admin.schemes.create');
        Route::post('/schemes/store', [SchemesController::class, 'store'])->name('admin.schemes.store');
        Route::get('/schemes/edit/{id}', [SchemesController::class, 'edit'])->name('admin.schemes.edit');
        Route::post('/schemes/update', [SchemesController::class, 'update'])->name('admin.schemes.update');
        Route::get('/schemes/delete/{id}', [SchemesController::class, 'destroy'])->name('admin.schemes.delete');
        Route::get('/schemes/view/{id}', [SchemesController::class, 'show'])->name('admin.schemes.view');
        Route::resource('admin/schemes', 'SchemesController');


        // usama_15-03-2024 - for margin selling price
        Route::get('/margin', [MarginSchemesController::class, 'margin'])->name('admin.margin');
        Route::get('/margin/create', [MarginSchemesController::class, 'margin_create'])->name('admin.margin.create');
        Route::any('/margin/store', [MarginSchemesController::class, 'margin_store'])->name('admin.margin.store');
        Route::get('/margin/edit/{id}', [MarginSchemesController::class, 'margin_edit'])->name('admin.margin.edit');
        Route::any('/margin/modify', [MarginSchemesController::class, 'margin_modify'])->name('admin.margin.modify');
        Route::any('/margin/delete/{id}', [MarginSchemesController::class, 'margin_destroy'])->name('admin.margin.delete');

        Route::any('/margin/form/{id}', [MarginSchemesController::class, 'margin_form'])->name('admin.margin.form');
        Route::any('/margin/update', [MarginSchemesController::class, 'margin_update'])->name('admin.margin.update');
        Route::any('/margin/fetch_margin', [MarginSchemesController::class, 'fetch_margin'])->name('admin.margin.fetch_margin');
        Route::resource('admin/margin', 'MarginSchemesController');

        //usama 30-03-2024
        Route::get('/scheme', [MarginSchemesController::class, 'scheme'])->name('admin.scheme');
        Route::get('/scheme/create', [MarginSchemesController::class, 'scheme_create'])->name('admin.scheme.create');
        Route::any('/scheme/store', [MarginSchemesController::class, 'scheme_store'])->name('admin.scheme.store');
        Route::get('/scheme/edit/{id}', [MarginSchemesController::class, 'scheme_edit'])->name('admin.scheme.edit');
        Route::any('/scheme/modify', [MarginSchemesController::class, 'scheme_modify'])->name('admin.scheme.modify');
        Route::any('/scheme/delete/{id}', [MarginSchemesController::class, 'scheme_destroy'])->name('admin.scheme.delete');

        Route::any('/scheme/form/{id}', [MarginSchemesController::class, 'scheme_form'])->name('admin.scheme.form');
        Route::any('/scheme/update', [MarginSchemesController::class, 'scheme_update'])->name('admin.scheme.update');
        Route::any('/scheme/fetch_scheme', [MarginSchemesController::class, 'fetch_scheme'])->name('admin.scheme.fetch_scheme');
        Route::resource('admin/scheme', 'MarginSchemesController');

        Route::get('/subdmargin/form', [MarginSchemesController::class, 'subdmargin'])->name('admin.subdmargin.form');
        Route::any('/subdmargin/update', [MarginSchemesController::class, 'subdmargin_update'])->name('admin.subdmargin.update');
        Route::any('/subdmargin/fetch_subdmargin', [MarginSchemesController::class, 'fetch_subdmargin'])->name('admin.subdmargin.fetch_subdmargin');
        Route::resource('admin/subdmargin', 'MarginSchemesController');

        Route::get('/pricingladder', [MarginSchemesController::class, 'pricingladder'])->name('admin.pricingladder');
        Route::get('/pricingladder/create', [MarginSchemesController::class, 'pricingladder_create'])->name('admin.pricingladder.create');
        Route::any('/pricingladder/store', [MarginSchemesController::class, 'pricingladder_store'])->name('admin.pricingladder.store');
        Route::get('/pricingladder/edit/{id}', [MarginSchemesController::class, 'pricingladder_edit'])->name('admin.pricingladder.edit');
        Route::any('/pricingladder/modify', [MarginSchemesController::class, 'pricingladder_modify'])->name('admin.pricingladder.modify');
        Route::any('/pricingladder/delete/{id}', [MarginSchemesController::class, 'pricingladder_destroy'])->name('admin.pricingladder.delete');
        Route::any('/pricingladder/store_db', [MarginSchemesController::class, 'store_db'])->name('admin.pricingladder.store_db');

        Route::get('/pricingladder/form/{id}', [MarginSchemesController::class, 'pricingladder_form'])->name('admin.pricingladder.form');
        Route::any('/pricingladder/fetch_pricingladder', [MarginSchemesController::class, 'fetch_pricingladder'])->name('admin.pricingladder.fetch_pricingladder');
        Route::resource('admin/pricingladder', 'MarginSchemesController');

        //usama_12-04-2024
        Route::get('/mrp', [MarginSchemesController::class, 'mrp'])->name('admin.mrp');



        Route::get('/proforma', [ProformaController::class, 'index'])->name('admin.proforma');
        Route::get('/proforma/create', [ProformaController::class, 'create'])->name('admin.proforma.create');
        Route::post('/proforma/store', [ProformaController::class, 'store'])->name('admin.proforma.store');
        Route::get('/proforma/edit/{id}', [ProformaController::class, 'edit'])->name('admin.proforma.edit');
        Route::get('/proforma/view/{id}', [ProformaController::class, 'show'])->name('admin.proforma.view');
        Route::post('/proforma/update', [ProformaController::class, 'update'])->name('admin.proforma.update');
        Route::get('/proforma/delete/{id}', [ProformaController::class, 'destroy'])->name('admin.proforma.delete');
        Route::get('/proforma/amountinwords/{number}', [ProformaController::class, 'amountinwords'])->name('admin.proforma.amountinwords');
        Route::get('/proforma/partydetails/{partyid}', [ProformaController::class, 'partydetails'])->name('admin.proforma.partydetails');
        Route::get('autocomplete', [ProformaController::class, 'autocomplete'])->name('autocomplete');
        Route::get('/proforma/download/{id}', [ProformaController::class, 'download'])->name('admin.proforma.download');
        Route::resource('admin/proforma', 'ProformaController');


        Route::get('/storagelocations', [StoragelocationsController::class, 'index'])->name('admin.storagelocations');
        Route::get('/storagelocations/index', [StoragelocationsController::class, 'index']);
        Route::get('/storagelocations/create', [StoragelocationsController::class, 'create'])->name('admin.storagelocations.create');
        Route::post('/storagelocations/store', [StoragelocationsController::class, 'store'])->name('admin.storagelocations.store');
        Route::get('/storagelocations/edit/{id}', [StoragelocationsController::class, 'edit'])->name('admin.storagelocations.edit');
        Route::post('/storagelocations/update', [StoragelocationsController::class, 'update'])->name('admin.storagelocations.update');
        Route::get('/storagelocations/delete/{id}', [StoragelocationsController::class, 'destroy'])->name('admin.storagelocations.delete');
        Route::get('/storagelocations/view/{id}', [StoragelocationsController::class, 'show'])->name('admin.storagelocations.view');
        Route::resource('admin/storagelocations', 'StoragelocationsController');

        Route::get('/yearmanage', [YearManagementController::class, 'index'])->name('admin.yearmanage');
        Route::get('/yearmanage/index', [YearManagementController::class, 'index']);
        Route::get('/yearmanage/create', [YearManagementController::class, 'create'])->name('admin.yearmanage.create');
        Route::post('/yearmanage/store', [YearManagementController::class, 'store'])->name('admin.yearmanage.store');
        Route::get('/yearmanage/edit/{id}', [YearManagementController::class, 'edit'])->name('admin.yearmanage.edit');
        Route::post('/yearmanage/update', [YearManagementController::class, 'update'])->name('admin.yearmanage.update');
        Route::get('/yearmanage/delete/{id}', [YearManagementController::class, 'destroy'])->name('admin.yearmanage.delete');
        Route::get('/yearmanage/view/{id}', [YearManagementController::class, 'show'])->name('admin.yearmanage.view');
        Route::resource('admin/yearmanage', 'YearManagementController');


        Route::get('/bin', [BinController::class, 'index'])->name('admin.bin');
        Route::get('/bin/index', [BinController::class, 'index']);
        Route::get('/bin/create', [BinController::class, 'create'])->name('admin.bin.create');
        Route::post('/bin/store', [BinController::class, 'store'])->name('admin.bin.store');
        Route::get('/bin/edit/{id}', [BinController::class, 'edit'])->name('admin.bin.edit');
        Route::post('/bin/update', [BinController::class, 'update'])->name('admin.bin.update');
        Route::get('/bin/delete/{id}', [BinController::class, 'destroy'])->name('admin.bin.delete');
        Route::get('/bin/view/{id}', [BinController::class, 'show'])->name('admin.bin.view');
        Route::resource('admin/bin', 'BinController');

        Route::get('/stockcountadjustment', [StockcountadjustmentController::class, 'index'])->name('admin.stockcountadjustment');
        Route::get('/stockcountadjustment/index', [StockcountadjustmentController::class, 'index']);
        Route::get('/stockcountadjustment/create', [StockcountadjustmentController::class, 'create'])->name('admin.stockcountadjustment.create');
        Route::post('/stockcountadjustment/store', [StockcountadjustmentController::class, 'store'])->name('admin.stockcountadjustment.store');
        Route::get('/stockcountadjustment/edit/{id}', [StockcountadjustmentController::class, 'edit'])->name('admin.stockcountadjustment.edit');
        Route::post('/stockcountadjustment/update', [StockcountadjustmentController::class, 'update'])->name('admin.stockcountadjustment.update');
        Route::get('/stockcountadjustment/delete/{id}', [StockcountadjustmentController::class, 'destroy'])->name('admin.stockcountadjustment.delete');
        Route::get('/stockcountadjustment/view/{id}', [StockcountadjustmentController::class, 'show'])->name('admin.stockcountadjustment.view');
        Route::resource('admin/stockcountadjustment', 'StockcountadjustmentController');


        Route::get('/stockmanagement', [StockManagementController::class, 'index'])->name('admin.stockmanagement');
        Route::get('/stockmanagement/index', [StockManagementController::class, 'index']);
        Route::post('/stockmanagement/update', [StockManagementController::class, 'update'])->name('admin.stockmanagement.update');
        Route::any('/stockmanagement/bin_transfer_history', [StockManagementController::class, 'bin_transfer_history'])->name('admin.stockmanagement.bin_transfer_history');
        Route::any('/stockmanagement/get_bins', [StockManagementController::class, 'get_bins'])->name('admin.stockmanagement.get_bins');
        Route::any('/stockmanagement/get_batches', [StockManagementController::class, 'get_batches'])->name('admin.stockmanagement.get_batches');
        Route::any('/stockmanagement/get_available_qty', [StockManagementController::class, 'get_available_qty'])->name('admin.stockmanagement.get_available_qty');
        Route::resource('admin/stockmanagement', 'StockManagementController');

        Route::get('/goodsreceipt', [GoodsreceiptController::class, 'index'])->name('admin.goodsreceipt');
        Route::get('/goodsreceipt/index', [GoodsreceiptController::class, 'index']);
        Route::get('/goodsreceipt/create', [GoodsreceiptController::class, 'create'])->name('admin.goodsreceipt.create');
        Route::post('/goodsreceipt/update', [GoodsreceiptController::class, 'update'])->name('admin.goodsreceipt.update');
        Route::resource('admin/goodsreceipt', 'GoodsreceiptController');

        Route::get('/goodsissue', [GoodsissueController::class, 'index'])->name('admin.goodsissue');
        Route::get('/goodsissue/index', [GoodsissueController::class, 'index']);
        Route::get('/goodsissue/create', [GoodsissueController::class, 'create'])->name('admin.goodsissue.create');
        Route::post('/goodsissue/update', [GoodsissueController::class, 'update'])->name('admin.goodsissue.update');
        Route::resource('admin/goodsissue', 'GoodsissueController');

        Route::get('/reports', [ReportsController::class, 'reports'])->name('admin.reports');
        Route::get('/sales', [ReportsController::class, 'sales'])->name('admin.sales');
        Route::get('/purchase', [ReportsController::class, 'purchase'])->name('admin.purchase');



        Route::get('/bintype', [BintypeController::class, 'index'])->name('admin.bintype');
        Route::get('/bintype/index', [BintypeController::class, 'index']);
        Route::get('/bintype/create', [BintypeController::class, 'create'])->name('admin.bintype.create');
        Route::post('/bintype/store', [BintypeController::class, 'store'])->name('admin.bintype.store');
        Route::get('/bintype/edit/{id}', [BintypeController::class, 'edit'])->name('admin.bintype.edit');
        Route::post('/bintype/update', [BintypeController::class, 'update'])->name('admin.bintype.update');
        Route::get('/bintype/delete/{id}', [BintypeController::class, 'destroy'])->name('admin.bintype.delete');
        Route::get('/bintype/view/{id}', [BintypeController::class, 'show'])->name('admin.bintype.view');
        Route::resource('admin/bintype', 'BintypeController');


        Route::get('/stockonhand', [StockonhandController::class, 'index'])->name('admin.stockonhand');
        Route::get('/stockonhand/index', [StockonhandController::class, 'index']);
        Route::get('/stockonhand/create', [StockonhandController::class, 'create'])->name('admin.stockonhand.create');
        Route::post('/stockonhand/store', [StockonhandController::class, 'store'])->name('admin.stockonhand.store');
        Route::get('/stockonhand/edit/{id}', [StockonhandController::class, 'edit'])->name('admin.stockonhand.edit');
        Route::post('/stockonhand/update', [StockonhandController::class, 'update'])->name('admin.stockonhand.update');
        Route::get('/stockonhand/delete/{id}', [StockonhandController::class, 'destroy'])->name('admin.stockonhand.delete');
        Route::get('/stockonhand/view/{id}', [StockonhandController::class, 'show'])->name('admin.stockonhand.view');
        Route::resource('admin/stockonhand', 'StockonhandController');

        // GST module
        Route::get('/gst', [GSTController::class, 'index'])->name('admin.gst.index');
        Route::get('/gst/create', [GSTController::class, 'create'])->name('admin.gst.create');
        Route::post('/gst/store', [GSTController::class, 'store'])->name('admin.gst.store');
        Route::get('/gst/delete/{id}', [GSTController::class, 'destroyUser'])->name('admin.gst.delete');
        Route::get('/gst/edit/{id}', [GSTController::class, 'edit'])->name('admin.gst.edit');
        Route::post('/gst/update', [GSTController::class, 'update'])->name('admin.gst.update');

        // usama_17-02-2024-country module
        Route::get('/country', [CountryController::class, 'index'])->name('admin.country');
        Route::get('/country/create', [CountryController::class, 'create'])->name('admin.country.create');
        Route::post('/country/store', [CountryController::class, 'store'])->name('admin.country.store');
        Route::get('/country/delete/{id}', [CountryController::class, 'destroy'])->name('admin.country.delete');
        Route::get('/country/edit/{id}', [CountryController::class, 'edit'])->name('admin.country.edit');
        Route::post('/country/update', [CountryController::class, 'update'])->name('admin.country.update');

        Route::get('/state/{id}', [StateController::class, 'index'])->name('admin.state');
        Route::get('/state/create', [StateController::class, 'create'])->name('admin.state.create');
        Route::post('/state/store', [StateController::class, 'store'])->name('admin.state.store');
        Route::get('/state/delete/{id}', [StateController::class, 'delete'])->name('admin.state.delete');
        Route::get('/state/edit/{id}', [StateController::class, 'edit'])->name('admin.state.edit');
        Route::post('/state/update', [StateController::class, 'update'])->name('admin.state.update');

        Route::get('/states/city/{id}', [CityController::class, 'index'])->name('admin.city');
        Route::get('/states/city/create', [CityController::class, 'create'])->name('admin.city.create');
        Route::post('/states/city/store', [CityController::class, 'store'])->name('admin.city.store');
        Route::get('/states/city/delete/{id}', [CityController::class, 'delete'])->name('admin.city.delete');
        Route::get('/states/city/edit/{id}', [CityController::class, 'edit'])->name('admin.city.edit');
        Route::post('/states/city/update', [CityController::class, 'update'])->name('admin.city.update');

        Route::get('/company', [CompanyController::class, 'index'])->name('admin.company');
        Route::get('/company/create', [CompanyController::class, 'create'])->name('admin.company.create');
        Route::post('/company/store', [CompanyController::class, 'store'])->name('admin.company.store');
        Route::get('/company/edit/{id}', [CompanyController::class, 'edit'])->name('admin.company.edit');
        Route::post('/company/update', [CompanyController::class, 'update'])->name('admin.company.update');
        Route::get('/company/delete/{id}', [CompanyController::class, 'destroy'])->name('admin.company.delete');
        Route::resource('admin/company', 'CompanyController');

        // admin.logs
        Route::get('/logs', [LogController::class, 'index'])->name('admin.logs');
        Route::get('/logs/create', [LogController::class, 'create'])->name('admin.logs.create');
        Route::post('/logs/store', [LogController::class, 'store'])->name('admin.logs.store');
        Route::get('/logs/delete/{id}', [LogController::class, 'destroylogs'])->name('admin.logs.delete');
        Route::get('/logs/edit/{id}', [LogController::class, 'edit'])->name('admin.logs.edit');
        Route::post('/logs/update', [LogController::class, 'update'])->name('admin.logs.update');

        // admin.productshistory
        Route::get('/productshistory', [ProductshistoryController::class, 'index'])->name('admin.productshistory');
        Route::get('/productshistory/show/{id}', [ProductshistoryController::class, 'show'])->name('admin.productshistory.show');

        Route::get('/trafficsource', [TrafficsourceController::class, 'index'])->name('admin.trafficsource');

        // admin.zones //27-02-2024
        Route::get('/zones', [ZonesController::class, 'index'])->name('admin.zones');
        Route::get('/zones/create', [ZonesController::class, 'create'])->name('admin.zones.create');
        Route::post('/zones/store', [ZonesController::class, 'store'])->name('admin.zones.store');
        Route::get('/zones/delete/{id}', [ZonesController::class, 'destroyZones'])->name('admin.zones.delete');
        Route::get('/zones/edit/{id}', [ZonesController::class, 'edit'])->name('admin.zones.edit');
        Route::post('/zones/update', [ZonesController::class, 'update'])->name('admin.zones.update');

        //admin.bpgroup -usama_11-03-2024
        Route::get('/bpgroup', [BpgroupController::class, 'index'])->name('admin.bpgroup');
        Route::get('/bpgroup/create', [BpgroupController::class, 'create'])->name('admin.bpgroup.create');
        Route::post('/bpgroup/store', [BpgroupController::class, 'store'])->name('admin.bpgroup.store');
        Route::get('/bpgroup/delete/{id}', [BpgroupController::class, 'destroybpgroup'])->name('admin.bpgroup.delete');
        Route::get('/bpgroup/edit/{id}', [BpgroupController::class, 'edit'])->name('admin.bpgroup.edit');
        Route::post('/bpgroup/update', [BpgroupController::class, 'update'])->name('admin.bpgroup.update');


        // updated by vijay sutar - financial year session update after dropdown change event
        Route::post('/loginyear', [AdminController::class, 'changeYear'])->name('admin.change.year');
    });
});
