  <p class="online">
       @extends('layouts.shop')
        @section('title','微商城首页')
        @section('content')
      <body>
        <div class="maincont">
         <header>
          <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
          <div class="head-mid">
           <form action="" method="get" class="prosearch">
              <input type="text" name="goods_name" value="@if(empty($goods_name))@else {{$goods_name}} @endif"/>
           </form>
          </div>
         </header>
         <ul class="pro-select">
          <li class="pro-selCur"><a href="javascript:;" class="news">新品</a></li>
          <li><a href="javascript:;" class="number">销量</a></li>
          <li><a href="javascript:;" class="price">价格</a></li>
         </ul><!--pro-select/-->
         <div class="prolist">
         @foreach($data as $key=>$val)
          <dl>
           <dt><a href="proinfo?goods_id={{$val->goods_id}}"><img src="{{asset('shop/uploads').'/'.$val->goods_img}}"
            width="100" height="100" /></a></dt>
           <dd>
            <h3><a href="proinfo?goods_id={{$val->goods_id}}">{{$val->goods_name}}</a></h3>
            <div class="prolist-price"><strong>¥{{$val->shop_price}}</strong> <span>¥{{$val->shop_price*2}}</span></div>
            <div class="prolist-yishou"><span>5.0折</span> <em>已售:0</em></div>
           </dd>
           <div class="clearfix"></div>
          </dl>
          @endforeach
      @include('public.footer')
        </div>
      <script>
         $(function(){
          var goods_name=$('input[name=goods_name]').val();
          if(goods_name==''){
            $('.number').click(function(){
                $(this).parent('li').prev().removeClass();
                $(this).parent('li').next().removeClass();
                $(this).parent('li').addClass('pro-selCur');
                var number='number';
                $.ajaxSetup({
                    headers: {
                      'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                    }
                  });
                $.post(
                  '/prolist', 
                  {prolist:number}, 
                  function(res) {
                    $('.prolist').html(res);
                });
              });
            $('.price').click(function(){
                $(this).parent('li').prev().removeClass();
                $(this).parent('li').prev().prev().removeClass();
                $(this).parent('li').addClass('pro-selCur');
                var price='price';
                $.ajaxSetup({
                    headers: {
                      'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                    }
                  });
                $.post(
                  '/prolist', 
                  {prolist:price}, 
                  function(res) {
                    $('.prolist').html(res);
                });
              });
            $('.news').click(function(){
                $(this).parent('li').next().removeClass();
                $(this).parent('li').next().next().removeClass();
                $(this).parent('li').addClass('pro-selCur');
                var news='news';
                $.ajaxSetup({
                    headers: {
                      'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                    }
                  });
                $.post(
                  '/prolist', 
                  {prolist:news}, 
                  function(res) {
                    $('.prolist').html(res);
                });
              });
          }else{
            $('.number').click(function(){
                $(this).parent('li').prev().removeClass();
                $(this).parent('li').next().removeClass();
                $(this).parent('li').addClass('pro-selCur');
                var number='number';
                $.ajaxSetup({
                    headers: {
                      'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                    }
                  });
                $.post(
                  '/prolist', 
                  {prolist:number,goods_name:goods_name}, 
                  function(res) {
                    $('.prolist').html(res);
                });
              });
            $('.price').click(function(){
                $(this).parent('li').prev().removeClass();
                $(this).parent('li').prev().prev().removeClass();
                $(this).parent('li').addClass('pro-selCur');
                var price='price';
                $.ajaxSetup({
                    headers: {
                      'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                    }
                  });
                $.post(
                  '/prolist', 
                  {prolist:price,goods_name:goods_name}, 
                  function(res) {
                    $('.prolist').html(res);
                });
              });
            $('.news').click(function(){
                $(this).parent('li').next().removeClass();
                $(this).parent('li').next().next().removeClass();
                $(this).parent('li').addClass('pro-selCur');
                var news='news';
                $.ajaxSetup({
                    headers: {
                      'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                    }
                  });
                $.post(
                  '/prolist', 
                  {prolist:news,goods_name:goods_name}, 
                  function(res) {
                    $('.prolist').html(res);
                });
              });
          }
            
            $('input[name=goods_name]').blur(function(){
            $('.prosearch').submit();
          });
         }); 
          
         </script>
         @endsection
   </p>
