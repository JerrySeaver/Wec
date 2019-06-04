    @extends('layouts.shop')
    @section('title','微商城注册')
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
     <form action="/isreg" method="get" class="reg-login">
      <h3>已经有账号了？点此<a class="orange" href="login">登陆</a></h3>
      <div class="lrBox">
       <div class="lrList"><input type="text" class="email" placeholder="输入手机号码或者邮箱号" name="email" /></div>
       <div class="lrList2"><input type="text" class="rand" placeholder="输入验证码" name="rand"/> <button class='btn'>获取验证码</button></div>
       <div class="lrList"><input type="password" class="password" placeholder="设置新密码（6-18位数字或字母）" name="password" /></div>
       <div class="lrList"><input type="password" class="repassword" placeholder="再次输入密码" /></div>
      </div><!--lrBox/-->
      <div class="lrSub">
       <input type="submit" class="btns" value="立即注册" />
      </div>
     </form>
    </div><!--maincont-->
    @include('public.footer')
    <script>
      $(function(){
          $('.btn').click(function(){
          var email=$('.email').val();
          var reg=/^[A-Za-z0-9]+@[a-zA-Z0-9_-]+(\.com+)+$/;
            console.log(email);
            if(email==''){
                alert('请填写邮箱');
                return false;
            }
            if(!reg.test(email)){
              alert('邮箱格式不正确');
                return false;
            }
            $.ajaxSetup({
                    headers: {
                      'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                    }
                  });
            $.post(
              '/checkEmail', 
              {email:email}, 
              function(res){
                if(res==1){
                  alert('邮箱已存在');
                  return false;
                }else{
                  $.ajaxSetup({
                    headers: {
                      'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                    }
                  });
                $.post(
                  '/email', 
                  {email:email}, 
                  function(res){
                    //倒计时
                        if(res){
                          //倒计时
                          $('.btn').css('pointer-events','none');
                          $('.btn').text('60'+'s');
                          timeLess = setInterval(function(){
                            var tms = parseInt($('.btn').text());
                            tms-=1;
                            $('.btn').text(tms+'s');
                            
                            //删除定时器
                            if (tms <= 0) {
                              clearInterval(timeLess);
                              $('.btn').text('获取');
                              $('.btn').css('pointer-events','auto');
                            }
                          }, 1000);
                        }
                    });
                }
            });
            return false;
          });
    /*点击注册*/
        $('.btns').click(function(){
          var rand=$('.rand').val();
          var pwd=$('.password').val();
          var repwd=$('.repassword').val();email
          var email=$('.email').val();
          console.log(rand);
          console.log(pwd);
          console.log(repwd);
          if(email==""){
            alert('请输入邮箱');
            return false;
          }
          if(rand==""){
            alert('请输入验证码');
            return false;
          }
          if(pwd==""){
            alert('请输入密码');
            return false;
          }
          if(repwd==""){
            alert('请再次输入密码');
            return false;
          }
          if(pwd!==repwd){
            alert('两次密码不一致');
            return false;
          }
          if(pwd!==repwd){
            alert('两次密码不一致');
            return false;
          }
        });
      });
    </script>
    @endsection
    
      

   