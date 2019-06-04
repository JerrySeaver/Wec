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
                                    <th>TagId</th>
                                    <th>标签名称</th>
                                    <th>标签人数</th>
                                    <th>操作</th>
                                </tr>
                            </thead>
                            <tfoot>
                            @foreach($data as $k=>$v)
                                <tr>
                                    <th>{{$v->tagid}}</th>
                                    <th>{{$v->name}}</th>
                                    <th>{{$v->count}}</th>
                                    <th>
                                    <a href="/updLabel?tagid={{$v->tagid}}">编辑</a>|
                                    <a href="/delLabel?tagid={{$v->tagid}}">删除</a>|
                                    <a href="/addFans?tagid={{$v->tagid}}">添加粉丝</a>
                                    </th>
                                </tr>
                            @endforeach
                            </tfoot> 
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- 全局js -->
    <script src="js/jquery.min.js?v=2.1.4"></script>
    <script src="js/bootstrap.min.js?v=3.3.6"></script>

 