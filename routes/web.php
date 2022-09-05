<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    } else {
        return view('auth.login');
    }
})->name('front');

Auth::routes();

Route::group(['middleware' => ['auth'], 'namespace' => 'admin'], function () {

    //dashboard route start
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
    Route::get('/seller/dashboard', 'DashboardController@sellerDashboard')->name('seller.dashboard');
    //dashboard route end

    //products route start
    Route::get('product', 'ProductController@index')->name('product');
    Route::get('product/getData', 'ProductController@getData')->name('product.getData');
    Route::get('product/create', 'ProductController@create')->name('product.create');
    Route::post('product/store', 'ProductController@store')->name('product.store');
    Route::get('product/edit/{id}', 'ProductController@edit')->name('product.edit');
    Route::put('product/update/{id}', 'ProductController@update')->name('product.update');
    Route::delete('product/destroy/{id}', 'ProductController@destroy')->name('product.destroy');
    Route::get('product/status_change/{id}', 'ProductController@statusChange')->name('product.status_change');
    //products route end

    //category route start
    Route::get('category', 'CategoryController@index')->name('category');
    Route::get('category/getData', 'CategoryController@getData')->name('category.getData');
    Route::get('category/create', 'CategoryController@create')->name('category.create');
    Route::post('category/store', 'CategoryController@store')->name('category.store');
    Route::get('category/edit/{id}', 'CategoryController@edit')->name('category.edit');
    Route::put('category/update/{id}', 'CategoryController@update')->name('category.update');
    Route::delete('category/destroy/{id}', 'CategoryController@destroy')->name('category.destroy');
    Route::get('category/status_change/{id}', 'CategoryController@statusChange')->name('category.status_change');
    //category route end

    //branch type route start
    Route::get('branch_type', 'BranchTypeController@index')->name('branch_type');
    Route::get('branch_type/getData', 'BranchTypeController@getData')->name('branch_type.getData');
    Route::get('branch_type/create', 'BranchTypeController@create')->name('branch_type.create');
    Route::post('branch_type/store', 'BranchTypeController@store')->name('branch_type.store');
    Route::get('branch_type/edit/{id}', 'BranchTypeController@edit')->name('branch_type.edit');
    Route::put('branch_type/update/{id}', 'BranchTypeController@update')->name('branch_type.update');
    Route::delete('branch_type/destroy/{id}', 'BranchTypeController@destroy')->name('branch_type.destroy');
    Route::get('branch_type/status_change/{id}', 'BranchTypeController@statusChange')->name('branch_type.status_change');
    //branch type route end

    //branch route start
    Route::get('branch', 'BranchController@index')->name('branch');
    Route::get('branch/getData', 'BranchController@getData')->name('branch.getData');
    Route::get('branch/create', 'BranchController@create')->name('branch.create');
    Route::post('branch/store', 'BranchController@store')->name('branch.store');
    Route::get('branch/edit/{id}', 'BranchController@edit')->name('branch.edit');
    Route::put('branch/update/{id}', 'BranchController@update')->name('branch.update');
    Route::delete('branch/destroy/{id}', 'BranchController@destroy')->name('branch.destroy');
    Route::get('branch/approve/{id}', 'BranchController@approve')->name('branch.approve');
    //branch route end

    //user management route start
    Route::get('permission', 'PermissionController@index')->name('permission');
    Route::get('permission/getData', 'PermissionController@getData')->name('permission.getData');
    Route::get('permission/create', 'PermissionController@create')->name('permission.create');
    Route::post('permission/store', 'PermissionController@store')->name('permission.store');
    Route::get('permission/edit/{id}', 'PermissionController@edit')->name('permission.edit');
    Route::put('permission/update/{id}', 'PermissionController@update')->name('permission.update');
    Route::delete('permission/destroy/{id}', 'PermissionController@destroy')->name('permission.destroy');

    Route::get('role', 'RoleController@index')->name('role');
    Route::get('role/getData', 'RoleController@getData')->name('role.getData');
    Route::get('role/create', 'RoleController@create')->name('role.create');
    Route::post('role/store', 'RoleController@store')->name('role.store');
    Route::get('role/edit/{id}', 'RoleController@edit')->name('role.edit');
    Route::put('role/update/{id}', 'RoleController@update')->name('role.update');
    Route::delete('role/destroy/{id}', 'RoleController@destroy')->name('role.destroy');

    Route::get('user', 'UserController@index')->name('user');
    Route::get('user/getData', 'UserController@getData')->name('user.getData');
    Route::get('user/create', 'UserController@create')->name('user.create');
    Route::post('user/store', 'UserController@store')->name('user.store');
    Route::get('user/edit/{id}', 'UserController@edit')->name('user.edit');
    Route::put('user/update/{id}', 'UserController@update')->name('user.update');
    Route::delete('user/destroy/{id}', 'UserController@destroy')->name('user.destroy');
    Route::get('user/status_change/{id}', 'UserController@statusChange')->name('user.status_change');
    //user management route end

    //product attribute route start
    Route::get('attribute', 'AttributeController@index')->name('attribute');
    Route::get('attribute/getData', 'AttributeController@getData')->name('attribute.getData');
    Route::get('attribute/create', 'AttributeController@create')->name('attribute.create');
    Route::post('attribute/store', 'AttributeController@store')->name('attribute.store');
    Route::get('attribute/edit/{id}', 'AttributeController@edit')->name('attribute.edit');
    Route::put('attribute/update/{id}', 'AttributeController@update')->name('attribute.update');
    Route::delete('attribute/destroy/{id}', 'AttributeController@destroy')->name('attribute.destroy');
    //product attribute route end

    //product stock route start
    Route::get('stock', 'ProductStockController@index')->name('stock');
    Route::get('stock/getData', 'ProductStockController@getData')->name('stock.getData');
    Route::post('stock/store', 'ProductStockController@store')->name('stock.store');
    Route::get('stock/edit/{id}', 'ProductStockController@edit')->name('stock.edit');
    Route::put('stock/update/{id}', 'ProductStockController@update')->name('stock.update');
    Route::delete('stock/destroy/{id}', 'ProductStockController@destroy')->name('stock.destroy');
    Route::get('check/stocked', 'ProductStockController@checkStocked')->name('check.stocked');

    //product stock route end

    //product distribution route start
    Route::get('product_distribution', 'ProductDistributionController@index')->name('product_distribution');
    Route::get('product_distribution/getData', 'ProductDistributionController@getData')->name('product_distribution.getData');
    Route::post('product_distribution/store', 'ProductDistributionController@store')->name('product_distribution.store');
    Route::get('product_distribution/edit/{id}', 'ProductDistributionController@edit')->name('product_distribution.edit');
    Route::put('product_distribution/update/{id}', 'ProductDistributionController@update')->name('product_distribution.update');
    Route::delete('product_distribution/destroy/{id}', 'ProductDistributionController@destroy')->name('product_distribution.destroy');
    //get branch type wise branch
    Route::get('branch/type/branches', 'ProductDistributionController@branchTypeBranches')->name('branch.type.branches');
    Route::get('branch/seller', 'ProductDistributionController@branchSeller')->name('branch.seller');
     Route::get('branch/seller/report', 'ProductDistributionController@branchSellerReport')->name('branch.seller.report');
    //get product attribute wise stock
    Route::get('product/attribute/stock', 'ProductDistributionController@productAttributeStock')->name('product.attribute.stock');
    //product distribution route end
    Route::get('get/product/stock', 'ProductDistributionController@getProductStock')->name('get.product.stock');
    Route::get('seller/products', 'ProductDistributionController@sellerProducts')->name('seller.products');
    //product distribution route end

    //seller sale route start
    Route::get('seller_sale_create', 'SellProductController@create')->name('seller.sale.create');
    Route::get('seller/sale/show/{id}', 'SellProductController@show')->name('seller.sale.show');
    Route::get('seller_sale_index', 'SellProductController@index')->name('seller.sale.index');
    Route::get('seller_sale_getData', 'SellProductController@getData')->name('seller.sale.getData');
    Route::post('product/sell/calculation/store', 'SellProductController@productSellStore')->name('product.sell.calculation.store');
    //seller sale route end

    Route::get('admin/sales/index', 'SellProductController@adminSaleIndex')->name('admin.sales.index');
    Route::get('admin/sales', 'SellProductController@adminSaleGetData')->name('admin.sales');
    Route::get('admin/sale/amount/update', 'SellProductController@adminSaleAmountUpdate')->name('admin.sale.amount.update');
    Route::get('admin/sale/approve/{id}', 'SellProductController@adminSaleSupperAdminApprove')->name('admin.sale.approve');


    //print route start
    // sale print
    Route::get('sale/print', 'InvoiceController@salePrint')->name('sale.print');

    //report route here

    Route::get('sale/report', 'InvoiceController@report')->name('report');
    Route::get('/report', 'InvoiceController@allReport')->name('all.report');


});
