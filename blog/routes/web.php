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
Route::any('/index','TestController@index');

Route::any('/LuckyDraw','WechatController@LuckyDraw');//参加抽奖
Route::any('/Draw','WechatController@Draw');//参加抽奖
Route::any('/MyCoupon','WechatController@MyCoupon');//我的优惠券列表



Route::any('/Login','WechatController@Login');//登陆
Route::any('/isLogin','WechatController@isLogin');//登陆
Route::any('Wechat','WechatController@index');//微信公众号
Route::any('IsUpload','WechatController@IsUpload');//上传
Route::any('Upload','WechatController@upload');//图片

//微信图片
Route::any('/Love','LoveController@index');//微信菜单
Route::any('WechatImg','WechatController@WechatImg');//上传微信图片
Route::any('dowechatimg','WechatController@dowechatimg');//上传微信图片

Route::any('/binding','WechatController@binding');//绑定微信公众号
Route::any('/doBinding','WechatController@doBinding');//处理微信用户个人信息
Route::any('/test','WechatController@test');//测试
Route::any('/code','WechatController@code');//登陆获取验证码


Route::any('/automatic','WechatController@automatic');//扫码登陆


Route::any('/okok','WechatController@okok');//添加测题处理页面



Route::any('/linddec','WechatController@linddec');//定时任务测试

Route::any('/TimingMass','WechatController@TimingMass');//定时每天九点

Route::prefix('/')->middleware('login')->group(function(){
    Route::any('Isindex','WechatController@Isindex');//首页

    //微信图片
    Route::any('/imgdel','WechatController@imgdel');//微信图片删除
    Route::any('/upd','WechatController@upd');//微信图片修改
    Route::any('/doupd','WechatController@doupd');//微信图片修改
    Route::any('/RandImg','WechatController@RandImg');//微信图片修改
    //公众号菜单处理
    Route::any('/Menu','WechatController@Menu');//公众号菜单列表
    //上传菜单
    Route::any('/InMenu','WechatController@InMenu');//公众号添加菜单页面
    Route::any('/doInMenu','WechatController@doInMenu');//公众号添加菜单处理
    //修改菜单
    Route::any('/UpMenu','WechatController@UpMenu');//公众号修改菜单页面
    Route::any('/DoUpMenu','WechatController@DoUpMenu');//公众号修改菜单处理
    Route::any('/DelMenu','WechatController@DelMenu');//一件同步
    Route::any('/synchronous','WechatController@synchronous');//一件同步


    //微信支付
    Route::any('/Pays','WechatController@Pays');//微信支付
    Route::any('/success','WechatController@success');//微信支付
    
    //素材
    Route::any('/files','WechatController@files');//公众号素材显示
    Route::any('/InFiles','WechatController@InFiles');//公众号素材上传显示
    Route::any('/DoInFiles','WechatController@DoInFiles');//公众号素材上传处理
    Route::any('/DelMenu','WechatController@DelMenu');//一件同步
    Route::any('/isss','WechatController@isss');//测试清楚接口

    Route::any('/AMass','WechatController@AMass');//群发显示页面
    Route::any('/IsAMass','WechatController@IsAMass');//群发
    Route::any('/PartAMass','WechatController@PartAMass');//部分发送

    Route::any('/IsQrCode','WechatController@IsQrCode');//二维码显示页面
    Route::any('/addOrCode','WechatController@addOrCode');//二维码添加页面
    Route::any('/RateQrCode','WechatController@RateQrCode');//生成二维码
    Route::any('/OrCodeDel','WechatController@OrCodeDel');//删除二维码
    Route::any('/OrCodeUpd','WechatController@OrCodeUpd');//修改二维码显示页面
    Route::any('/DoQrCodeUpd','WechatController@DoQrCodeUpd');//修改二维码处理页面
    Route::any('/statistical','WechatController@statistical');//统计表格
    
    //首页天气
    Route::any('/weather','WechatController@weather');//统计表格
    Route::any('/AjaxWeather','WechatController@AjaxWeather');//AJAX天气

    //粉丝标签
    Route::any('/fans','WechatController@fans');//粉丝
    Route::any('/TubeLabel','WechatController@TubeLabel');//标签管理
    Route::any('/addLabel','WechatController@addLabel');//添加标签
    Route::any('/doAddLabel','WechatController@doAddLabel');//添加标签处理页面
    Route::any('/updLabel','WechatController@updLabel');//编辑标签
    Route::any('/DoUpdLabel','WechatController@DoUpdLabel');//编辑标签
    Route::any('/delLabel','WechatController@delLabel');//删除标签
    Route::any('/addFans','WechatController@addFans');//标签添加粉丝
    Route::any('/DoAddFans','WechatController@DoAddFans');//标签添加粉丝处理页面
    
    Route::any('/QrCodeLanding','WechatController@QrCodeLanding');//标签添加粉丝处理页面

    Route::any('/SignView','WechatController@SignView');//签到显示个人信息
    Route::any('/Sign','WechatController@Sign');//签到处理页面
    Route::any('/role','WechatController@role');//角色展示
    Route::any('/addRole','WechatController@addRole');//角色添加显示页面
    Route::any('/doAddRole','WechatController@doAddRole');//角色添加处理页面
    Route::any('/PerMiss','WechatController@PerMiss');//添加权限显示页面
    Route::any('/doPerMiss','WechatController@doPerMiss');//添加权限处理页面
    Route::any('/delRole','WechatController@delRole');//删除权限处理页面
    Route::any('/updRole','WechatController@updRole');//角色修改显示页面
    Route::any('/doUpdRole','WechatController@doUpdRole');//角色修改处理页面


    Route::any('/JsSdk','WechatController@JsSdk');//JsSdk分享


    Route::any('/TheTest','WechatController@TheTest');//测题显示页面
    Route::any('/addTheTest','WechatController@addTheTest');//添加测题显示页面
    Route::any('/doAddTheTest','WechatController@doAddTheTest');//添加测题处理页面
    

    //优惠券
    Route::any('/coupons','WechatController@coupons');//优惠券添加
    Route::any('/addCoupons','WechatController@addCoupons');//优惠券添加处理
   
});
