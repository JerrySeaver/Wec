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
                                    <th>ID</th>
                                    <th>名称</th>
                                    <th>类型</th>
                                    <th>关键字</th>
                                    <th>操作</th>
                                </tr>
                            </thead>
                            <tfoot>
                            @foreach($data as $k=>$v)
                                <tr>
                                    <th>{{$v->id}}</th>
                                    @if($v->parent_id!=0)


                                    <th>—|{{$v->names}}</th>
                                    @else
                                    <th>{{$v->names}}</th>
                                    @endif
                                    <th>{{$v->type}}</th>
                                    <th>{{$v->key}}</th>
                                    <th>
                                        <a href="/UpMenu?id={{$v->id}}">编辑</a>|
                                        <a href="/DelMenu?id={{$v->id}}">删除</a>
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
    <div class="col-sm-12 col-sm-offset-3">
        <a href="/InMenu">
            <button class="btn btn-sm btn-primary" type="submit">上传菜单</button>
        </a>
        <a href="/synchronous">
            <button class="btn btn-sm btn-success" type="submit">同步公众号</button>
        </a>
        
    </div>
	    <div class="container">
	     	@foreach ($data as $user)
	     	{{ $user->name }}
	     	@endforeach
     	</div>
	     	{{ $data->links() }}";
    <!-- 全局js -->
    <script src="js/jquery.min.js?v=2.1.4"></script>
    <script src="js/bootstrap.min.js?v=3.3.6"></script>

 