   @extends('layouts.shop')
    @section('title','微商城个人中心')
    @section('content')
  <body>
    <div class="maincont">
     <div class="userName">
      <dl class="names">
       <dt><img src="{{asset('shop/images/user01.png')}}" /></dt>
       <dd>
        <h3>{{$sanm}}</h3>
       </dd>
       <div class="clearfix"></div>
      </dl>
      <div class="shouyi">
       <dl>
        <dt>我的余额</dt>
        <dd>0.00元</dd>
       </dl>
       <dl>
        <dt>我的积分</dt>
        <dd>0</dd>
       </dl>
       <div class="clearfix"></div>
      </div><!--shouyi/-->
     </div><!--userName/-->
     
     <ul class="userNav">
      <li><span class="glyphicon glyphicon-list-alt"></span><a href="order">我的订单</a></li>
      <div class="height2"></div>
      <div class="state">
         <dl>
          <dt><a href="/order?is_status=1"><img src="{{asset('shop/images/user1.png')}}" /></a></dt>
          <dd><a href="/order?is_status=1">待支付</a></dd>
         </dl>
         <dl>
          <dt><a href="/order?is_status=2"><img src="{{asset('shop/images/user2.png')}}" /></a></dt>
          <dd><a href="/order?is_status=2">代发货</a></dd>
         </dl>
         <dl>
          <dt><a href="/order?is_status=3"><img src="{{asset('shop/images/user3.png')}}" /></a></dt>
          <dd><a href="/order?is_status=3">待收货</a></dd>
         </dl>
         <dl>
          <dt><a href="/order"><img src="{{asset('shop/images/user4.png')}}" /></a></dt>
          <dd><a href="/order">全部订单</a></dd>
         </dl>
         <div class="clearfix"></div>
      </div><!--state/-->
      <li><span class="glyphicon glyphicon-map-marker"></span><a href="/address">收货地址管理</a></li>
      <li><span class="glyphicon glyphicon-star-empty"></span><a href="/shoucang">我的收藏</a></li>
      <li><span class="glyphicon glyphicon-heart"></span><a href="/historys">我的浏览记录</a></li>
	 </ul><!--userNav/-->
     
     <div class="lrSub">
       <a href="javascript:;" class="outLogin">退出登录</a>
     </div>
 @include('public.footer')
 <script>
    $('.outLogin').click(function(){
      $.ajaxSetup({
                headers: {
                  'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                }
              });
        $.post(
          '/outLogin',
          function(res){
            alert('退出成功');
            window.location.href = "/login";
        });
    });
 </script>
     @endsection