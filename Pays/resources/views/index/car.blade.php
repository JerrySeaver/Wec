   @extends('layouts.shop')
    @section('title','购物车')
    @section('content')
     <header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <h1>购物车</h1>
      </div>
     </header>
     <div class="head-top">
      <img src="{{asset('shop/images/head.jpg')}}" />
     </div><!--head-top/-->
     <table class="shoucangtab">
      <tr>
       <td width="75%"><span class="hui">购物车共有：<strong class="orange">{{$ges}}</strong>件商品</span></td>
       <td width="25%" align="center" style="background:#fff url({{asset('shop/images/xian.jpg')}}) left center no-repeat;">
        <span class="glyphicon glyphicon-shopping-cart" style="font-size:2rem;color:#666;"></span>
       </td>
      </tr>
     </table>
  @foreach($data as $key=>$val)
     <div class="dingdanlist">
      <table>
       <tr>
        <td width="4%" goods_id="{{$val['goods_id']}}">
            <input type="checkbox" class="checkCounts" name="checkCounts"/>
        </td>
        <td class="dingimg" width="15%"><img src="{{asset('shop/uploads').'/'.$val["goods_img"]}}" /></td>
        <td width="50%">
         <h3>{{$val['goods_name']}}</h3>
        </td>
        <td align="right"  goods_id="{{$val['goods_id']}}"><input type="text" class="spinnerExample" value="{{$val['number']}}"/></td>
       </tr>
       <tr>
        <th colspan="4"><strong class="orange">¥0.00</strong></th>
       </tr>
       <tr>
        <td width="100%" colspan="4"><a href="javascript:;">删除</a></td>
       </tr>
      </table>
     </div><!--dingdanlist/-->
  @endforeach
     <div class="height1"></div>
     <div class="gwcpiao">
     <table>
      <tr>
       <th width="10%">
          <a href="javascript:history.back(-1)">
            <span class="glyphicon glyphicon-menu-left"></span>
          </a>
        </th>
       <td width="50%">总计：
          <strong class="orange">¥</strong>
          <strong class="orange" id="shopPrice">0.00</strong>
        </td>
       <td width="40%">
          <a href="javascript:;" class="jiesuan">去结算</a>
        </td>
      </tr>
     </table>
    </div><!--gwcpiao/-->
    </div><!--maincont-->
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="{{asset('shop/js/jquery.min.js')}}"></script>[]
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="{{asset('shop/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('shop/js/style.js')}}"></script>
    <!--jq加减-->
    <script src="{{asset('shop/js/jquery.spinner.js')}}"></script>
   <script>
	$('.spinnerExample').spinner({});
	</script>
  <script>
    $(function(){
        /*点击减号*/
        $('.decrease').click(function(){
            var counts=$(this).next().val();
            var check=$(this).parent('div').parent('td').prev().prev().prev().find('input').attr('checked',true);
            var Checked=$(this).parent('div').parent('td').prev().prev().prev().find('input').is(':checked');
            var _this=$(this);
            var goods_id=$(this).parent('div').parent('td').attr('goods_id');
            if(Checked){
              $.ajaxSetup({
                headers: {
                  'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                }
              });
              $.post(
                "/CountPrice", 
                {goods_id:goods_id,goods_number:counts}, 
                function(res){
                var countPrice=_this.parent('div').parent('td').parent('tr').next('tr').find('th').find('strong');
                countPrice.html("￥"+res+".00");
              });
            }
            /*获取总价格*/
            checkCounts(counts);
        });
        /*点击加号*/
        $('.increase').click(function(){
            var counts=$(this).prev().val();
            var check=$(this).parent('div').parent('td').prev().prev().prev().find('input').attr('checked',true);
            var _this=$(this);
            var Checked=$(this).parent('div').parent('td').prev().prev().prev().find('input').is(':checked');
            var goods_id=$(this).parent('div').parent('td').attr('goods_id');
            if(Checked){
              $.ajaxSetup({
                headers: {
                  'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                }
              });
              $.post(
                "/CountPrice", 
                {goods_id:goods_id,goods_number:counts}, 
                function(res){
                var countPrice=_this.parent('div').parent('td').parent('tr').next('tr').find('th').find('strong');
                countPrice.html("￥"+res+".00");
                
              });
            }
            /*获取总价格*/
            checkCounts(counts);
        });
        /*获取总价格*/
        function checkCounts(counts){
          var checkCounts=$('.checkCounts');
          var shopPrice=$('#shopPrice').html();
          goods_id="";
           checkCounts.each(function(index){
             if($(this).is(':checked')){
               goods_id+=$(this).parent('td').attr('goods_id')+',';
             }
           });
           goods_id=goods_id.substr(0,goods_id.length-1);
           $.ajaxSetup({
                headers: {
                  'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                }
              });
              $.post(
                "/checkCounts", 
                {goods_id:goods_id,goods_number:counts}, 
                function(res){
                  $('#shopPrice').text(res);
              });
        }
        $('.jiesuan').click(function(){
            var checkCounts=$('.checkCounts');
            goods_id="";
            checkCounts.each(function(index){
             if($(this).is(':checked')){
               goods_id+=$(this).parent('td').attr('goods_id')+',';
             }
           });
            goods_id=goods_id.substr(0,goods_id.length-1);
            $.ajaxSetup({
                headers: {
                  'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                }
              });
              $.post(
                "/ispay", 
                function(res){
                  if(res==1){
                    alert('请先登录');
                  }else{
                    if(goods_id==''){
                      alert('请选择商品');
                      return false;
                    }else{
                      alert('正在结算');
                      window.location.href = "/pay?goods_id="+goods_id;
                    }
                  }
              });
        })
    });
  </script>
  </body>
</html>
@endsection
