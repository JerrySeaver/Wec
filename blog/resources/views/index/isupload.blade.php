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
                                    <th>图片ID</th>
                                    <th>图片名称</th>
                                    <th>图片展示</th>
                                    <th>图片MedialId</th>
                                    <th>操作</th>
                                </tr>
                            </thead>
                            <tfoot>
                            @foreach($data as $k=>$v)
                                <tr>
                                    <th>{{$v->img_id}}</th>
                                    <th>{{$v->img_name}}</th>
                                    <th>
                                    @if($v->type=="video")
                                        <embed src="{{$v->img_url}}" height="60"/>
                                    @elseif($v->type=="image")
                                        <img src="{{$v->img_url}}" height="60"/>  
                                    @elseif($v->type=="voice")
                                    <audio src="{{$v->img_url}}"" controls muted loop></audio>
                                    @elseif($v->type=="music")
                                        <audio src="{{$v->img_url}}"></audio>
                                    @elseif($v->type=="thumb")
                                        <img src="{{$v->img_url}}" height="30"/>  
                                    @endif
                                    </th>
                                    <th>{{$v->media_id}}</th>
                                    <th>
                                      <a href="/imgdel?img_id={{$v->img_id}}">删除</a>
                                      |
                                      <a href="/upd?img_id={{$v->img_id}}">编辑</a>
                                    </th>
                                </tr>
                            @endforeach
                            </tfoot> 
                            <form action="" method="get">
                                <input type="hidden" name="type" value="image" class="types">
                                <button class="btn btn-sm btn-primary" type="submit" class="image">图片</button>
                            </form>  
                            <form action="" method="get">
                                <input type="hidden" name="type" value="voice" class="types">
                                <button class="btn btn-sm btn-success" type="submit"  class="voice">语音</button>
                            </form>  
                            <form action="" method="get">
                                <input type="hidden" name="type" value="video" class="types">
                                <button class="btn btn-sm btn-info" type="submit"  class="video">视频</button>
                            </form>  
                            <form action="" method="get">
                                <input type="hidden" name="type" value="music" class="types">
                                <button class="btn btn-sm btn-danger" type="submit"  class="music">音乐</button>
                            </form>  
                            <form action="" method="get">
                                <input type="hidden" name="type" value="thumb" class="types">
                                <button class="btn btn-sm btn-warning" type="submit"  class="thumb">缩略图</button>
                            </form>      
                    </div>
                            
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="container">
    @foreach ($data as $user)
    {{ $user->name }}
    @endforeach
    </div>
    {{ $data->appends($type)->links()}}
    <script src="/hAdmin/js/jquery.min.js?v=2.1.4"></script>
    <script>
        $(function(){
            $('.image').click(function(){
                alert(123);
                return false;
            })
        })
    </script>
    
 