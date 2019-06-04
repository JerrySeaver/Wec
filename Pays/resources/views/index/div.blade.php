 @foreach($data as $key=>$val)
          <dl>
           <dt><a href="proinfo?goods_id={{$val->goods_id}}"><img src="{{asset('shop/uploads').'/'.$val->goods_img}}" width="100" height="100" /></a></dt>
           <dd>
            <h3><a href="proinfo?goods_id={{$val->goods_id}}">{{$val->goods_name}}</a></h3>
            <div class="prolist-price"><strong>¥{{$val->shop_price}}</strong> <span>¥{{$val->shop_price*2}}</span></div>
            <div class="prolist-yishou"><span>5.0折</span> <em>已售:0< /em></div>
           </dd>
           <div class="clearfix"></div>
          </dl>
          @endforeach
      @include('public.footer')
        </div>