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
                        <form role="form" class="form-horizontal m-t" method="post" action="/doAddTheTest">
                            <div class="form-group draggable">
                                <label class="col-sm-3 control-label">试题名称</label>
                                <div class="col-sm-9">
                                    <input type="text" name="t_name" class="form-control names" placeholder="请输入试题名称">
                                    <span class="help-block m-b-none"></span>
                                </div>
                            </div>

                            <div class="form-group draggable">
                                <label class="col-sm-3 control-label">答案A</label>
                                <div class="col-sm-9">
                                    <input type="text" name="answer_a" class="form-control names" placeholder="请输入答案A">
                                    <span class="help-block m-b-none"></span>
                                </div>
                            </div>

                            <div class="form-group draggable">
                                <label class="col-sm-3 control-label">答案B</label>
                                <div class="col-sm-9">
                                    <input type="text" name="answer_b" class="form-control names" placeholder="请输入答案B">
                                    <span class="help-block m-b-none"></span>
                                </div>
                            </div>

                            <div class="form-group draggable">
                                <label class="col-sm-3 control-label">正确答案</label>
                                <div class="col-sm-9">
                                    <input type="text" name="correct" class="form-control" placeholder="请输入正确答案">
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
    </script>

