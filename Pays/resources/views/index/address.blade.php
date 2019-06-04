   @extends('layouts.shop')
    @section('title','收货地址')
    @section('content')
  <body>
    <div class="maincont">
     <header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <h1>收货地址</h1>
      </div>
     </header>
     <div class="head-top">
      <img src="{{asset('shop/images/head.jpg')}}" />
     </div><!--head-top/-->
     <table class="shoucangtab">
      <tr>
       <td width="75%"><a href="/addressadd" class="hui"><strong class="">+</strong> 新增收货地址</a></td>
       <td width="25%" align="center" style="background:#fff url({{asset('shop/images/xian.jpg')}}) left center no-repeat;"><a href="javascript:;" class="orange"></a></td>
      </tr>
     </table>
     
     <div class="dingdanlist">
      <table>
       @foreach($add as $key => $val)
       <tr>
        <td width="50%">
         <h3>{{$val['add_name']}} {{$val['address_tel']}}</h3>
         <time>{{$val['province']}}{{$val['city']}}{{$val['area']}}{{$val['address_detail']}}</time>
        </td>
        <td align="right"><a href="/upadd?add_id={{$val['add_id']}}" class="hui"><span class="glyphicon glyphicon-check"></span> 修改信息</a></td>
       </tr>
       @endforeach
      </table>
     </div><!--dingdanlist/-->
  @include('public.footer')
     @endsection