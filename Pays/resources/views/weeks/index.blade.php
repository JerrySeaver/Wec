<!DOCTYPE html>
<html lang="zh-cn">
  <head>
    <meta charset="utf-8">
    <title>登陆</title>
    <script src="{{asset('shop/js/jquery-3.3.1.min.js')}}"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
  </head>
  <body>
  <form>

    <table border="1" align="center">
    <h4 align="center">登陆页面</h4>
      <tr>
        <td>用户名</td>
        <td>
        @csrf
            <input type="text" name="username" class="username">
        </td>
      </tr>
      <tr>
        <td>密码</td>
        <td>
            <input type="password" name="password" class="password">
        </td>
      </tr>
      <tr>
        <td></td>
        <td>
            <button class="btn">登陆</button>
            <a href="/weeks/regis" align="right">点击注册</a>
        </td>
      </tr>
    </table>
  </form>
  </body>
  <script>
    $(function(){
      $('.btn').click(function(){
        var username=$('.username').val();
        var password=$('.password').val();
        if(username==''){
          alert('请输入用户名');
          return false;
        }
        if(password==''){
          alert('请输入密码');
          return false;
        }
        $.ajaxSetup({
          headers: {
          'X-CSRF-TOKEN':$('meta[name="csrf-token"]').attr('content')
          }
        });
        $.post(
          '/weeks/login',
          {username:username,password:password},
          function(res) {
            if(res!="登陆成功"){
              alert(res);
            }else{
              window.location.href = "/weeks/user";
            }
        });
          return false; 
      });
        
    });
  </script>
</html>
