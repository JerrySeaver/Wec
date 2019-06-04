@extends('public.header')
<body class="gray-bg">
    <div class="wrapper wrapper-content">

        <div class="row">
            <div class="col-sm-5">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>修改推广</h5>
                    </div>
                    <div class="ibox-content">
                        <form role="form" class="form-horizontal m-t" method="post" action="/DoQrCodeUpd">
                        <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">推广名称</label>
                                    <div class="col-sm-9">
                                        <input type="text" name="typename" value="{{$data['typename']}}" class="form-control" placeholder="请输入推广名称"> <span class="help-block m-b-none"></span>
                                <input type="hidden" name="id" value="{{$data['id']}}">
                                   </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-12 col-sm-offset-3">
                                        <button class="btn btn-primary" type="submit">修改推广</button>
                                        <button class="btn btn-white" type="submit">取消</button>
                                    </div>
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

