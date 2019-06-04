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
                        <form role="form" class="form-horizontal m-t" method="post" action="/DoInFiles" enctype='multipart/form-data'>

                            <div class="form-group draggable">
                                <label class="col-sm-3 control-label">素材名称</label>
                                <div class="col-sm-9">
                                    <input type="text" name="names" class="form-control names" placeholder="请输入菜单名称">
                                    <span class="help-block m-b-none"></span>
                                </div>
                            </div>

                            <div class="form-group draggable">
                                <label class="col-sm-3 control-label">选择素材</label>
                                <div class="col-sm-9">
                                    <input type="file" name="url" class="form-control">
                                </div>
                            </div>

                            <div class="form-group draggable">
                                <label class="col-sm-3 control-label">素材类型</label>
                                <div class="col-sm-9">
                                    <select class="form-control" name="type">
                                    <option value="voice">语音</option> 
                                    <option value="video">音乐</option>  
                                    <option value="music">视频</option> 
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

