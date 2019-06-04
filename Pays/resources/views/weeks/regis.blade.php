<!DOCTYPE html>
<html lang="zh-cn">
  <head>
    <meta charset="utf-8">
    <title>注册</title>
    <script src="{{asset('shop/js/jquery-3.3.1.min.js')}}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
  </head>
  <body>
  <form action="/weeks/reg" method="post">

    <table border="1" align="center">
    <h4 align="center">注册页面</h4>
      <tr>
        <td>用户名</td>
        <td>
            <input type="text" class="username" name="username">
        </td>
      </tr>
      <tr>
        <td>密码</td>
        <td>
            <input type="password" class="password" name="password">
        </td>
      </tr>
      <tr>
        <td>确认密码</td>
        <td>
            <input type="password" class="repassword" name="password">
        </td>
      </tr>
      <tr>
        <td>邮箱</td>
        <td>
            <input type="email" class="email" name="email">
        </td>
      </tr>
      <tr>
        <td><button class="btn">获取验证码</button></td>
        <td>
            <input type="number" name="rand" class="rand">
        </td>
      </tr>
      <tr>
        <td></td>
        <td>
            <button class="subtn">点击注册</button>
        </td>
      </tr>
    </table>
  </form>
  </body>
  <script>
    $(function(){
      /*点击获取邮箱验证码*/
        $('.btn').click(function(){
          var email=$('.email').val();
          if(email==''){
            alert('请输入邮箱');
            return false;
          }
          $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
            }
          });
          $.post(
            '/weeks/checkemail', 
            {email:email}, 
            function(res){
              if(res==1){
                alert('邮箱已经存在');
                return false;
              }else{
                $.ajaxSetup({
                  headers: {
                  'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
                  }
                });
                $.post(
                  '/weeks/email',
                  {email:email},
                  function(res) {
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
        $('.subtn').click(function(){
          var username=$('.username').val();
          var password=$('.password').val();
          var repassword=$('.repassword').val();
          var email=$('.email').val();
          var rand=$('.rand').val();
          if(username==""){
            alert('请输入名称');
            return false;
          }
          if(password==""){
            alert('请输入密码');
            return false;
          }
          if(repassword==""){
            alert('请再次输入密码');
            return false;
          }
          if(email==""){
            alert('请输入邮箱');
            return false;
          }
          if(rand==""){
            alert('请输入验证码');
            return false;
          }
          $.ajaxSetup({
            headers: {
            'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
            }
          });
          $.post(
            '/weeks/reg', 
            {username:username,password:password,repassword:repassword,email:email,rand:rand}, 
            function(res){
              if(res==1){
                alert('密码不一致');
                return false;
              }else if(res==2){
                alert('验证码不正确');
                return false;
              }else if(res==3){
                alert('添加失败');
                return false;
              }else{
                alert('注册成功！');
                window.location.href = "/weeks/index";
              }
          });
          return false; 
        })
    });
  </script>
</html>
