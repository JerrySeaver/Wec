   @extends('layouts.shop')
    @section('title','微商城购物车')
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
     <form action="" method="post" class="reg-login">
      <div class="lrBox">
       <div class="lrList"><input type="text" value="{$addInfo['add_name']}" id="add_name" placeholder="收货人" /></div>
       <div class="lrList">
        <select id="province" class="changearea">
        <option value="" selected="selected">--省份--</option>
        @foreach($areaInfo as $k=>$v)
        <option value="{{$v['id']}}">{{$v['name']}}</option>
        @endforeach
        </select>
       </div>
       <div class="lrList">
        <select id="city" class="changearea">
         <option value="" selected="selected">--直辖市--</option>
        </select>
       </div>
       <div class="lrList">
        <select id="area" class="changearea">
         <option value="" selected="selected">--区县--</option>
        </select>
       </div>
       <div class="lrList"><input type="text" value="{$addInfo['address_detail']}" id="address_detail" placeholder="详细地址" /></div>
       <div class="lrList"><input type="text" value="{$addInfo['address_tel']}" id="address_tel" placeholder="手机" /></div>
       <div class="lrList2"><input type="text" placeholder="设为默认地址" /><span></span> <button class="btn">设为默认</button></div>
      </div><!--lrBox/-->
      <div class="lrSub">
       <input type="submit" class="sub" value="保存" />
      </div>
     </form><!--reg-login/-->
     <script>
        $(function(){
          /*三级联动*/
            $('.changearea').change(function(){
              var _this=$(this);
              var _option="<option value='' selected='selected'>--请选择--</option>";
              _this.parent('div').next('div').find('select').html(_option);
              var pid=_this.val();
              $.ajaxSetup({
                headers: {
                  'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                }
              });
              $.post(
                  "/AddProvince", 
                  {pid:pid}, 
                  function(res){
                    // _option="";
                    for (var i in res) {
                      _option+="<option value='"+res[i]['id']+"'>"+res[i]['name']+"</option>";
                    };
                    console.log(_option);
                    _this.parent('div').next('div').find('select').html(_option);
                 });
            });
            /*提交表单*/
            $('.btn').click(function(){
              $(this).prev('span').append('<input type="hidden" id="is_default" value="1">');
              return false;
            });
            $(".sub").click(function(){
              var obj={};
              obj.province=$('#province').val();
              obj.city=$('#city').val();
              obj.area=$('#area').val();
              obj.address_detail=$('#address_detail').val();
              obj.address_tel=$('#address_tel').val();
              obj.is_default=$('#is_default').val();
              obj.add_name=$('#add_name').val();
              $.ajaxSetup({
                headers: {
                  'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                }
              });
              $.post(
                '/doaddadd', 
                {obj:obj}, 
                function(res) {
                  if(res==1){
                    alert('添加成功');
                    window.location.href = "/address";
                  }else{
                    alert('添加失败');
                  }
              });
              return false;
            })
        });
     </script>
  @include('public.footer')
     @endsection