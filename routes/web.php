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

//首页路由
Route::get('/', ['as' => '/', 'uses' => 'IndexController@lists']);

//材料管理路由
Route::group(['prefix' => 'materials'], function () {
    Route::get('lists', ['as' => 'materials.lists', 'uses' => 'MaterialsController@lists']);
    Route::get('logs', ['as' => 'materials.logs', 'uses' => 'MaterialsController@logs']);
    Route::get('add', ['as' => 'materials.add', 'uses' => 'MaterialsController@add']);
    Route::post('getLists', ['as' => 'materials.getLists', 'uses' => 'MaterialsController@getLists']);
    Route::post('getLogs', ['as' => 'materials.getLogs', 'uses' => 'MaterialsController@getLogs']);
    Route::post('increase', ['as' => 'materials.increase', 'uses' => 'MaterialsController@increase']);
    Route::post('save', ['as' => 'materials.save', 'uses' => 'MaterialsController@save']);
    Route::post('del', ['as' => 'materials.del', 'uses' => 'MaterialsController@del']);
});

//产品管理路由
Route::group(['prefix' => 'goods'], function () {
    Route::get('lists', ['as' => 'goods.lists', 'uses' => 'GoodsController@lists']);
    Route::get('add', ['as' => 'goods.add', 'uses' => 'GoodsController@add']);
    Route::get('logs', ['as' => 'goods.logs', 'uses' => 'GoodsController@logs']);
    Route::post('getLists', ['as' => 'goods.getLists', 'uses' => 'GoodsController@getLists']);
    Route::post('getLogs', ['as' => 'goods.getLogs', 'uses' => 'GoodsController@getLogs']);
    Route::post('chgBtn', ['as' => 'goods.chgBtn', 'uses' => 'GoodsController@chgBtn']);
    Route::post('save', ['as' => 'goods.save', 'uses' => 'GoodsController@save']);
    Route::post('del', ['as' => 'goods.del', 'uses' => 'GoodsController@del']);
    Route::post('increase', ['as' => 'goods.increase', 'uses' => 'GoodsController@increase']);
});

//订单管理路由
Route::group(['prefix' => 'orders'], function () {
    Route::get('lists', ['as' => 'orders.lists', 'uses' => 'OrdersController@lists']);
    Route::post('getLists', ['as' => 'orders.getLists', 'uses' => 'OrdersController@getLists']);
    Route::get('add', ['as' => 'orders.add', 'uses' => 'OrdersController@add']);
    Route::post('save', ['as' => 'orders.save', 'uses' => 'OrdersController@save']);
    Route::post('chgBtn', ['as' => 'orders.chgBtn', 'uses' => 'OrdersController@chgBtn']);
    Route::get('getDetails', ['as' => 'orders.getDetails', 'uses' => 'OrdersController@getDetails']);
    Route::post('del', ['as' => 'orders.del', 'uses' => 'OrdersController@del']);
    Route::post('test', ['as' => 'orders.test', 'uses' => 'OrdersController@test']);
});
