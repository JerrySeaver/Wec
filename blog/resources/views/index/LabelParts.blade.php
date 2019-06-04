@extends('public.header')
<body class="gray-bg">
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-content">
                        <table class="table table-striped table-bordered table-hover " id="editable">
                            <thead>
                                <select name="tagid" >
                                    <option value="">请选择分组</option>
                                    @foreach($data as $k => $v)
                                        <option value="{{$v->tagid}}">{{$v->name}}</option>
                                    @endforeach
                                </select>
                            </thead>
                            <tfoot>
                            </tfoot> 
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
    <!-- 全局js -->
    <script src="js/jquery.min.js?v=2.1.4"></script>
    <script src="js/bootstrap.min.js?v=3.3.6"></script>

 