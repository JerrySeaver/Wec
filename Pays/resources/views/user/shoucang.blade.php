   @extends('layouts.shop')
    @section('title','微商城')
    @section('content')
<body>
    <div class="maincont">
     <header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <h1>我的收藏</h1>
      </div>
     </header>
     <div class="head-top">
      <img src="images/head.jpg" />
     </div><!--head-top/-->
     <table class="shoucangtab">
      <tr>
       <td width="75%"><span class="hui">收藏栏共有：<strong class="orange">{{$count}}</strong>件商品</span></td>
       <td width="25%" align="center" style="background:#fff url(images/xian.jpg) left center no-repeat;"><a href="javascript:;" class="orange">全部删除</a></td>
      </tr>
     </table>
      @foreach($FavorInfo as $key=>$val)
     <div class="dingdanlist">
      <table>
       <tr>
        <td colspan="2" width="65%"></td>
        <td width="35%" align="right" class=""><div class="qingqu"><a href="javascript:;" f_id={{$val['f_id']}} class="abacd orange">取消收藏</a></div></td>
       </tr>
       <tr>
        <td class="dingimg" width="15%"><img src="{{asset('shop/uploads').'/'.$val["goods_img"]}}" /></td>
        <td width="50%">
         <h3>{{$val['goods_name']}}</h3>
        </td>
        <td align="right"><img src=" {{asset('shop/images/jian-new.png')}}" onClick="window.location.href='proinfo?goods_id={{$val['goods_id']}}'"/></td>
       </tr>
       <tr>
        <th colspan="3"><strong class="orange">¥{{$val['shop_price']}}</strong></th>
       </tr>
      </table>
     </div><!--dingdanlist/--> 
     @endforeach
     <script>
     /*取消收藏*/
     $('.abacd').click(function(){
        var f_id=$(this).attr('f_id');
        $.ajaxSetup({
                headers: {
                  'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                }
              });
        $.post(
          '/shoucangOn', 
          {f_id:f_id}, 
          function(res){
          if(res==1){
            alert('取消成功');
            window.location.href = "/shoucang";
          }else{
            alert('取消失败');
          }
        });
     })
     </script>
@include('public.footer')
     @endsection