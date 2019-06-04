@extends('public.header')
<body class="gray-bg">
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-content">
                        <form action="/DoAddFans" method="post">
                            <table class="table table-striped table-bordered table-hover " id="editable">
                                <thead>
                                    <tr>
                                        <th>添加</th>
                                        <th>ID</th>
                                        <th>用户名称</th>
                                        <th>oPenId</th>
                                        <th>性别</th>
                                        <th>所属地</th>
                                        <th>关注时间</th>
                                    </tr>
                                </thead>
                                <tfoot>
                                @foreach($data as $k=>$v)
                                    <tr>
                                        <th>
                                            <input type="checkbox" name="openid_list[]" value=" {{$v->openid}}">
                                            <input type="hidden" name="tagid" value=" {{$tagid}}">
                                        </th>
                                        <th>{{$v->id}}</th>
                                        <th>{{$v->nickname}}</th>
                                        <th>{{$v->openid}}</th>
                                        <th>
                                        @if($v->sex=="1")
                                        男
                                        @else
                                        女
                                        @endif
                                        </th>
                                        <th>{{$v->region}}</th>
                                        <th>{{date("Y-m-d h:i:s",$v->subscribe_time)}}</th>
                                    </tr>
                                @endforeach
                                </tfoot> 
                                
                            </table>
                            <button>确定添加</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- 全局js -->
    <script src="js/jquery.min.js?v=2.1.4"></script>
    <script src="js/bootstrap.min.js?v=3.3.6"></script>

 