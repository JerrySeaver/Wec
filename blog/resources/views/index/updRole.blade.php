@extends('public.header')
<body class="gray-bg">
    <div class="wrapper wrapper-content">

        <div class="row">
            <div class="col-sm-5">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>添加管理员</h5>
                    </div>
                    <div class="ibox-content">
                        <form role="form" class="form-horizontal m-t" method="post" action="/doUpdRole">
                            <div class="form-group draggable">
                                <label class="col-sm-3 control-label">管理员名称</label>
                                <div class="col-sm-9">
                                    <input type="text" name="name" value="{{$data['name']}}" class="form-control names">
                                    <span class="help-block m-b-none"></span>
                                </div>
                            </div>

                            <div class="form-group draggable">
                                <label class="col-sm-3 control-label">选择权限</label>
                                <div class="col-sm-9">
                                <input type="hidden" name="r_id" value="{{$data['r_id']}}">
                                    <table border="1">
                                    @foreach($static as $key=>$val)
                                        <tr>
                                             @foreach($val as $k=>$v)
                                                <td>
                                                    <input type="checkbox" name="id[]" @if( in_array($v['id'],$id) ) checked @endif value="{{$v['id']}}">
                                                    {{$v['name']}}
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                    </table>
                                </div>
                            </div>
                           
                            <div class="form-group draggable">
                                <label class="col-sm-3 control-label">选择绑定的用户</label>
                                <div class="col-sm-9">
                                    <select class="form-control" name="user_id">
                                        <option>请选择</option>
                                    @foreach($login as $key => $val)
                                        <option value="{{$val->id}}" @if( $val->id == $data['user_id'] ) selected @endif >{{$val->username}}</option>
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

