<?php

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

Route::get('/', function(){
    if(auth()->check()) return redirect()->to('/home');
    return redirect()->route('auth.login');
});

Route::get('/home', function () {
    return view('change_registers.index');
});
Route::get('auth/login','Auth\LoginController@showLoginForm')->name('auth.login');
Route::post('/auth/login','Auth\LoginController@login');
Route::middleware('auth')->group(function(){
    Route::get('auth/logout','Auth\LoginController@logout')->name('auth.logout');
    Route::get('/home', function () {
        return view('change_registers.index');
    });
    //local transfer
    Route::get('change-local-transfers','LocalTransferController@index')->name('local-transfers.index');
    Route::post('ajax/nodes','LocalTransferController@getNodes')->name('local-transfers.nodes');
    Route::post('ajax/get-assets-by-node','LocalTransferController@getAssetsAfterNodeSelected')->name('local-transfers.assets');
    Route::post('ajax/node-to-node','LocalTransferController@nodeToNode')->name('local-transfers.assets.submit');

    Route::get('transfer-node-to-manager','LocalTransferController@nodeToManager')->name('local-transfers.node-to-manager');
    Route::post('ajax/node-to-manager/get-node','LocalTransferController@getNodeAfterManagerSelected')->name('local-transfers.node-to-manager.get-node');
    Route::post('ajax/node-to-manager/submit','LocalTransferController@nodeToManagerSubmit')->name('local-transfers.node-to-manager.submit');


    Route::get('transfer-warehouse-to-manager','LocalTransferController@warehouseToManager')->name('local-transfers.warehouse-to-manager');
    Route::get('ajax/transfer-warehouse-to-manager/get-manager','LocalTransferController@getCurrentUser')->name('local-transfers.warehouse-to-manager.get-manager');
    Route::post('ajax/transfer-warehouse-to-manager/get-warehouse','LocalTransferController@getWarehouse')->name('local-transfers.warehouse-to-manager.get-warehouse');
    Route::post('ajax/transfer-warehouse-to-manager/get-assets-after-warehouse','LocalTransferController@getAssetAfterWarehouseSelected')->name('local-transfers.warehouse-to-manager.get-assets');
    Route::post('ajax/transfer-warehouse-to-manager/submit','LocalTransferController@warehouseToManagerSubmit')->name('local-transfers.warehouse-to-manager.submit');


    //transfer warehouse to warehouse
    Route::prefix('transfer-warehouse-to-warehouse')->group(function (){
        Route::get('','LocalTransferController@warehouseToWarehouse')->name('local-transfers.warehouse-to-warehouse');
        Route::post('ajax/get-warehouse','LocalTransferController@getWarehouse');
        Route::post('ajax/get-warehouse-after-warehouse-selected','LocalTransferController@getWarehouseAfterWarehouseSelected');
        Route::post('ajax/get-asset-after-warehouse-selected','LocalTransferController@getAssetByWarehouse');
        Route::post('ajax/submit','LocalTransferController@warehouseToWarehouseSubmit');
    });

    //transfer warehouse to node
    Route::prefix('warehouse-to-node')->group(function(){
        Route::get('create','TransferController@showFormWarehouseToNode')->name('warehouse-to-node.create');
        Route::post('nodes','TransferController@getNodes')->name('warehouse-to-node.nodes');
        Route::post('warehouses','TransferController@getWarehouses')->name('warehouse-to-node.warehouses');
        Route::post('assets','TransferController@getAssets')->name('warehouse-to-node.assets');
        Route::post('submit','TransferController@transferWarehouseToNode')->name('warehouse-to-node.submit');
    });
    //transfer warehouse to node

    /* transfer node to warehouse */
    Route::prefix('node-to-warehouse')->group(function(){
        Route::get('create','TransferController@showFormNodeToWarehouse')->name('node-to-warehouse.create');
        Route::post('nodes','TransferController@getNodes')->name('node-to-warehouse.nodes');
        Route::post('warehouses','TransferController@getWarehouses')->name('node-to-warehouse.warehouses');
        Route::post('assets','TransferController@getAssets')->name('node-to-warehouse.assets');
        Route::post('submit','TransferController@transferNodeToWarehouse')->name('node-to-warehouse.submit');
    });
    /* transfer node to warehouse */

    //transfer manager to manager
    Route::prefix('manager-transfers')->group(function(){
        Route::get('create', 'LocalTransferController@showFormManagerTransfer')->name('local-manager-transfers.create');
        Route::post('managers', 'LocalTransferController@getManagers')->name('local-manager-transfers.managers');
        Route::post('assets', 'LocalTransferController@getAssets')->name('local-manager-transfers.assets');
        Route::post('transfer', 'LocalTransferController@managerTransfer')->name('local-manager-transfers.transfer');
        Route::get('assets-temp-transfers','LocalTransferController@showAssetTempTransfers')->name('local-manager-transfers.assets-temp-transfers');
        Route::get('assets-temp','LocalTransferController@assetTempTransfers')->name('local-manager-transfers.assets-temp');
        Route::post('accept-temp-transfer','LocalTransferController@acceptAssetTempTransfer')->name('local-manager-transfers.accept-asset-transfer');
        Route::post('cancel-temp-transfer','LocalTransferController@cancelAssetTempTransfer')->name('local-manager-transfers.cancel-asset-transfer');
    });
    //transfer manager to manager


    //transfer out of station

    Route::prefix('transfer-out-of-station')->group(function (){
        Route::get('/','TransferController@transferOutOfStation')->name('transfer-out-of-station.index');
        Route::post('/ajax/get-asset','TransferController@selectAsset');
        Route::post('/ajax/submit','TransferController@transferOutOfStationSubmit');
    });

    // transfer repairs
    Route::prefix('warranty-repairs')->group(function(){
        Route::get('create','TransferController@showFormWarrantyRepair')->name('warranty-repairs.create');
        Route::post('assets','TransferController@getAssetTransferWarrantyRepair')->name('warranty-repairs.assets');
        Route::post('submit','TransferController@submitWarrantyRepair')->name('warranty-repairs.submit');
    });


    Route::get('users', 'ChangeRegisterController@users')->name('cr.users');
    Route::get('change-registers/all','ChangeRegisterController@allChangeRegister')->name('change-registers.all');
    Route::resource('change-registers','ChangeRegisterController')->except(['show']);
});