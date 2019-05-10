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

/*Route::get('/{$name}', function ( $name ) {
    return view('welcome');
});*/


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

    
/*----------------------------------------------------------------
 | 系統管理專用路由
 |----------------------------------------------------------------
 | 只有系統管理人員才可以使用的路由群組
 |
 */
Route::group(['middleware' => ['auth','role:Admin'] ], function () {
    
    // 權限管理
    Route::get('/permissions','PermissionsController@index')->name('permissions');
    Route::get('/permissionsNew','PermissionsController@new')->name('permissionsNew');
    Route::post('/permissionsNewDo','PermissionsController@newDo')->name('permissionsNewDo');
    Route::get('/permissionsEdit/{id?}','PermissionsController@edit')->name('permissionsEdit');
    Route::post('/permissionsEditDo','PermissionsController@editDo')->name('permissionsEditDo');
    Route::post('/permissionsDeleteDo','PermissionsController@DeleteDo')->name('permissionsDeleteDo');
    

    // 身分管理
    Route::get('/role','RoleController@index');
    Route::get('/roleNew','RoleController@new');
    Route::post('/roleNewDo','RoleController@newDo');
    Route::get('/roleEdit/{id?}','RoleController@edit');
    Route::post('/roleEditDo','RoleController@editDo');    
    Route::post('/roleDeleteDo','RoleController@DeleteDo');

    // 會員管理
    Route::get('/account','AccountController@index');
    Route::get('/accountNew','AccountController@new');
    Route::post('/accountNewDo','AccountController@newDo');
    Route::get('/accountEdit/{id?}','AccountController@edit');
    Route::post('/accountEditDo','AccountController@editDo');      
    Route::post('/accountDeleteDo','AccountController@DeleteDo');

    // 網站管理 ( 先進行商品)
    
    // 總站商品類別管理
    Route::get('/category','CategoryController@index');
    Route::get('/categoryNew','CategoryController@new');
    Route::post('/categoryNewDo','CategoryController@newDo');
    Route::get('/categoryEdit/{id?}','CategoryController@edit');
    Route::post('/categoryEditDo','CategoryController@editDo');       
    Route::post('/categoryDeleteDo','CategoryController@DeleteDo');
    
    // 總站商品管理
    Route::get('/goods','GoodsController@index');
    Route::get('/goodsNew','GoodsController@new');
    Route::post('/goodsNewDo','GoodsController@newDo');
    Route::get('/goodsEdit/{id?}','GoodsController@edit');
    Route::post('/goodsEditDo','GoodsController@editDo');
    Route::post('/goodsDeleteDo','GoodsController@DeleteDo');        
    Route::post('/goodsQuery','GoodsController@query'); 
    Route::post('/goodsAjaxPic','GoodsController@ajaxPic');
    Route::post('/goodsAjaxPicDelete','GoodsController@ajaxPicDelete');
    Route::post('/goodsAjaxPicSort','GoodsController@ajaxPicSort');
    
    // 網站設置
    Route::get('/set','SetController@set');
    Route::post('/setDo','SetController@setDo');
    Route::get('/articleList','SetController@articleList');
    Route::get('/articleNew','SetController@articleNew');
    Route::post('/articleNewDo','SetController@articleNewDo');
    Route::get('/articleEdit/{id}','SetController@articleEdit');
    Route::post('/articleEditDo','SetController@articleEditDo');
    Route::post('/setDelete','SetController@setDelete');

    Route::get('/announcementList','SetController@announcementList');
    Route::get('/announcementNew','SetController@announcementNew');
    Route::post('/announcementNewDo','SetController@announcementNewDo');
    Route::get('/announcementEdit/{id}','SetController@announcementEdit');
    Route::post('/announcementEditDo','SetController@announcementEditDo');
    Route::post('/announcementDelete','SetController@announcementDelete');
    
    
    


});


/*----------------------------------------------------------------
 | 系統管理 & 經銷 群組
 |----------------------------------------------------------------
 | 系統管理以及經銷管理共用的路由群組
 |
 */
Route::group(['middleware' => ['auth','role:Admin|Dealer'] ], function () {
    
    // 訂單管理
    Route::get('/order','OrderController@index');
    Route::get('/orderNew','OrderController@new');
    Route::get('/orderEdit/{id?}','OrderController@edit');
    //Route::post('/goodsEditDo','GoodsController@editDo');
    Route::get('/orderEditBasic/{type}/{id?}','OrderController@editBasic');
    Route::post('/orderEditBasicDo/','OrderController@editBasicDo');
    Route::get('/orderInfo/{id?}','OrderController@info');
    Route::post('/orderStatus','OrderController@updateStatus');
    Route::post('/orderDeleteDo','OrderController@deleteDo');
    
    Route::post('/orderQuery','OrderController@query');
    Route::post('/orderSearchGoods','OrderController@searchGoods');
    Route::post('/orderGetGoods','OrderController@getGoods');
    Route::post('/orderAddGoods','OrderController@addGoods');
    Route::post('/orderEditGoods','OrderController@editGoods');
    Route::post('/orderDeleteGoods','OrderController@deleteGoods');
    

    // 進貨單管理
    Route::get('/purchaseEstimate','PurchaseController@estimate');
    Route::post('/purchaseEstimateDo','PurchaseController@estimateDo');  // 棄用
    Route::post('/purchaseOrder','PurchaseController@newPurchaseOrder'); // 棄用
    Route::get('/purchaseList','PurchaseController@index');
    Route::post('/purchaseQuery','PurchaseController@query');
    Route::get('/purchaseInfo/{id}','PurchaseController@info');
    Route::post('/purchaseAjaxEstimateDo','PurchaseController@ajaxEstimateDo');
    Route::post('/purchaseAjaxOrder','PurchaseController@ajaxNewPurchaseOrder');
    Route::post('/purchaseAjaxAddGoods','PurchaseController@ajaxAddPurchaseGoods');
    Route::post('/puchaseStatus','PurchaseController@updateStatus');
    Route::post('/purchaseDeleteDo','PurchaseController@purchaseDelete');
    Route::get('/addStockException/{id}','PurchaseController@addStockException'); // 特殊狀況入庫 
    Route::post('ajaxAddStockGoods','PurchaseController@ajaxAddStockGoods'); // ajax撈出要加入庫存之商品
    Route::post('addToStock','PurchaseController@addStockExceptionDo'); // 特殊狀況入庫實作
    

    // 報表管理
    Route::get('reportOrder','ReportController@order');
    Route::get('reportPurchase','ReportController@purchase');
    Route::get('reportGoodsSale','ReportController@goodsSale');


    // 經銷商網站管理
    Route::get('/dealer','DealerController@index');
    Route::post('/dealerQuery','DealerController@query');
    Route::get('/dealerNew','DealerController@dealerNew');      // 新增經銷商介面
    Route::post('/dealerNewDo','DealerController@dealerNewDo'); // 新增經銷商實作
    Route::get('/dealerEdit/{id}','DealerController@dealerEdit');// 編輯經銷商頁面
    Route::post('/dealerEditDo','DealerController@dealerEditDo'); // 編輯經銷商實作
    Route::post('/dealerDeleteDo','DealerController@dealerDeleteDo'); // 刪除經銷商

    // 經銷商設定商品價格
    Route::get('/price' , 'PriceController@index');// 商品價格列表
    Route::post('/priceQuery' , 'PriceController@query');// 商品價格查詢
    Route::get('/priceEdit/{id}' , 'PriceController@priceEdit');// 編修價格
    Route::post('/priceEditDo' ,'PriceController@priceEditDo');
});    


/*----------------------------------------------------------------
 | 購物車相關
 |----------------------------------------------------------------
 |
 */
Route::get('/{name}','CartController@index')->middleware(['cart']);
Route::get('/{name}/goods/{goodsId}','CartController@viewGoods')->middleware(['cart']);
Route::post('/{name}/addToCart/','CartController@addToCart')->middleware(['cart']);
Route::post('/{name}/deleteItem/','CartController@deleteItem')->middleware(['cart']);
Route::get('/{name}/checkout/','CartController@checkout')->middleware(['cart']);