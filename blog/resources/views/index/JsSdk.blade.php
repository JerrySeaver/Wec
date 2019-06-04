@extends('public.header')
<body class="gray-bg">
    <div class="wrapper wrapper-content animated fadeInRight">
    
        <div class="row">
            <div class="col-sm-12">
            
                <div class="ibox float-e-margins">
                
                    <div class="ibox-content">
   
                        <table class="table table-striped table-bordered table-hover " id="editable">
                            <thead>
                                <tr>
                                    <th>图片ID</th>
                                    <th>图片名称</th>
                                    <th>图片展示</th>
                                    <th>图片MedialId</th>
                                    <th>操作</th>
                                </tr>
                            </thead>
                            <tfoot>
                          
                            </tfoot>   
                    </div>
                            <button class="btn"></button>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="/hAdmin/js/jquery.min.js?v=2.1.4"></script>
    <script src="http://res.wx.qq.com/open/js/jweixin-1.4.0.js"></script>
    <script>
            wx.config({
                debug: true, // 开启调试模式,调用的所有api的返回值会在客户端alert出来，若要查看传入的参数，可以在pc端打开，参数信息会通过log打出，仅在pc端时才会打印。
                appId: 'wx1f9161f806626795', // 必填，公众号的唯一标识
                timestamp: "{{$timestamp}}", // 必填，生成签名的时间戳
                nonceStr: "{{$noncestr}}", // 必填，生成签名的随机串
                signature: "{{$signature}}",// 必填，签名
                jsApiList: ['updateAppMessageShareData'] // 必填，需要使用的JS接口列表
            });
            wx.ready(function () {   //需在用户可能点击分享按钮前就先调用
                    wx.updateAppMessageShareData({ 
                        title: '微信公众号', // 分享标题
                        desc: '后台管理微信公众号分享模块', // 分享描述
                        link: '39.97.101.66/JsSdk', // 分享链接，该链接域名或路径必须与当前页面对应的公众号JS安全域名一致
                        imgUrl: '', // 分享图标
                        success: function () {
                            alert(1);
                        }
                    })
                });
            $('.btn').click(function(){
                
            })
            
    </script>
    
 