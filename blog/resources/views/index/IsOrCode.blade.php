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
                                    <th>推广ID</th>
                                    <th>推广名称</th>
                                    <th>关键字</th>
                                    <th>关注人数</th>
                                    <th>二维码展示</th>
                                    <th>操作</th>
                                </tr>
                            </thead>
                            <tfoot>
                            @foreach($data as $k=>$v)
                                <tr>
                                    <th>{{$v->id}}</th>
                                    <th>{{$v->typename}}</th>
                                    <th>{{$v->keyword}}</th>
                                    <th>{{$v->number}}</th>
                                    <th>
                                        <img class="image" src="{{$v->img}}" height="70">
                                    </th>
                                    <th>
                                      <a href="/OrCodeDel?id={{$v->id}}">删除</a>
                                      |
                                      <a href="/OrCodeUpd?id={{$v->id}}">编辑</a>
                                    </th>
                                </tr>
                            @endforeach
                            </tfoot>     
                    </div>
                           <a href="/statistical">
                                <button class="btn btn-sm btn-primary" type="submit" class="image">数据统计</button>
                            </a>  
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
    <div class="container">
    @foreach ($data as $user)
    {{ $user->name }}
    @endforeach
    </div>
    {{ $data->links()}}
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
    
 