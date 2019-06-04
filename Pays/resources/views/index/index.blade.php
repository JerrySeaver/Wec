   @extends('layouts.shop')
    @section('title','微商城首页')
    @section('content')
     <div class="head-top">
      <img src="{{asset('shop/images/head.jpg')}}" />
      <dl>
       <dt><a href="user"><img src="{{asset('shop/images/touxiang.jpg')}}" /></a></dt>
       <dd>
        <h1 class="username">三级分销终身荣誉会员</h1>
        <ul>
         <li><a href="prolist"><strong>{{$count}}</strong><p>全部商品</p></a></li>
         <li><a href="javascript:;"><span class="glyphicon glyphicon-star-empty"></span><p>收藏本店</p></a></li>
         <li style="background:none;"><a href="javascript:;"><span class="glyphicon glyphicon-picture"></span><p>二维码</p></a></li>
         <div class="clearfix"></div>
        </ul>
       </dd>
       <div class="clearfix"></div>
      </dl>
     </div><!--head-top/-->
     <form action="" method="get" class="search">
      <input type="text" name='goods_name' class="seaText fl" />
      <input type="submit" value="搜索" class="seaSub fr" />
     </form><!--search/-->
     @if($loginIs)
     <ul class="reg-login-click">
      <li><a href="login">登录</a></li>
        <li><a href="reg" class="rlbg">注册</a></li>
        <div class="clearfix"></div>
     </ul><!--reg-login-click/-->
     @else
      <ul class="reg-login-click">
        
      </ul><!--reg-login-click/-->
      @endif
     </ul><!--pronav/-->
     <div class="index-pro1">
     @foreach($data as $key=>$val)
      <div class="index-pro1-list">
       <dl>
        <dt><a href="proinfo?goods_id={{$val->goods_id}}"><img src="{{asset('shop/uploads').'/'.$val->goods_img}}" /></a></dt>
        <dd class="ip-text"><a href="proinfo?goods_id={{$val->goods_id}}">{{$val->goods_name}}</a><span>已售:0</span></dd>
        <dd class="ip-price"><strong>¥{{$val->shop_price}}</strong> <span>¥{{$val->market_price}}</span></dd>
       </dl>
      </div>
      @endforeach
      <div class="clearfix"></div>
     </div><!--index-pro1/-->
     <div class="prolist">
     @foreach($res as $key=>$val)
      <dl>
       <dt><a href="proinfo?goods_id={{$val->goods_id}}"><img src="http://uploads.pays.com/{{$val->goods_img}}" width="100" height="100" /></a></dt>
       <dd>
        <h3><a href="proinfo?goods_id={{$val->goods_id}}">{{$val->goods_name}}</a></h3>
        <div class="prolist-price"><strong>¥{{$val->shop_price}}</strong> <span>{{$val->shop_price*2}}</span></div>
        <div class="prolist-yishou"><span>5.0折</span> <em>已售:0</em></div>
       </dd>
       <div class="clearfix"></div>
      </dl>
      @endforeach
     </div>
     @include('public.footer')
     @endsection