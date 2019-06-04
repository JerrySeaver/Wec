@extends('public.header')
<body class="gray-bg">

    <div class="wrapper wrapper-content animated fadeInRight">
                            
        <div class="row">
            <div class="col-sm-12">
            
                <div class="ibox float-e-margins">
                
                    <div class="ibox-content">
   
                        <table class="table table-striped table-bordered table-hover " id="editable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>测试题内容</th>
                                    <th>答案A</th>
                                    <th>答案b</th>
                                    <th>正确答案</th>
                                </tr>
                            </thead>
                            <tfoot>
                            @foreach($data as $k=>$v)
                                <tr>
                                    <th>{{$v->id}}</th>
                                    <th>{{$v->t_name}}</th>
                                    <th>{{$v->answer_a}}</th>
                                    <th>{{$v->answer_b}}</th>
                                    <th>{{$v->correct}}</th>
                                </tr>
                            @endforeach
                            </tfoot>     
                    </div>
                           
                        </table>
                            <div class="bg_div" style="display:none;background:#ccc;width:100%;height:100%;position:absolute;top:0;left:0;opacity:0.8;text-align:center;padding-top:10%">
                                <div class="close_div" style="padding-left:20%">
                                    <a>关闭</a>
                                </div>
                                <img src="" style="width:300px">
                            </div>
                            
                    </div>
                </div>
            </div>
            <div id="fade" class="black_overlay">
            </div>
                            
    </div>
    </div>
    <script src="/hAdmin/js/jquery.min.js?v=2.1.4"></script>
    <script>
        $(function(){
            $('.image').click(function(){
                var src=$(this).attr('src');
                console.log($('.bg_div img'));
                $('.bg_div').find('img').attr('src',src);
                $('.bg_div').show();
            });
            $('.close_div').click(function(){
                $('.bg_div').hide();
            })
        })
    </script>
    
 