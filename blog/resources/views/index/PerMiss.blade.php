@extends('public.header')
<body class="gray-bg">
    <div class="wrapper wrapper-content">

        <div class="row">
            <div class="col-sm-5">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>权限添加</h5>
                    </div>
                    <div class="ibox-content">
                        <form role="form" class="form-horizontal m-t" method="post" action="/doPerMiss">
                        <div class="form-group draggable">
                                <label class="col-sm-3 control-label">权限名称</label>
                                <div class="col-sm-9">
                                    <input type="text" name="name" class="form-control names" placeholder="请输入权限名称">
                                    <span class="help-block m-b-none"></span>
                                </div>
                            </div>

                            <div class="form-group draggable">
                                <label class="col-sm-3 control-label">控制器名称</label>
                                <div class="col-sm-9">
                                    <input type="text" name="con_name" class="form-control names" placeholder="请输入控制器名称">
                                    <span class="help-block m-b-none"></span>
                                </div>
                            </div>

                            <div class="form-group draggable">
                                <label class="col-sm-3 control-label">方法名称</label>
                                <div class="col-sm-9">
                                    <input type="text" name="methods" class="form-control names" placeholder="请输入方法名称">
                                    <span class="help-block m-b-none"></span>
                                </div>
                            </div>
                            
                            <div class="form-group draggable">
                                <label class="col-sm-3 control-label">上级菜单名称</label>
                                <div class="col-sm-9">
                                    <select class="form-control" name="parent_id">
                                        <option value="0">请选择父级分类</option>
                                        @foreach($data as $k=>$val)
                                            <option value="{{$val['id']}}">{{$val['name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="hr-line-dashed"></div>
                            <div class="form-group draggable">
                                <div class="col-sm-12 col-sm-offset-3">
                                    <button class="btn btn-primary" type="submit">保存内容</button>
                                    <button class="btn btn-white" type="submit">取消</button>
                                </div>
                            </div>
                        </form>
                        <div class="clearfix"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script src="/jquery-3.3.1.min.js"></script>
    @extends('public.header')
    <script>
    </script>

