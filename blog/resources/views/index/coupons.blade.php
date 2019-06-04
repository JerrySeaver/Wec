@extends('public.header')
<body class="gray-bg">
    <div class="wrapper wrapper-content">

        <div class="row">
            <div class="col-sm-5">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>添加优惠券</h5>
                    </div>
                    <div class="ibox-content">
                        <form role="form" class="form-horizontal m-t" method="post" action="/addCoupons">
                            <div class="form-group draggable">
                                <label class="col-sm-3 control-label"></label>
                                <div class="col-sm-9">
                                    <input type="text" name="c_name" class="form-control names" placeholder="优惠券名称">
                                    <span class="help-block m-b-none"></span>
                                </div>
                            </div>

                            <div class="form-group draggable">
                                <label class="col-sm-3 control-label"></label>
                                <div class="col-sm-9">
                                    <input type="text" name="number" class="form-control names" placeholder="优惠券数量">
                                    <span class="help-block m-b-none"></span>
                                </div>
                            </div>

                            <div class="form-group draggable">
                                <label class="col-sm-3 control-label"></label>
                                <div class="col-sm-9">
                                    <input type="text" name="conditions" class="form-control names" placeholder="优惠券使用的条件【元】">
                                    <span class="help-block m-b-none"></span>
                                </div>
                            </div>

                            <div class="form-group draggable">
                                <label class="col-sm-3 control-label"></label>
                                <div class="col-sm-9">
                                    <input type="text" name="amount" class="form-control" placeholder="优惠券减免的金额【元】">
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

