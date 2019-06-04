@extends('public.header')
<body class="gray-bg">
 <form method="post" action="/doupd" enctype='multipart/form-data'>
    <div class="col-md-12">
<div class="form-group">  </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">图片名称</label>
        <div class="col-sm-9">
            <input type="text" name="img_name" class="form-control" value="{{$res['img_name']}}"> 
@csrf
        </div>
    </div>

        <label class="col-sm-3 control-label">选择素材</label>
        <div class="col-sm-9">
            <select class="form-control" name="material">
                    <option value="voice">语音</option> 
                    <option value="video">视频</option>  
                    <option value="music">音乐</option> 
                    <option value="thumb">缩略图</option>
                    <option value="temporary">图片临时素材</option>
                    <option value="always">图片永久素材</option>
            </select>
        </div>

    <div class="form-group">
        <label class="col-sm-3 control-label">上传素材</label>
        <div class="col-sm-9">
            <input type="file" name="img_url" class="form-control">
        </div>
    </div>

    <div class="form-group">
        <label class="col-sm-3 control-label">文件</label>
        <div class="col-sm-9">
            @if($res['type']=="video")
                <embed src="{{$res['img_url']}}" height="60"/>
            @elseif($res['type']=="image")
                <img src="{{$res['img_url']}}" height="60"/>  
            @elseif($res['type']=="voice")
                <audio src="{{$res['img_url']}}"></audio>
            @elseif($res['type']=="music")
                <audio src="{{$res['img_url']}}"></audio>
            @elseif($res['type']=="thumb")
                <img src="{{$res['img_url']}}" height="30"/>  
            @endif
            <input type="hidden" name="img_id" value="{{$res['img_id']}}">
        </div>
        
    </div>
    
    <br/>
    <hr/>
    <div class="form-group">
        <div class="col-sm-12 col-sm-offset-3">
            <button class="btn btn-primary">保存内容</button>
            <button class="btn btn-white" type="submit">取消</button>
        </div>
    </div>
</div>  
</form> 
</body>
@extends('public.foot')