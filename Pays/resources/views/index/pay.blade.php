   @extends('layouts.shop')
    @section('title','微商城支付')
    @section('content')
  <body>
    <div class="maincont">
     <header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <h1>支付</h1>
      </div>
     </header>
     <div class="head-top">
      <img src="{{asset('shop/images/head.jpg')}}" />
     </div><!--head-top/-->
     <div class="dingdanlist">
      <table>
       
       @if(empty($resAdd))
          <tr  onClick="window.location.href='/address'">
            <td class="dingimg"  width="75%" colspan="2">选择收货地址</td>
            <td align="right"><img src="{{asset('shop/images/jian-new.png')}}"  /></td>
         </tr>
       @else
       <tr  onClick="window.location.href='/address'">
          <td class="dingimg"  width="75%" colspan="2">选择收货地址</td>
          <td align="right"><img src="{{asset('shop/images/jian-new.png')}}"  /></td>
        </tr>
        @foreach($add as $key => $val)
       <tr>
        <td width="50%">
         <h3>{{$val['add_name']}} {{$val['address_tel']}}</h3>
         <time>{{$val['province']}}{{$val['city']}}{{$val['area']}}{{$val['address_detail']}}</time>
        </td>
        <td align="right"><a href="/upadd?add_id={{$val['add_id']}}" class="hui"><span class="glyphicon glyphicon-check"></span> 修改信息</a></td>
       </tr>
       @endforeach
       
       @endif
       <tr><td colspan="3" style="height:10px; background:#efefef;padding:0;"></td></tr>
       <tr>
        <td class="dingimg" width="75%" colspan="2">支付方式</td>
        <td align="right"><span class="hui">网上支付</span></td>
       </tr>
       <tr><td colspan="3" style="height:10px; background:#efefef;padding:0;"></td></tr>
       <tr>
        <td class="dingimg" width="75%" colspan="2">发票抬头</td>
        <td align="right"><span class="hui">个人</span></td>
       </tr>
       <tr>
        <td class="dingimg" width="75%" colspan="3">商品清单</td>
       </tr>
      @foreach($Info as $key=>$val)
       <tr>
        <td class="dingimg" width="15%"><img src="{{asset('shop/uploads').'/'.$val["goods_img"]}}" /></td>
        <td width="50%">                          
         <h3>{{$val['goods_name']}}</h3>
        </td>
        <td align="right"><span class="qingdan">X{{$val['number']}}</span></td>
       </tr>
       <tr>
        <th colspan="3"><strong class="orange">¥{{$val['shop_price']}}</strong></th>
       </tr>
      @endforeach
       <tr>
        <td class="dingimg" width="75%" colspan="2">商品金额</td>
        <td align="right"><strong class="orange">¥{{$count}}.00</strong></td>
       </tr>
      </table>
     </div><!--dingdanlist/-->
    </div><!--content/-->
    <div class="height1"></div>
    <div class="gwcpiao">
     <table>
      <tr>
       <th width="10%"><a href="javascript:history.back(-1)"><span class="glyphicon glyphicon-menu-left"></span></a></th>
       <td width="50%">总计：<strong class="orange">¥{{$count}}.00</strong></td>
       <td width="40%"><a href="success.html" class="jiesuan">提交订单</a></td>
      </tr>
     </table>
    </div><!--gwcpiao/-->
    </div><!--maincont-->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
    <script src="js/style.js"></script>
    <!--jq加减-->
    <script src="js/jquery.spinner.js"></script>
  <script>
    $('.jiesuan').click(function(){
      var goods_id='{{$goods_id}}';
      // alert(goods_id);
      $.ajaxSetup({
                headers: {
                  'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                }
              });
      $.post(
        '/issuccess', 
        {goods_id:goods_id}, 
        function(res) {
          if(res==22){
            alert('请选择收货地址');
            window.location.href = "/address";
          }else if(res=="111"){
            alert('请选择收货地址');
            window.location.href = "/address";
          }else{
            window.location.href = "/success?order_id="+res;
          }
      });
      return false;
    });
  </script>
  </body>
</html>
     @endsection