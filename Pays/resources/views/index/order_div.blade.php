     @foreach($res as $k=>$v)
     <div class="dingdanlist" >
      <table>
       <tr>
        <td colspan="2" width="65%">订单号：<strong>{{$v['order_number']}}</strong></td>
        <td width="35%" align="right"><div class="qingqu"><a href="javascript:;" id="sss" class="orange">订单取消</a></div></td>
       </tr>
           @foreach($ress as $key=>$val)
           <tr onClick="window.location.href='proinfo.html'">
            <td class="dingimg" width="15%"><img src="{{asset('shop/uploads').'/'.$val["goods_img"]}}" /></td>
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