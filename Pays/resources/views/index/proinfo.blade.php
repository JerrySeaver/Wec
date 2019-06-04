 @extends('layouts.shop')
        @section('title','微商城首页')
        @section('content')
  <body>
    <div class="maincont">
     <header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <h1>产品详情</h1>
      </div>
     </header>
     <div id="sliderA" class="slider">
      <img src="{{asset('shop/uploads').'/'.$data[0]->goods_img}}" />
     </div><!--sliderA/-->
     <table class="jia-len">
      <tr>
       <th>
        <h4>价格：<strong class="orange">{{$data[0]->shop_price}}</strong></h4>
            
        </th>
       <td>
        <p class="hui">库存:<strong class="orange">{{$data[0]->goods_number}}</strong></p>
       </td>
      </tr>
      <tr>
       <td>
        <strong>{{$data[0]->goods_name}}</strong>
        <p class="hui">{{$data[0]->content}}</p>
       </td>
       <td align="right">
        <a href="javascript:;" class="shoucang"><span class="glyphicon glyphicon-star-empty" goods_id="{{$data[0]->goods_id}}"></span></a>
       </td>
      </tr>
     </table>
     <div class="height2"></div>
     <h3 class="proTitle">商品规格</h3>
     <ul class="guige">
      <li class="guigeCur"><a href="javascript:;">50ML</a></li>
      <li><a href="javascript:;">100ML</a></li>
      <li><a href="javascript:;">150ML</a></li>
      <li><a href="javascript:;">200ML</a></li>
      <li><a href="javascript:;">300ML</a></li>
      <div class="clearfix"></div>
     </ul><!--guige/-->
     <div class="height2"></div>
     <div class="zhaieq">
      <a href="javascript:;" class="zhaiCur">商品简介</a>
      <a href="javascript:;">商品参数</a>
      <a href="javascript:;" style="background:none;">订购列表</a>
      <div class="clearfix"></div>
     </div><!--zhaieq/-->
     <div class="proinfoList">
      <img src="http://uploads.pays.com/{{$data[0]->goods_img}}" width="636" height="822" />
     </div><!--proinfoList/-->
     <div class="proinfoList">
      暂无信息....
     </div><!--proinfoList/-->
     <div class="proinfoList">
      暂无信息......
     </div><!--proinfoList/-->
     <table class="jrgwc">
      <tr>
       <th>
        <a href="index.html"><span class="glyphicon glyphicon-home"></span></a>
       </th>
       <td goods_id="{{$data[0]->goods_id}}"><a href="javascript:;" class="OneBuy">加入购物车</a></td>
      </tr>
     </table>
    </div>
    <script>
        var goods_id=$('.OneBuy').parent('td').attr('goods_id');
          $.ajaxSetup({
                headers: {
                  'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                }
              });
          $.post(
            '/history',
            {goods_id:goods_id},
            function(res){
            console.log(res)
          });
        $('.OneBuy').click(function(){
          goods_id=$(this).parent('td').attr('goods_id');
          $.ajaxSetup({
                headers: {
                  'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                }
              });
          $.post(
            '/OneBuy', 
            {goods_id:goods_id}, 
            function(res){
            if(res==1){
              alert('加入购物车成功');
            }else{
              alert('请先登录');
            }
          });
        });
        $('.glyphicon').click(function(){
          var goods_id=$(this).attr('goods_id');
          $.ajaxSetup({
                headers: {
                  'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                }
          });
          $.post(
            '/Favorites',
            {goods_id:goods_id},
            function(res){
              if(res==1){
                alert('收藏成功');
              }else if(res==3){
                alert('收藏失败，未知错误');
              }else if(res==2){
                alert('您已经收藏过了！');
              }else if(res==0){
                alert('请您先登录');
              }
          });
        });
    </script>
    @endsection
    