@extends('public.header')
<body class="gray-bg">
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">
            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-content">
                        <table class="table table-striped table-bordered table-hover " id="editable">
                            <thead>
                                @foreach($data as $k => $v)
                                <tr>
                                    <th>
                                        <input type="checkbox" name="openid[]" value="{{$v->openid}}">
                                    </th>
                                    <th>{{$v->nickname}}</th>
                                    <th>{{$v->openid}}</th>
                                </tr>   
                                @endforeach
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

 