   @extends('layouts.shop')
    @section('title','微商城登陆')
    @section('content')
     <header>
      <a href="javascript:history.back(-1)" class="back-off fl"><span class="glyphicon glyphicon-menu-left"></span></a>
      <div class="head-mid">
       <h1>会员注册</h1>
      </div>
     </header>
          @if(session('msg'))
      <div class="alert alert-success">
        {{session('msg')}}
      </div>
      @endif
        @if ($errors->any())
        <div class="alert alert-danger">
          <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
        @endif
     <div class="head-top">
      <img src="{{asset('shop/images/head.jpg')}}" />
     </div><!--head-top/-->
     <form action="user" method="get" class="reg-login">
      <h3>还没有三级分销账号？点此<a class="orange" href="reg">注册</a></h3>
      <div class="lrBox">
       <div class="lrList"><input type="text" name='email' placeholder="输入手机号码或者邮箱号" class="names" /></div>
       <div class="lrList"><input type="password" name='password' placeholder="输入密码" class="pwd" /></div>
      </div><!--lrBox/-->
      <div class="lrSub">
       <input type="submit" value="立即登录" class="btn" />
      </div>
     </form>
    @include('public.footer')
    <script>
    $(function(){
        $('.btn').click(function(){
          var email=$('.names').val();
          var password=$('.pwd').val();
          if(email==""){
            alert('请填写账号');
            return false;
          }
          if(password==""){
            alert('请填写密码');
            return false;
          }
          $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
            }
          });
          $.post(
            '/islogin', 
            {email:email,password:password}, 
            function(res){
             if(res=='登陆成功'){
                alert('登陆成功');
                window.location.href = 'user';
             }else{
                alert(res);
                return false;
             }
          });
          return false;
        });
    });
    </script>
    @endsection