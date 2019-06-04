   @extends('layouts.shop')
    @section('title','微商城订单')
    @section('content')
  <body>
    <div class="maincont">
     <header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <h1>我的订单</h1>
      </div>
     </header>
     <div class="head-top">
      <img src="{{asset('shop/images/head.jpg')}}" />
     </div><!--head-top/-->
     
     <div class="zhaieq oredereq">
      <a href="javascript:;" id="1" class="zhaiCur">待付款</a>
      <a href="javascript:;" id="2">待发货</a>
      <a href="javascript:;" id="3">待收货</a>
      <a href="javascript:;" id="4" style="background:none;">已完成</a>
      <div class="clearfix"></div>
     </div><!--oredereq/-->
     <div class="assorder">
       @foreach($res as $k=>$v)
       <div class="dingdanlist" >
        <table>
         <tr>
          <td colspan="2" width="65%">订单号：<strong>{{$v['order_number']}}</strong></td>
          <td width="35%" align="right"><div class="qingqu"><a href="javascript:;" order_id="{{$v['order_id']}}" id="sss" class="orange">订单取消</a></div></td>
         </tr>
             @foreach($ress as $key=>$val)
             <tr onClick="window.location.href='proinfo.html'">
              <td class="dingimg" width="15%"><img src="{{asset('shop/uploads').'/'.$val["goods_img"]}}"/></td>
              <td width="50%">
               <h3>{{$val['goods_name']}}</h3>
               <a>X{{$val['buy_number']}}</a>
              </td>
              <td align="right"><img src="{{asset('shop/images/jian-new.png')}}" /></td>
             </tr>
             <tr>
             @endforeach
          <th colspan="3"><strong class="orange">¥{{$v['order_amount']}}</strong></th>
         </tr>
        </table>
       </div><!--dingdanlist/-->
      @endforeach
    </div>
     <script>
        $('#1').click(function(){
          var is_status=1;
          $.ajaxSetup({
                headers: {
                  'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                }
              });
          $.post(
            '/order_div', 
            {is_status:is_status}, 
            function(res) {
            $('.assorder').html(res);
          });
        });
        $('#2').click(function(){
          var is_status=2;
          $.ajaxSetup({
                headers: {
                  'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                }
              });
          $.post(
            '/order_div', 
            {is_status:is_status},  
            function(res) {
            $('.assorder').html(res);
          });
        });
        $('#3').click(function(){
          var is_status=3;
          $.ajaxSetup({
                headers: {
                  'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                }
              });
          $.post(
            '/order_div', 
            {is_status:is_status}, 
            function(res) {
            $('.assorder').html(res);
          });
        });
        $('#4').click(function(){
          var is_status=4;
          $.ajaxSetup({
                headers: {
                  'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                }
              });
          $.post(
            '/order_div',  
            {is_status:is_status},  
            function(res) {
            $('.assorder').html(res);
          });
        });
        $('#sss').click(function(){
          var order_id=$(this).attr('order_id');
          $.ajaxSetup({
                headers: {
                  'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                }
              });
          $.post(
            '/is_order',  
            {order_id:order_id},  
            function(res) {
            if(res==1){
              alert('取消成功');
              window.location.href = "/order?is_status=1";
            }else{
              alert('取消失败');
            }
          });
        })
     </script>

     
    @include('public.footer') 
    @endsection