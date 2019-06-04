    @extends('public.header')
        <!--左侧导航开始-->
        <nav class="navbar-default navbar-static-side" role="navigation">
            <div class="nav-close"><i class="fa fa-times-circle"></i>
            </div>
            <div class="sidebar-collapse">
                <ul class="nav" id="side-menu">
                    <li class="nav-header">
                        <div class="dropdown profile-element">
                            <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                <span class="clear">
                                    <span class="block m-t-xs" style="font-size:20px;">
                                        <i class="fa fa-area-chart"></i>
                                        <strong class="font-bold">hAdmin</strong>
                                    </span>
                                </span>
                            </a>
                        </div>
                        <div class="logo-element">hAdmin
                        </div>
                    </li>
                    <li class="hidden-folded padder m-t m-b-sm text-muted text-xs">
                        <span class="ng-scope">分类</span>
                    </li>
                    <li>
                        <a class="J_menuItem" href="/weather">
                            <i class="fa fa fa-bar-chart-i"></i>
                            <span class="nav-label">首页</span>
                            <span class="fa comments"></span>
                        </a>
                    </li>
                    @if(in_array('IsUpload',$per))
                    <li>
                        <a href="#">
                            <i class="fa fa fa-bar-chart-oi"></i>
                            <span class="nav-label">微信公众号素材</span>
                            <span class="fa comments"></span>
                        </a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a class="J_menuItem" href="/IsUpload">公众号素材列表</a>
                            </li>
                            <li>
                                <a class="J_menuItem" href="/WechatImg">上传公众号素材</a>
                            </li>
                        </ul>
                    </li>
                    @endif

                    @if(in_array('Menu',$per))
                    <li>
                        <a href="#">
                            <i class="fa fa fa-bar-chart-i"></i>
                            <span class="nav-label">微信公众号菜单</span>
                            <span class="fa comments"></span>
                        </a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a class="J_menuItem" href="/Menu">公众号菜单列表</a>
                            </li>
                            <li>
                                <a class="J_menuItem" href="/InMenu">上传公众号菜单</a>
                            </li>
                        </ul>
                    </li>
                    @endif

                    @if(in_array('IsQrCode',$per))
                    <li>
                        <a href="#">
                            <i class="fa fa fa-bar-chart-i"></i>
                            <span class="nav-label">微信公众号推广</span>
                            <span class="fa comments"></span>
                        </a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a class="J_menuItem" href="/IsQrCode">公众号推广列表</a>
                            </li>
                            <li>
                                <a class="J_menuItem" href="/addOrCode">添加公众号推广</a>
                            </li>
                        </ul>
                    </li>
                    @endif

                    @if(in_array('fans',$per))
                    <li>
                        <a href="#">
                            <i class="fa fa fa-bar-chart-i"></i>
                            <span class="nav-label">粉丝管理</span>
                            <span class="fa comments"></span>
                        </a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a class="J_menuItem" href="/fans">粉丝列表</a>
                            </li>
                            <li>
                                <a class="J_menuItem" href="/TubeLabel">管理标签</a>
                            </li>
                            <li>
                                <a class="J_menuItem" href="/addLabel">添加标签</a>
                            </li>
                        </ul>
                    </li>
                    @endif

                    @if(in_array('IsAMass',$per))
                    <li>
                        <a href="#comments">
                            <i class="fa fa-comments"></i>
                            <span class="nav-label">群发消息</span>
                            <span class="fa comments"></span>
                        </a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a class="J_menuItem" href="/IsAMass">一键群发</a>
                            </li>
                        </ul>
                    </li>
                    @endif
                    <li>
                        <a href="#comments">
                            <i class="fa fa-comments"></i>
                            <span class="nav-label">活动</span>
                            <span class="fa comments"></span>
                        </a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a class="J_menuItem" href="/SignView">签到</a>
                            </li>
                        </ul>
                    </li> 
                    @if(in_array('role',$per))
                    <li>
                        <a href="#comments">
                            <i class="fa fa-comments"></i>
                            <span class="nav-label">角色管理</span>
                            <span class="fa comments"></span>
                        </a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a class="J_menuItem" href="/role">角色展示</a>
                            </li>
                        </ul>
                        <ul class="nav nav-second-level">
                            <li>
                                <a class="J_menuItem" href="/addRole">角色添加</a>
                            </li>
                        </ul>
                        <ul class="nav nav-second-level">
                            <li>
                                <a class="J_menuItem" href="/PerMiss">权限添加</a>
                            </li>
                        </ul>
                    </li>
                    @endif
                    <li>
                        <a href="#comments">
                            <i class="fa fa-comments"></i>
                            <span class="nav-label">微信打赏</span>
                            <span class="fa comments"></span>
                        </a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a class="J_menuItem" href="/Pays">支付</a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="#comments">
                            <i class="fa fa-comments"></i>
                            <span class="nav-label">测试系统</span>
                            <span class="fa comments"></span>
                        </a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a class="J_menuItem" href="/TheTest">测试题</a>
                            </li>
                        </ul>
                        <ul class="nav nav-second-level">
                            <li>
                                <a class="J_menuItem" href="/addTheTest">添加测题</a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="#comments">
                            <i class="fa fa-comments"></i>
                            <span class="nav-label">优惠券</span>
                            <span class="fa comments"></span>
                        </a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a class="J_menuItem" href="/coupons">优惠券添加</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
        <!--左侧导航结束-->
        <!--右侧部分开始-->
        <div id="page-wrapper" class="gray-bg dashbard-1">
            <div class="row border-bottom">
                <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                    <div class="navbar-header"><a class="navbar-minimalize minimalize-styl-2 btn btn-info " href="#"><i class="fa fa-bars"></i> </a>
                        <form role="search" class="navbar-form-custom" method="post" action="search_results.html">
                            <div class="form-group">
                                <input type="text" placeholder="请输入您需要查找的内容 …" class="form-control" name="top-search" id="top-search">
                            </div>
                        </form>
                    </div>
                    <ul class="nav navbar-top-links navbar-right">
                        <li class="dropdown">
                            <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                                <i class="fa fa-envelope"></i> <span class="label label-warning">16</span>
                            </a>
                            <ul class="dropdown-menu dropdown-messages">
                                <li class="m-t-xs">
                                    <div class="dropdown-messages-box">
                                        <a href="profile.html" class="pull-left">
                                            <img alt="image" class="img-circle" src="/hAdmin/img/a7.jpg">
                                        </a>
                                        <div class="media-body">
                                            <small class="pull-right">46小时前</small>
                                            <strong>小四</strong> 是不是只有我死了,你们才不骂爵迹
                                            <br>
                                            <small class="text-muted">3天前 2014.11.8</small>
                                        </div>
                                    </div>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <div class="dropdown-messages-box">
                                        <a href="profile.html" class="pull-left">
                                            <img alt="image" class="img-circle" src="/hAdmin/img/a4.jpg">
                                        </a>
                                        <div class="media-body ">
                                            <small class="pull-right text-navy">25小时前</small>
                                            <strong>二愣子</strong> 呵呵
                                            <br>
                                            <small class="text-muted">昨天</small>
                                        </div>
                                    </div>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <div class="text-center link-block">
                                        <a class="J_menuItem" href="mailbox.html">
                                            <i class="fa fa-envelope"></i> <strong> 查看所有消息</strong>
                                        </a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                        <li class="dropdown">
                            <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                                <i class="fa fa-bell"></i> <span class="label label-primary">8</span>
                            </a>
                            <ul class="dropdown-menu dropdown-alerts">
                                <li>
                                    <a href="mailbox.html">
                                        <div>
                                            <i class="fa fa-envelope fa-fw"></i> 您有16条未读消息
                                            <span class="pull-right text-muted small">4分钟前</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <a href="profile.html">
                                        <div>
                                            <i class="fa fa-qq fa-fw"></i> 3条新回复
                                            <span class="pull-right text-muted small">12分钟钱</span>
                                        </div>
                                    </a>
                                </li>
                                <li class="divider"></li>
                                <li>
                                    <div class="text-center link-block">
                                        <a class="J_menuItem" href="notifications.html">
                                            <strong>查看所有 </strong>
                                            <i class="fa fa-angle-right"></i>
                                        </a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </nav>
            </div>
            <div class="row J_mainContent" id="content-main">
                <iframe id="J_iframe" width="100%" height="100%" src="/weather" frameborder="0" data-id="index_v1.html" seamless></iframe>
            </div>
        </div>
        <!--右侧部分结束-->
