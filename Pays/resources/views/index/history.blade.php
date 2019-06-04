   @extends('layouts.shop')
    @section('title','微商城')
    @section('content')
<body>
    <div class="maincont">
     <header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <h1>我的浏览记录</h1>
      </div>
     </header>
     <div class="head-top">
      <img src="{{asset('shop/images/head.jpg')}}" />
     </div><!--head-top/-->
     <table class="shoucangtab">
      <tr>
       <td width="75%"><span class="hui"><strong class="orange"></strong></span></td>
       <td width="25%" align="center" style="background:#fff url(images/xian.jpg) left center no-repeat;"><a href="javascript:;" class="orange"></a></td>
      </tr>
     </table>
      @foreach($res as $key=>$val)
     <div class="dingdanlist">
      <table>
       <tr>
        <td colspan="2" width="65%"></td>
        <td width="35%" align="right" class=""><div class="qingqu"><a href="javascript:;" h_id={{$val['h_id']}} class="abacd orange">删除此条记录</a></div></td>
       </tr>
       <tr>
        <td class="dingimg" width="15%"><img src="{{asset('shop/uploads').'/'.$val["goods_img"]}}"/></td>
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
        var h_id=$(this).attr('h_id');
        $.ajaxSetup({
                headers: {
                  'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                }
              });
        $.post(
          '/HistoryIn', 
          {h_id:h_id}, 
          function(res){
          if(res==1){
            alert('取消成功');
            window.location.href = "/historys";
          }else{
            alert('取消失败');
          }
        });
     })
     </script>
@include('public.footer')
     @endsection