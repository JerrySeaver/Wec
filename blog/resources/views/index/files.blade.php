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
                                    <th>素材ID</th>
                                    <th>素材名称</th>
                                    <th>素材url</th>
                                    <th>MedialId</th>
                                    <th>素材类型</th>
                                    <th>操作</th>
                                </tr>
                            </thead>
                            <tfoot>
                            @foreach($data as $k=>$v)
                                <tr>
                                    <th>{{$val->id}}</th>
                                    <th>{{$val->name}}</th>
                                    <th>{{$val->url}}</th>
                                    <th>{{$val->medial_id}}</th>
                                    <th>{{$val->type}}</th>
                                    <th>
                                        <a href="">编辑</a>|
                                        <a href="">删除</a>
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

 