<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title> - 登录</title>
    <meta name="keywords" content="">
    <meta name="description" content="">

    <link rel="shortcut icon" href="favicon.ico"> <link href="/hAdmin/css/bootstrap.min.css?v=3.3.6" rel="stylesheet">
    <link href="/hAdmin/css/font-awesome.css?v=4.4.0" rel="stylesheet">

    <link href="/hAdmin/css/animate.css" rel="stylesheet">
    <link href="/hAdmin/css/style.css?v=4.1.0" rel="stylesheet">
    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html" />
    <![endif]-->
    <script>if(window.top !== window.self){ window.top.location = window.location;}</script>
</head>

<body class="gray-bg">

    <div class="middle-box text-center loginscreen  animated fadeInDown">
        <div>
            <div>

                <h1 class="logo-name">h</h1>

            </div>
            <h3>欢迎登陆微信公众号后台</h3>

            <form class="m-t" role="form" action="/isLogin">
                <div class="form-group">
                    <input type="text" class="form-control username" name="username" placeholder="用户名" required="">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="password" placeholder="密码" required="">
                </div>
                <div class="form-group">
                    <input type="password" class="code" name="code" placeholder="获取微信验证码" required=""> <button id="btn">获取</button>
                </div>
                <img src="/OrCode/Login.jpg" height="80">
                <button type="submit" class="btn btn-primary block full-width m-b">登 录</button>


                <p class="text-muted text-center"> <a href="login.html#"><small>忘记密码了？</small></a> | <a href="register.html">注册一个新账号</a>
                </p>

            </form>
        </div>
    </div>

    <!-- 全局js -->
    <script src="/hAdmin/js/jquery.min.js?v=2.1.4"></script>
    <script src="/hAdmin/js/bootstrap.min.js?v=3.3.6"></script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.4.0.js"></script>

    
    
<script>
            wx.config({
                debug: false, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
                appId: 'wx1f9161f806626795', // 必填，公众号的唯一标识
                timestamp: "{{$JsSdk['timestamp']}}", // 必填，生成签名的时间戳
                nonceStr: "{{$JsSdk['nonceStr']}}", // 必填，生成签名的随机串
                signature: "{{$JsSdk['signature']}}",// 必填，签名
                jsApiList: ['updateAppMessageShareData'] // 必填，需要使用的JS接口列表
            });
            wx.ready(function () {   //需在用户可能点击分享按钮前就先调用
                wx.updateAppMessageShareData({ 
                    title: '登陆页面', // 分享标题
                    desc: '微信后台管理登陆', // 分享描述
                    link: 'http://39.97.101.66/Login', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                    imgUrl: 'https://ss2.bdstatic.com/70cFvnSh_Q1YnxGkpoWK1HF6hhy/it/u=2949701994,2897032056&fm=26&gp=0.jpg', // 分享图标
                    success: function () {
                    // 设置成功
                    }
                })
            });
    $(function(){
        setInterval(automatic(),3000);
        $('#btn').click(function(){
            var username=$('.username').val();
            if(username==""){
                alert('请先填写用户名！');
            }
            $.post(
                '/code', 
                {username:username}, 
                function(res){
                    if(res){
                          //倒计时
                          $('#btn').css('pointer-events','none');
                          $('#btn').text('60'+'s');
                          timeLess = setInterval(function(){
                            var tms = parseInt($('#btn').text());
                            tms-=1;
                            $('#btn').text(tms+'s');
                            
                            //删除定时器
                            if (tms <= 0) {
                              clearInterval(timeLess);
                              $('#btn').text('获取微信验证码');
                              $('#btn').css('pointer-events','auto');
                            }
                          }, 1000);
                        }
            });
            return false;
        });
        function automatic()
        {
            $.post(
                '/automatic', 
                function(res){
                    
                    if(res==1){
                        automatic();
                    }else if(res==2){
                        location.href="/Isindex";
                    }
            });
        }
    })
</script>
</body>

</html>
