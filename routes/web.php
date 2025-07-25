<?php

use App\Http\Controllers\Admin\Modules\Branches\BranchController;
use App\Http\Controllers\Admin\Modules\BranchTransfer\BranchTransferController;
use App\Http\Controllers\Admin\Modules\Delivery\DeliveredController;
use App\Http\Controllers\Admin\Modules\Delivery\DueDeliveryController;
use App\Http\Controllers\Admin\Modules\Delivery\DuePaymentDueDeliveryController;
use App\Http\Controllers\Admin\Modules\Estimate\DuePaymentController;
use App\Http\Controllers\Admin\Modules\Estimate\EstimateController;
use App\Http\Controllers\Admin\Modules\Products\BrandController;
use App\Http\Controllers\Admin\Modules\Products\CategoryController;
use App\Http\Controllers\Admin\Modules\Products\ProductController;
use App\Http\Controllers\Admin\Modules\Purchase\PurchaseController;
use App\Http\Controllers\Admin\Modules\Reports\BranchStockController;
use App\Http\Controllers\Admin\Modules\Reports\ConsolidateReportController;
use App\Http\Controllers\Admin\Modules\Reports\CustomerHistoryReportController;
use App\Http\Controllers\Admin\Modules\Reports\CustomerReportController;
use App\Http\Controllers\Admin\Modules\Reports\EstimateReportController;
use App\Http\Controllers\Admin\Modules\Reports\LowInventoryReportController;
use App\Http\Controllers\Admin\Modules\Reports\ProductWiseReportController;
use App\Http\Controllers\Admin\Modules\Reports\WareHouseReportController;
use App\Http\Controllers\Admin\Modules\Users\UserController;
use App\Http\Controllers\Branch\Modules\Delivery\DeliveredListController;
use App\Http\Controllers\Branch\Modules\Delivery\DuesDeliveryController;
use App\Http\Controllers\Branch\Modules\Delivery\SaleReturnedController;
use App\Http\Controllers\Branch\Modules\Estimate\CancelledEstimateController;
use App\Http\Controllers\Branch\Modules\Estimate\DuesEstimateListController;
use App\Http\Controllers\Branch\Modules\Estimate\EstimateListController;
use App\Http\Controllers\Branch\Modules\Purchase\BranchAllPurchaseController;
use App\Http\Controllers\Branch\Modules\Purchase\BranchLastPurchaseController;
use App\Http\Controllers\Branch\Modules\Purchase\BranchPurchaseController;
use App\Http\Controllers\Branch\Modules\Delivery\DueDeliveryNewController;
use App\Http\Controllers\LoginController;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Branch\Modules\Products\BrandBranchController;
use App\Http\Controllers\Branch\Modules\Products\CategoryBranchController;
use App\Http\Controllers\Branch\Modules\Products\ProductBranchController;
use App\Http\Controllers\Admin\Modules\BranchTransfer\InTransitAdminController;
use App\Http\Controllers\WareHouse\Modules\BranchTransfer\InTransitWarehouseController;

use App\Http\Controllers\WareHouse\Modules\Products\BrandWareHouseController;
use App\Http\Controllers\WareHouse\Modules\Products\CategoryWareHouseController;
use App\Http\Controllers\WareHouse\Modules\Products\ProductWareHouseController;

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

//common
Route::get('/', 'App\Http\Controllers\HomeController@index')->name('index');
Route::post('logged_in', [LoginController::class, 'authenticate'])->name('logged_in');
Route::get('/clear', function () {
    Artisan::call('route:clear');
    Artisan::call('config:clear');
    Artisan::call('cache:clear');
    Artisan::call('view:clear');
    Artisan::call('optimize:clear');
    return redirect()->back();
});
//common

//admin
Route::group(['prefix' => 'admin', 'middleware' => ['auth:sanctum', 'verified', 'CheckAdminUser', 'optimizeImages', 'HtmlMinifier'],], function () {

    Route::get('/dashboard', 'App\Http\Controllers\Admin\HomeController@index')->name('admin.dashboard');
    Route::get('/profile', 'App\Http\Controllers\Admin\Modules\ProfileController@index')->name('admin.profile');


    Route::prefix('product')->group(function () {

        //brand
        Route::resource('/brand', BrandController::class);
        Route::get('getBrandsList', 'App\Http\Controllers\Admin\Modules\Products\BrandController@getBrandsList')->name('brand.getBrandsList');
        //brand

        //category
        Route::resource('/category', CategoryController::class);
        Route::get('getCategoryList', 'App\Http\Controllers\Admin\Modules\Products\CategoryController@getCategoryList')->name('category.getCategoryList');
        //category

        //product
        Route::resource('/product', ProductController::class);
        Route::get('getProductList', 'App\Http\Controllers\Admin\Modules\Products\ProductController@getProductList')->name('product.getProductList');
        Route::post('productStatusChange', 'App\Http\Controllers\Admin\Modules\Products\ProductController@productStatusChange')->name('product.productStatusChange');
        Route::post('deleteBulkProducts', 'App\Http\Controllers\Admin\Modules\Products\ProductController@deleteBulkProducts')->name('admin.products.deleteBulkProducts');
        //product
    });


    Route::prefix('branch')->group(function () {

        //branch
        Route::resource('/branch', BranchController::class);
        Route::get('getBranchList', 'App\Http\Controllers\Admin\Modules\Branches\BranchController@getBranchList')->name('branch.getBranchList');
        //branch

    });


    Route::prefix('user')->group(function () {

        //user
        Route::resource('/user', UserController::class);
        Route::get('getUserList', 'App\Http\Controllers\Admin\Modules\Users\UserController@getUserList')->name('user.getUserList');
        //user

    });

    Route::prefix('estimate')->group(function () {
        //estimate
        Route::resource('/estimate', EstimateController::class);
        Route::get('getEstimateList', 'App\Http\Controllers\Admin\Modules\Estimate\EstimateController@getEstimateList')->name('estimate.getEstimateList');
        Route::get('getEstimateProductsReadyList/{id}', 'App\Http\Controllers\Admin\Modules\Estimate\EstimateController@getEstimateProductsReadyList')->name('estimate.getEstimateProductsReadyList');
        Route::get('getReadyProductDetail/{product_id}', 'App\Http\Controllers\Admin\Modules\Estimate\EstimateController@getReadyProductDetail')->name('estimate.getReadyProductDetail');

        Route::get('getEstimateListOrderToMakeIndex', 'App\Http\Controllers\Admin\Modules\Estimate\EstimateController@getEstimateListOrderToMakeIndex')->name('estimate.getEstimateListOrderToMakeIndex');
        Route::get('getEstimateListOrderToMake', 'App\Http\Controllers\Admin\Modules\Estimate\EstimateController@getEstimateListOrderToMake')->name('estimate.getEstimateListOrderToMake');
        //estimate

        //due payment list
        Route::resource('/due-payment', DuePaymentController::class);
        Route::get('getDuePaymentList', 'App\Http\Controllers\Admin\Modules\Estimate\DuePaymentController@getDuePaymentList')->name('estimate.getDuePaymentList');
        Route::post('updateSettleAmount', 'App\Http\Controllers\Admin\Modules\Estimate\DuePaymentController@updateSettleAmount')->name('estimate.updateSettleAmount');
        //due payment list


        Route::post('estimate-cancel-admin/{id}', '\App\Http\Controllers\Admin\Modules\Estimate\EstimateController@cancelEstimate')->name('admin.estimateList.cancelEstimate');


        //customize estimate product
        Route::post('checkCustomizeProductIsAddedOrNotAdmin', 'App\Http\Controllers\Admin\Modules\Estimate\EstimateController@checkCustomizeProductIsAddedOrNotAdmin')->name('admin.estimateList.checkCustomizeProductIsAddedOrNotAdmin');
        //customize estimate product

        //applly for approval
        Route::post('applyForDueApproval', '\App\Http\Controllers\Admin\Modules\Estimate\EstimateController@applyForDueApproval')->name('admin.estimateList.applyForDueApproval');
        //applly for approval
        
        
                
        //applly for getAdminStockListSearch
        Route::get('getAdminStockListSearch', '\App\Http\Controllers\Admin\Modules\DashboardController@getAdminStockListSearch')->name('admin.branchStock.getAdminStockListSearch');
        //applly for getAdminStockListSearch
        
    });


    Route::prefix('delivery')->group(function () {
        //due-delivery
        Route::resource('/due-delivery', DueDeliveryController::class);
        Route::get('getDueDeliveryList', 'App\Http\Controllers\Admin\Modules\Delivery\DueDeliveryController@getDueDeliveryList')->name('delivery.getDueDeliveryList');
        //due-delivery

        //delivered
        Route::resource('/delivered', DeliveredController::class);
        Route::get('getDeliveredList', 'App\Http\Controllers\Admin\Modules\Delivery\DeliveredController@getDeliveredList')->name('delivery.getDeliveredList');
        //delivered


        //dues-delivery-list-otm
        Route::resource('/dues-delivery-list-otm-admin', \App\Http\Controllers\Admin\Modules\Delivery\DuesDeliveryOTMController::class);
        Route::get('getDuesDeliveryListOTMAdmin', '\App\Http\Controllers\Admin\Modules\Delivery\DuesDeliveryOTMController@getDuesDeliveryListOTMAdmin')->name('branch.getDuesDeliveryListOTMAdmin.getDuesDeliveryListOTMAdmin');
        //dues-delivery-list-otm


        //sale Returned list
        Route::resource('/sale-returned-list-admin', \App\Http\Controllers\Admin\Modules\Delivery\SaleReturnedController::class);
        Route::get('getSaleReturnedListAdmin', '\App\Http\Controllers\Admin\Modules\Delivery\SaleReturnedController@getSaleReturnedListAdmin')->name('Admin.saleReturnedList.getSaleReturnedListAdmin');
        //sale Returned list


        //due-delivery
        Route::resource('/due-payment-due-delivery', DuePaymentDueDeliveryController::class);
        Route::get('getDuePaymentDueDeliveryList', [DuePaymentDueDeliveryController::class, 'getDuePaymentDueDeliveryList'])->name('delivery.getDuePaymentDueDeliveryList');
        //due-delivery


    });


    Route::prefix('branch-transfer')->group(function () {
        //in-transit
        Route::resource('/branch-transfer-admin', BranchTransferController::class);
        Route::get('getInTransitList', 'App\Http\Controllers\Admin\Modules\BranchTransfer\BranchTransferController@getInTransitList')->name('branchTransfer.getInTransitList');
        //in-transit




        //In Transit*****************
        Route::resource('/in-transit-admin', InTransitAdminController::class);
        Route::get('getInTransitAdminList', '\App\Http\Controllers\Admin\Modules\BranchTransfer\InTransitAdminController@getInTransitAdminList')->name('admin.branch-transfer.getInTransitAdminList');
        //In Transit*****************



    });


    Route::prefix('purchase')->group(function () {
        //purchase
        Route::resource('/purchase', PurchaseController::class);
        Route::get('getPurchaseList', 'App\Http\Controllers\Admin\Modules\Purchase\PurchaseController@getPurchaseList')->name('purchase.getPurchaseList');
        Route::post('approvePurchase', 'App\Http\Controllers\Admin\Modules\Purchase\PurchaseController@approvePurchase')->name('purchase.approvePurchase');
        Route::post('approvePurchaseBulk', 'App\Http\Controllers\Admin\Modules\Purchase\PurchaseController@approvePurchaseBulk')->name('purchase.approvePurchaseBulk');
        //purchase

    });


    Route::prefix('reports')->group(function () {
        //customer-report
        Route::resource('/customer-report', CustomerReportController::class);
        Route::get('getCustomerReportList', '\App\Http\Controllers\Admin\Modules\Reports\CustomerReportController@getCustomerReportList')->name('reports.getCustomerReportList');
        //customer-report


        //branch-stock-report
        Route::resource('/branch-stock-report', BranchStockController::class);
        Route::get('getBranchStockReportList', '\App\Http\Controllers\Admin\Modules\Reports\BranchStockController@getBranchStockReportList')->name('reports.getBranchStockReportList');
        //branch-stock-report


        //estimate-report
        Route::resource('/estimate-report', EstimateReportController::class);
        Route::get('getEstimateReportList', '\App\Http\Controllers\Admin\Modules\Reports\EstimateReportController@getEstimateReportList')->name('reports.getEstimateReportList');
        //estimate-report


        //warehouse-report
        Route::resource('/warehouse-report', WareHouseReportController::class);
        Route::get('getWarehouseReportList', '\App\Http\Controllers\Admin\Modules\Reports\WareHouseReportController@getWarehouseReportList')->name('reports.getWarehouseReportList');
        Route::get('getBranchStockListSearchAdmin', '\App\Http\Controllers\Admin\Modules\Reports\WareHouseReportController@getBranchStockListSearchAdmin')->name('branch.branchStock.getBranchStockListSearchAdmin');
        //warehouse-report


        //product-wise-report
        Route::resource('/product-wise-report', ProductWiseReportController::class);
        Route::get('getProductWiseReportList', '\App\Http\Controllers\Admin\Modules\Reports\ProductWiseReportController@getProductWiseReportList')->name('reports.getProductWiseReportList');
        Route::get('getEstimateListProductReport', '\App\Http\Controllers\Admin\Modules\Reports\ProductWiseReportController@getEstimateListProductReport')->name('reports.getEstimateListProductReport');
        //product-wise-report


        //low-inventory-report
        Route::resource('/low-inventory-report', LowInventoryReportController::class);
        Route::get('getLowInventoryReportList', '\App\Http\Controllers\Admin\Modules\Reports\LowInventoryReportController@getLowInventoryReportList')->name('reports.getLowInventoryReportList');
        //low-inventory-report


        //consolidate-report
        Route::resource('/consolidate-report', ConsolidateReportController::class);
        Route::get('getConsolidateReportList', '\App\Http\Controllers\Admin\Modules\Reports\ConsolidateReportController@getConsolidateReportList')->name('reports.getConsolidateReportList');
        //consolidate-report


        //customer-history-report
        Route::resource('/customer-history-report', CustomerHistoryReportController::class);
        Route::get('getCustomerHistoryReportList', [CustomerHistoryReportController::class, 'getCustomerHistoryReportList'])->name('reports.getCustomerHistoryReportList');
        //customer-history-report


    });


    //dashboard controller************
    Route::prefix('dashboard')->group(function () {
        Route::resource('/admin-dashboard', \App\Http\Controllers\Admin\Modules\DashboardController::class);
        Route::get('topFiveBranchesList', '\App\Http\Controllers\Admin\Modules\DashboardController@topFiveBranchesList')->name('admin.topFiveBranchesList');
        Route::get('topFiveQuarterBranchesList', '\App\Http\Controllers\Admin\Modules\DashboardController@topFiveQuarterBranchesList')->name('admin.topFiveQuarterBranchesList');
        Route::get('topBranchesList', '\App\Http\Controllers\Admin\Modules\DashboardController@topBranchesList')->name('admin.topBranchesList');
    });
    //dashboard controller************


    Route::prefix('sale')->group(function () {
        //sale
        Route::resource('/sale-list-admin', \App\Http\Controllers\Admin\Modules\Sale\SaleController::class);
        Route::get('getSaleList', '\App\Http\Controllers\Admin\Modules\Sale\SaleController@getSaleList')->name('admin.sale.getSaleList');
        Route::get('printSale/{id}', '\App\Http\Controllers\Admin\Modules\Sale\SaleController@printSale')->name('admin.sale.printSale');
        //sale

        //sale today's
        Route::resource('/sale-list-admin-today', \App\Http\Controllers\Admin\Modules\Sale\TodaySaleController::class);
        Route::get('getSaleListToday', '\App\Http\Controllers\Admin\Modules\Sale\TodaySaleController@getSaleListToday')->name('admin.sale.getSaleListToday');
        Route::get('printSaleToday/{id}', '\App\Http\Controllers\Admin\Modules\Sale\TodaySaleController@printSaleToday')->name('admin.sale.printSaleToday');
        //sale today's


        //daily sale
        Route::resource('/sale-list-daily-admin', \App\Http\Controllers\Admin\Modules\Sale\DsrController::class);
        Route::get('getDailySaleListAdmin', '\App\Http\Controllers\Admin\Modules\Sale\DsrController@getDailySaleListAdmin')->name('admin.sale.getDailySaleListAdmin');
        //daily sale


    });
});
//admin


//branch
Route::group(['prefix' => 'branch', 'middleware' => ['auth:sanctum', 'verified', 'CheckBranchUser', 'optimizeImages', 'HtmlMinifier'],], function () {

    Route::get('/dashboard', 'App\Http\Controllers\Branch\HomeController@index')->name('branch.dashboard');
    Route::get('/profile', 'App\Http\Controllers\Branch\Modules\ProfileController@index')->name('branch.profile');


    Route::prefix('estimate')->group(function () {

        //estimate-list
        Route::resource('/estimate-list', EstimateListController::class);
        Route::get('getEstimateList', '\App\Http\Controllers\Branch\Modules\Estimate\EstimateListController@getEstimateList')->name('branch.estimateList.getEstimateList');
        Route::get('getReadyProductDetail/{product_id}', '\App\Http\Controllers\Branch\Modules\Estimate\EstimateListController@getReadyProductDetail')->name('branch.estimateList.getReadyProductDetail');
        Route::post('getClientDetails', '\App\Http\Controllers\Branch\Modules\Estimate\EstimateListController@getClientDetails')->name('branch.estimateList.getClientDetails');

        Route::post('setPaymentTypeInSession', '\App\Http\Controllers\Branch\Modules\Estimate\EstimateListController@setPaymentTypeInSession')->name('branch.estimateList.setPaymentTypeInSession');


        Route::get('estimate-print/{id}', '\App\Http\Controllers\Branch\Modules\Estimate\EstimateListController@estimatePrint')->name('branch.estimateList.estimatePrint');
        Route::post('estimate-cancel/{id}', '\App\Http\Controllers\Branch\Modules\Estimate\EstimateListController@cancelEstimate')->name('branch.estimateList.cancelEstimate');
        //estimate-list

        //dues estimate-list
        Route::resource('/dues-estimate-list', DuesEstimateListController::class);
        Route::get('getDuesEstimateList', '\App\Http\Controllers\Branch\Modules\Estimate\DuesEstimateListController@getDuesEstimateList')->name('branch.duesEstimateList.getDuesEstimateList');
        Route::post('applyForDueApproval', '\App\Http\Controllers\Branch\Modules\Estimate\DuesEstimateListController@applyForDueApproval')->name('branch.duesEstimateList.applyForDueApproval');
        //dues estimate-list


        //cancelled estimate
        Route::resource('/estimate-list-cancelled', CancelledEstimateController::class);
        Route::get('getEstimateListCancelled', '\App\Http\Controllers\Branch\Modules\Estimate\CancelledEstimateController@getEstimateListCancelled')->name('branch.estimateListCancelled.getEstimateListCancelled');
        //cancelled estimate


        //customize estimate product
        Route::post('checkCustomizeProductIsAddedOrNot', '\App\Http\Controllers\Branch\Modules\Estimate\EstimateListController@checkCustomizeProductIsAddedOrNot')->name('branch.estimateList.checkCustomizeProductIsAddedOrNot');
        //customize estimate product


    });


    Route::prefix('delivery')->group(function () {

        //dues-delivery-list
        Route::resource('/dues-delivery-list', DuesDeliveryController::class);
        Route::get('getDuesDeliveryList', '\App\Http\Controllers\Branch\Modules\Delivery\DuesDeliveryController@getDuesDeliveryList')->name('branch.duesDeliveryList.getDuesDeliveryList');
        //dues-delivery-list


        //delivered list
        Route::resource('/delivered-list', DeliveredListController::class);
        Route::get('getDeliveredList', '\App\Http\Controllers\Branch\Modules\Delivery\DeliveredListController@getDeliveredList')->name('branch.deliveredList.getDeliveredList');
        Route::post('printChallanBulk', '\App\Http\Controllers\Branch\Modules\Delivery\DeliveredListController@printChallanBulk')->name('branch.deliveredList.printChallanBulk');
        //delivered list


        //sale Returned list
        Route::resource('/sale-returned-list', SaleReturnedController::class);
        Route::get('getSaleReturnedList', '\App\Http\Controllers\Branch\Modules\Delivery\SaleReturnedController@getSaleReturnedList')->name('branch.saleReturnedList.getSaleReturnedList');
        //sale Returned list


        //dues-delivery-list-otm
        Route::resource('/dues-delivery-list-otm', \App\Http\Controllers\Branch\Modules\Delivery\DuesDeliveryOTMController::class);
        Route::get('getDuesDeliveryListOTM', '\App\Http\Controllers\Branch\Modules\Delivery\DuesDeliveryOTMController@getDuesDeliveryListOTM')->name('branch.duesDeliveryListOTM.getDuesDeliveryListOTM');
        //dues-delivery-list-otm




        //dues-delivery-list
        Route::resource('/dues-delivery-list-new', DueDeliveryNewController::class);
        Route::get('getDuesDeliveryListNew', '\App\Http\Controllers\Branch\Modules\Delivery\DueDeliveryNewController@getDuesDeliveryListNew')->name('branch.duesDeliveryNewList.getDuesDeliveryListNew');
        Route::post('postDuesDeliveryListNew', '\App\Http\Controllers\Branch\Modules\Delivery\DueDeliveryNewController@postDuesDeliveryListNew')->name('branch.duesDeliveryNewList.postDuesDeliveryListNew');
        //dues-delivery-list


    });


    Route::prefix('branch-transfer')->group(function () {

        //branch-transfer
        Route::resource('/branch-transfer', \App\Http\Controllers\Branch\Modules\BranchTransfer\BranchTransferController::class);
        Route::get('getBranchTransferList', '\App\Http\Controllers\Branch\Modules\BranchTransfer\BranchTransferController@getBranchTransferList')->name('branch.branchTransfer.getBranchTransferList');
        Route::get('getBranchTransferProductDetail/{product_id}', '\App\Http\Controllers\Branch\Modules\BranchTransfer\BranchTransferController@getBranchTransferProductDetail')->name('branch.branchTransfer.getBranchTransferProductDetail');
        //branch-transfer




        //In Transit*****************
        Route::resource('/in-transit', \App\Http\Controllers\Branch\Modules\BranchTransfer\InTransitController::class);
        Route::get('getInTransitList', '\App\Http\Controllers\Branch\Modules\BranchTransfer\InTransitController@getInTransitList')->name('branch.branchTransfer.getInTransitList');
        //In Transit*****************


        //Transfer record************
        Route::resource('/transfer-record', \App\Http\Controllers\Branch\Modules\BranchTransfer\TransferRecordController::class);
        Route::get('getTransferRecordList', '\App\Http\Controllers\Branch\Modules\BranchTransfer\TransferRecordController@getTransferRecordList')->name('branch.branchTransfer.getTransferRecordList');
        //Transfer record************


    });


    Route::prefix('reports')->group(function () {

        //branch-stock
        Route::resource('/branch-stock', \App\Http\Controllers\Branch\Modules\Reports\BranchStockController::class);
        Route::get('getBranchStockList', '\App\Http\Controllers\Branch\Modules\Reports\BranchStockController@getBranchStockList')->name('branch.branchStock.getBranchStockList');
        Route::get('getBranchStockListSearch', '\App\Http\Controllers\Branch\Modules\Reports\BranchStockController@getBranchStockListSearch')->name('branch.branchStock.getBranchStockListSearch');

        //branch-stock


        //consolidate
        Route::resource('/consolidate', \App\Http\Controllers\Branch\Modules\Reports\ConsolidatedController::class);
        Route::get('getConsolidateList', '\App\Http\Controllers\Branch\Modules\Reports\ConsolidatedController@getConsolidateList')->name('branch.consolidate.getConsolidateList');
        Route::get('getConsolidateListSearch', '\App\Http\Controllers\Branch\Modules\Reports\ConsolidatedController@getConsolidateListSearch')->name('branch.consolidate.getConsolidateListSearch');

        //consolidate


        //product report
        Route::resource('/product-report', \App\Http\Controllers\Branch\Modules\Reports\ProductReportController::class);
        Route::get('getProductReportList', '\App\Http\Controllers\Branch\Modules\Reports\ProductReportController@getProductReportList')->name('branch.productReport.getProductReportList');
        Route::get('getBranchProductStockListSearch', '\App\Http\Controllers\Branch\Modules\Reports\ProductReportController@getBranchProductStockListSearch')->name('branch.productReport.getBranchProductStockListSearch');
        //product report


    });


    //dashboard controller************
    Route::prefix('branch-dashboard')->group(function () {
        Route::resource('/branch-dashboard', \App\Http\Controllers\Branch\Modules\DashboardController::class);
        Route::get('getBranchTransferInList', '\App\Http\Controllers\Branch\Modules\DashboardController@getBranchTransferInList')->name('branch.branchDashboard.getBranchTransferInList');
        Route::post('postBranchTransferReturn', '\App\Http\Controllers\Branch\Modules\DashboardController@postBranchTransferReturn')->name('branch.branchDashboard.postBranchTransferReturn');


        Route::get('getBranchTransferReturnList', '\App\Http\Controllers\Branch\Modules\DashboardController@getBranchTransferReturnList')->name('branch.branchDashboard.getBranchTransferReturnList');
        Route::get('getBranchTransferOutList', '\App\Http\Controllers\Branch\Modules\DashboardController@getBranchTransferOutList')->name('branch.branchDashboard.getBranchTransferOutList');
        Route::get('getBranchStockListSearch', '\App\Http\Controllers\Branch\Modules\DashboardController@getBranchStockListSearch')->name('branch.branchDashboard.getBranchStockListSearch');
        Route::get('getBranchStockListBySearch', '\App\Http\Controllers\Branch\Modules\DashboardController@getBranchStockListBySearch')->name('branch.branchDashboard.getBranchStockListBySearch');
    });
    //dashboard controller************


    Route::prefix('sale')->group(function () {
        //sale
        Route::resource('/sale-list', \App\Http\Controllers\Branch\Modules\Sale\SaleController::class);
        Route::get('getSaleList', '\App\Http\Controllers\Branch\Modules\Sale\SaleController@getSaleList')->name('branch.sale.getSaleList');
        Route::get('printSale/{id}', '\App\Http\Controllers\Branch\Modules\Sale\SaleController@printSale')->name('branch.sale.printSale');
        //sale


        //daily sale
        Route::resource('/sale-list-daily', \App\Http\Controllers\Branch\Modules\Sale\DsrController::class);
        Route::get('getDailySaleList', '\App\Http\Controllers\Branch\Modules\Sale\DsrController@getDailySaleList')->name('branch.sale.getDailySaleList');
        //daily sale


    });


    //branch-purchase
    Route::group(['prefix' => 'branch-purchase', 'middleware' => ['can:create purchase'],], function () {

        //purchase-warehouse
        Route::resource('/branch-purchase', BranchPurchaseController::class);
        Route::get('getBranchBranchTransferProductDetail/{product_id}', [BranchPurchaseController::class, 'getBranchTransferProductDetail'])->name('branch.purchase.getBranchTransferProductDetail');

        Route::resource('/branch-last-purchase', BranchLastPurchaseController::class);
        Route::get('getBranchLastPurchaseList', [BranchLastPurchaseController::class, 'getBranchLastPurchaseList'])->name('branch.purchase.getBranchLastPurchaseList');
        //purchase-warehouse

        //all-purchase
        Route::get('/branch-all-purchase', [BranchAllPurchaseController::class, 'index'])->name('branch.purchase.getBranchAllPurchaseList.index');
        Route::get('getBranchAllPurchaseList', [BranchAllPurchaseController::class, 'getBranchAllPurchaseList'])->name('branch.purchase.getBranchAllPurchaseList');
        //all-purchase

    });
    //branch-purchase



    //branch-product
    Route::prefix('product')->group(function () {

        //brand
        Route::resource('/branch-brand', BrandBranchController::class);
        Route::get('getBrandsList', 'App\Http\Controllers\Branch\Modules\Products\BrandBranchController@getBrandsList')->name('branch-brand.getBrandsList');
        //brand

        //category
        Route::resource('/branch-category', CategoryBranchController::class);
        Route::get('getCategoryList', 'App\Http\Controllers\Branch\Modules\Products\CategoryBranchController@getCategoryList')->name('branch-category.getCategoryList');
        //category

        //product
        Route::resource('/branch-product', ProductBranchController::class);
        Route::get('getProductList', 'App\Http\Controllers\Branch\Modules\Products\ProductBranchController@getProductList')->name('branch-product.getProductList');
        Route::post('productStatusChange', 'App\Http\Controllers\Branch\Modules\Products\ProductBranchController@productStatusChange')->name('branch.product.productStatusChange');
        //product

    });
    //branch-product





});
//branch

//warehouse
Route::group(['prefix' => 'warehouse', 'middleware' => ['auth:sanctum', 'verified', 'CheckWarehouseUser', 'optimizeImages', 'HtmlMinifier'],], function () {

    Route::get('/dashboard', 'App\Http\Controllers\WareHouse\HomeController@index')->name('warehouse.dashboard');
    Route::resource('profile-warehouse', 'App\Http\Controllers\WareHouse\Modules\ProfileController');


    //dashboard controller************
    Route::prefix('warehouse-dashboard')->group(function () {

        Route::resource('/warehouse-resource', \App\Http\Controllers\WareHouse\Modules\DashboardController::class);
        Route::get('getWarehouseTransferInList', '\App\Http\Controllers\WareHouse\Modules\DashboardController@getWarehouseTransferInList')->name('warehouse.warehouseDashboard.getWarehouseTransferInList');
        Route::post('postWarehouseTransferInList', '\App\Http\Controllers\WareHouse\Modules\DashboardController@postWarehouseTransferInList')->name('warehouse.warehouseDashboard.postWarehouseTransferInList');




        Route::get('getBranchTransferReturnList', '\App\Http\Controllers\WareHouse\Modules\DashboardController@getBranchTransferReturnList')->name('warehouse.wareHouseDashboard.getBranchTransferReturnList');
        Route::get('getBranchTransferOutList', '\App\Http\Controllers\WareHouse\Modules\DashboardController@getBranchTransferOutList')->name('warehouse.wareHouseDashboard.getBranchTransferOutList');


        Route::get('getWarehouseStockListSearch', '\App\Http\Controllers\WareHouse\Modules\DashboardController@getWarehouseStockListSearch')->name('warehouse.wareHouseDashboard.getWarehouseStockListSearch');
        Route::get('getWarehouseStockListBySearch', '\App\Http\Controllers\WareHouse\Modules\DashboardController@getWarehouseStockListBySearch')->name('warehouse.wareHouseDashboard.getWarehouseStockListBySearch');
    });
    //dashboard controller************


    Route::prefix('branch-transfer')->group(function () {
        //branch-transfer
        Route::resource('/branch-transfer-warehouse', \App\Http\Controllers\WareHouse\Modules\BranchTransfer\BranchTransferController::class);
        //Route::get('getBranchTransferList', '\App\Http\Controllers\Warehouse\Modules\BranchTransfer\BranchTransferController@getBranchTransferList')->name('warehouse.branchTransfer.getBranchTransferList');
        Route::get('getBranchTransferProductDetail/{product_id}', '\App\Http\Controllers\WareHouse\Modules\BranchTransfer\BranchTransferController@getBranchTransferProductDetail')->name('warehouse.branchTransfer.getBranchTransferProductDetail');
        //branch-transfer



        //In Transit*****************
        Route::resource('/in-transit-warehouse', InTransitWarehouseController::class);
        Route::get('getInTransitWarehouseList', '\App\Http\Controllers\WareHouse\Modules\BranchTransfer\InTransitWarehouseController@getInTransitWarehouseList')->name('warehouse.branch-transfer.getInTransitWarehouseList');
        //In Transit*****************


        //Transfer record warehouse************
        Route::resource('/transfer-record-warehouse', \App\Http\Controllers\WareHouse\Modules\BranchTransfer\TransferRecordWarehouseController::class);
        Route::get('getTransferRecordWarehouseList', '\App\Http\Controllers\WareHouse\Modules\BranchTransfer\TransferRecordWarehouseController@getTransferRecordWarehouseList')->name('warehouse.branchTransfer.getTransferRecordWarehouseList');
        //Transfer record warehouse************


    });


    Route::prefix('purchase')->group(function () {
        //purchase-warehouse
        Route::resource('/purchase-warehouse', \App\Http\Controllers\WareHouse\Modules\Purchase\PurchaseController::class);
        Route::get('getBranchTransferProductDetail/{product_id}', '\App\Http\Controllers\WareHouse\Modules\Purchase\PurchaseController@getBranchTransferProductDetail')->name('warehouse.purchase.getBranchTransferProductDetail');

        Route::resource('/warehouse-last-purchase', \App\Http\Controllers\WareHouse\Modules\Purchase\LastPurchaseController::class);
        Route::get('getWarehouseLastPurchaseList', '\App\Http\Controllers\WareHouse\Modules\Purchase\LastPurchaseController@getWarehouseLastPurchaseList')->name('warehouse.purchase.getWarehouseLastPurchaseList');
        //purchase-warehouse


        //all-purchase
        Route::get('/warehouse-all-purchase', '\App\Http\Controllers\WareHouse\Modules\Purchase\AllPurchaseController@index')->name('warehouse.purchase.getWarehouseAllPurchaseList.index');
        Route::get('getWarehouseAllPurchaseList', '\App\Http\Controllers\WareHouse\Modules\Purchase\AllPurchaseController@getWarehouseAllPurchaseList')->name('warehouse.purchase.getWarehouseAllPurchaseList');
        //all-purchase


    });


    Route::prefix('reports')->group(function () {

        //branch-stock
        Route::resource('/stock-warehouse', \App\Http\Controllers\WareHouse\Modules\Stock\StockController::class);
        Route::get('getBranchStockList', '\App\Http\Controllers\WareHouse\Modules\Stock\StockController@getBranchStockList')->name('warehouse.branchStock.getBranchStockList');
        Route::get('getBranchStockListSearch', '\App\Http\Controllers\WareHouse\Modules\Stock\StockController@getBranchStockListSearch')->name('warehouse.branchStock.getBranchStockListSearch');
        //branch-stock


        //consolidate
        Route::resource('/consolidate-warehouse', \App\Http\Controllers\WareHouse\Modules\Stock\ConsolidatedController::class);
        Route::get('getConsolidateList', '\App\Http\Controllers\WareHouse\Modules\Stock\ConsolidatedController@getConsolidateList')->name('warehouse.consolidate.getConsolidateList');
        Route::get('getConsolidateListSearch', '\App\Http\Controllers\WareHouse\Modules\Stock\ConsolidatedController@getConsolidateListSearch')->name('warehouse.consolidate.getConsolidateListSearch');
        //consolidate

    });



    //branch-product
    Route::prefix('product')->group(function () {

        //brand
        Route::resource('/warehouse-brand', BrandWarehouseController::class);
        Route::get('getWarehouseBrandsList', 'App\Http\Controllers\WareHouse\Modules\Products\BrandWareHouseController@getWarehouseBrandsList')->name('warehouse-brand.getWarehouseBrandsList');
        //brand

        //category
        Route::resource('/warehouse-category', CategoryWarehouseController::class);
        Route::get('getWarehouseCategoryList', 'App\Http\Controllers\WareHouse\Modules\Products\CategoryWareHouseController@getWarehouseCategoryList')->name('warehouse-category.getWarehouseCategoryList');
        //category

        //product
        Route::resource('/warehouse-product', ProductWarehouseController::class);
        Route::get('getWarehouseProductList', 'App\Http\Controllers\WareHouse\Modules\Products\ProductWareHouseController@getWarehouseProductList')->name('warehouse-product.getWarehouseProductList');
        Route::post('productWarehouseStatusChange', 'App\Http\Controllers\Warehouse\Modules\Products\ProductWarehouseController@productStatusChange')->name('warehouse.product.productWarehouseStatusChange');
        //product

    });
    //branch-product








});
//warehouse


//Route::get('update_search_term', function () {
//    ini_set('max_execution_time', 300);
//    $special_characters = [':', '-', '/', '%', '#', '&', '@', '$', '*', ' ', ')', '(', '!', '^', '-', '+', '_', '=', '{', '}', '[', ']', '', '<', '>' . '?'];
//    $products = App\Models\Product::get();
//    foreach ($products as $product) {
//        $product_code_search = str_replace($special_characters, '', $product->product_code);
//        $product_name_search = str_replace($special_characters, '', $product->product_name);
//        App\Models\Product::where('id', $product->id)->update(
//            [
//                'product_code_search' => $product_code_search,
//                'product_name_search' => $product_name_search,
//            ]
//        );
//    }
//    return redirect()->back()->with('success', 'success');
//});
