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

Route::get('/', 'IndexController@index')->name('index');
Route::get('/product_category', 'CategoryProductController@index')->name('product_category');
Route::get('/single_product', 'ProductController@index')->name('single_product');

//DO Register
Route::get('/deliveryorder', 'DeliveryOrderController@index')->name('delivery_order');
Route::post('/deliveryorder', 'DeliveryOrderController@store')->name('store_delivery_order');
Route::get('/register-success', 'DeliveryOrderController@successorder')->name('successorder');
Route::get('/fetchCso', 'DeliveryOrderController@fetchCso')->name('fetchCso');
Route::get('/templistregwaki1995', 'DeliveryOrderController@listDeliveryOrder')->name('listDeliveryOrder');

//Order 
Route::get('/order', 'OrderController@index')->name('add_order');
Route::post('/order', 'OrderController@store')->name('store_order');
Route::get('/order-success', 'OrderController@successorder')->name('order_success');
Route::get('/templistorderwaki1995', 'OrderController@listOrder')->name('list_order');

Auth::routes(['verify' => true]);
Route::group(['prefix' => 'cms-admin'], function () {
	Route::get('/', function () {
		if(Auth::guard()->check()){
			return redirect()->route('dashboard');
		}
		else {
			return redirect()->route('login');
		}
	});

	//show login form
	Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
	//login cek nya
    Route::post('/login', 'Auth\LoginController@login')->name('admin_login');
    //logout usernya
    Route::get('/logout', 'Auth\LoginController@logoutUser')->name('admin_logout');
    //dashboard
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
});
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
