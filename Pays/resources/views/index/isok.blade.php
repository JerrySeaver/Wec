   @extends('layouts.shop')
    @section('title','微商城订单')
    @section('content')
  <body>
    <div class="maincont">
     <header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <h1>分销申请</h1>
      </div>
     </header>
     <div class="head-top">
      <img src="{{asset('shop/images/head.jpg')}}" />
     </div><!--head-top/-->
     <div class="fenxiao">
     <h2>支付成功！</h2>
     <p><a href="/Order">查看订单</a></p>
      </div><!--fen-list/-->
     </div><!--fenxiao/-->
    @include('public.footer') 
    @endsection