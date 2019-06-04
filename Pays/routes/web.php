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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/sss', function () {
    echo $_GET['echostr'];die;
});

// Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');

// Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');


// Route::any('/', 'IndexController@index');//首页
// Route::any('/prolist', 'IndexController@prolist');//全部商品页面
// Route::any('/proinfo', 'IndexController@proinfo');//商品详情页
// Route::prefix('/')->middleware('login')->group(function(){
// 	Route::get('/car', 'IndexController@car');//购物车
// 	Route::any('/CountPrice', 'IndexController@CountPrice');//计算小计
// 	Route::any('/checkCounts', 'IndexController@checkCounts');//计算总价格
// 	Route::any('/OneBuy', 'IndexController@OneBuy');//加入购物车
// 	Route::get('user', 'IndexController@user');//个人中心
// 	Route::any('/shoucang', 'IndexController@shoucang');//收藏列表
// 	Route::any('/shoucangOn', 'IndexController@shoucangOn');//收藏列表
// 	Route::any('/pay', 'IndexController@pay');//点击结算之后的显示页面
// 	Route::any('/address', 'IndexController@address');//收货地址现实页面
// 	Route::any('/addressadd', 'IndexController@addressadd');//收货地址添加
// 	Route::any('/AddProvince', 'IndexController@AddProvince');//三级联动
// 	Route::any('/doaddadd', 'IndexController@doaddadd');//地址添加
// 	Route::any('/upadd', 'IndexController@upadd');//修改地址
// 	Route::any('/doupadd', 'IndexController@doupadd');//修改地址处理页面
// 	Route::get('/success', 'IndexController@success');//
// 	Route::any('/alipay/{order_id}', 'IndexController@alipay');//提交订单处理页面
// 	Route::any('/isno', 'IndexController@isno');//支付失败页面
// 	Route::any('/order', 'IndexController@order');//订单显示页面
// 	Route::any('/order_div', 'IndexController@order_div');//订单显示条件页面
// 	Route::any('/history', 'IndexController@history');//浏览记录
// 	Route::any('/historys', 'IndexController@historys');//浏览记录显示页面
// 	Route::any('/HistoryIn', 'IndexController@HistoryIn');//浏览记录显示页面
// 	Route::any('/ispay', 'IndexController@ispay');//点击结算判断用户登陆
// 	Route::any('/issuccess', 'IndexController@issuccess');//提交订单处理页面
// 	Route::any('/is_order', 'IndexController@is_order');//提交订单取消页面
// });

// Route::any('/Favorites', 'IndexController@Favorites');//加入收藏
// Route::get('/login', 'LoginController@login');//登陆
// Route::get('/reg', 'LoginController@reg');//注册
// Route::any('/email', 'LoginController@email');//email邮箱发送
// Route::any('/checkEmail', 'LoginController@checkEmail');//邮箱唯一性
// Route::any('/isreg', 'LoginController@isreg');//注册入库
// Route::any('/islogin', 'LoginController@islogin');//登陆验证
// Route::any('/outLogin', 'LoginController@outLogin');//退出登陆

// Route::any('/show/{id}', 'IndexController@show');//缓存

// Route::any('/return_url','IndexController@return_url');//支付宝同步
// Route::any('/notify_url','IndexController@notify_url');//支付宝异步



// Route::any('/weeks/index','WeeksController@index');//周考首页,登陆
// Route::any('/weeks/regis','WeeksController@regis');//注册
// Route::any('/weeks/reg','WeeksController@reg');//注册处理页面
// Route::any('/weeks/checkemail','WeeksController@checkemail');//发送邮箱验证邮箱唯一性
// Route::any('/weeks/email','WeeksController@email');//发送邮箱
// Route::any('/weeks/login','WeeksController@login');//登陆验证
// Route::any('/weeks/user','WeeksController@user');//登陆之后的首页


Route::get('/Wechat','WechatController@index');//微信
// Route::any('/weChat','IndexController@weChat');//微信-7



