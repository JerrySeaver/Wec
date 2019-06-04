@section('title','主页')
    @section('header')
<body class="gray-bg">
    <div class="wrapper wrapper-content">

        <div class="row">
            <div class="col-sm-5">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>添加菜单</h5>
                    </div>
                    <div class="ibox-content">
                        <form role="form" class="form-horizontal m-t" method="post" action="/AMass">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">内容</label>
                                <div class="col-sm-9">
                                    <input type="text" name="content" class="form-control" placeholder="请输入要群发的内容">
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">群发人群</label>
                                <div class="col-sm-9">
                                    <label class="radio-inline">
                                        <input type="radio" checked="" value="1" class="radio" id="optionsRadios1" name="radio">全部
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" value="0" class="ra         dio" id="optionsRadios2" name="radio">部分
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" value="2" class="radio" id="optionsRadios3" name="radio">选择标签
                                    </label>
                                </div>
                               
                            </div>
                             <div class="additional"></div>
                            <div class="form-group">
                                <div class="col-sm-12 col-sm-offset-3">
                                    <button class="btn btn-primary" type="submit">发送</button>
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
        $('#optionsRadios1').click(function(){
               $('.additional').empty();
           });
        $('#optionsRadios2').click(function(){
               $('.additional').empty();
           })
        $('#optionsRadios3').click(function(){
               $('.additional').empty();
           })
        $('#optionsRadios2').click(function(){
            $('.additional').empty();
               var radio=1;
                $.post(
                    '/PartAMass', 
                    {radio:radio}, 
                    function(res){
                        $('.additional').append(res);
                });
           });
           $('#optionsRadios3').click(function(){
            $('.additional').empty();
               var radio=2;
                $.post(
                    '/PartAMass', 
                    {radio:radio}, 
                    function(res){
                        $('.additional').append(res);
                });
           })
           
           
       })
    </script>

