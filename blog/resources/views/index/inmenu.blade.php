@extends('public.header')
<body class="gray-bg">
    <div class="wrapper wrapper-content">

        <div class="row">
            <div class="col-sm-5">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>添加菜单</h5>
                    </div>
                    <div class="ibox-content">
                        <form role="form" class="form-horizontal m-t" method="post" action="/doInMenu">
                            <div class="form-group draggable">
                                <label class="col-sm-3 control-label">菜单名称</label>
                                <div class="col-sm-9">
                                    <input type="text" name="names" class="form-control names" placeholder="请输入菜单名称">
                                    <span class="help-block m-b-none"></span>
                                </div>
                            </div>

                            <div class="form-group draggable">
                                <label class="col-sm-3 control-label">父级菜单</label>
                                <div class="col-sm-9">
                                    <select class="form-control" name="parent_id">
                                        <option value="0">父级分类</option>
                                    @foreach($data as $k=>$v)
                                        <option value="{{$v->id}}">{{$v->names}}</option>
                                    @endforeach   
                                    </select>
                                </div>
                            </div>

                            <div class="form-group draggable">
                                <label class="col-sm-3 control-label">菜单类型</label>
                                <div class="col-sm-9">
                                    <input type="text" name="type" class="form-control" placeholder="请输入菜单类型">
                                    <span class="help-block m-b-none"></span>
                                </div>
                            </div>
                            <div class="form-group draggable">
                                <label class="col-sm-3 control-label">关键字</label>
                                <div class="col-sm-9">
                                    <input type="text" name="key" class="form-control" placeholder="请输入关键字">
                                    <span class="help-block m-b-none"></span>
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
       $(function(){
           $('.names').blur(function(){
               var names=$(this).val();
                    console.log(names.length);
                if(names.length>4){
                   alert('菜单名称不可以超过四个字');
                   return false;
                }
           });
       })
    </script>

