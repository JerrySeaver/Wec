<?php
    //接收用户发送XML数据 POST方式
    $xml=file_get_contents("php://input");
    //把XML数据记录到本地文件里.。
    file_put_contents('1.txt',$xml);
    //xml专为对象
    $xmlObj=simplexml_load_string($xml);
    //得到用户留言
    $msg="sorry, 本豹看不懂";
    $content=trim($xmlObj->Content);
    $type=$xmlObj->MsgType;
    $chars = array(
        "周红包", "王鑫飞", "刘博", "叶云阳", "苏杭", "芹为非", "马吉檬", "吴炳坤", "王建", "王峰", "谷锐冻",
        "吕祥宇", "蔺鑫宇", "张家豪", "张晓乐",
    );
    //回复文本
    if($type=="text"){
        if($content=="1"){
            $msg= implode(',',$chars);
        }elseif($content=="2"){
            function getRandomStr($len, $special=true){
                if($special){
                    $chars = array(
                        "周红包", "王鑫飞", "刘博", "叶云阳", "苏杭", "芹为非", "马吉檬", "吴炳坤", "王建", "王峰", "谷锐冻",
                        "吕祥宇", "蔺鑫宇", "张家豪", "张晓乐",
                    );
                    $chars=array_merge($chars,array(
                        "周红包", "王鑫飞", "刘博", "叶云阳", "苏杭", "芹为非", "马吉檬", "吴炳坤", "王建", "王峰", "谷锐冻",
                    "吕祥宇", "蔺鑫宇", "张家豪", "张晓乐",
                    ));
                }
                $charsLen = count($chars) - 1;
                //打乱数组顺序
                shuffle($chars);                            
                $str = '';
                for($i=0; $i<$len; $i++){
                    //随机取出一位
                    $str .= $chars[mt_rand(0, $charsLen)];    
                }
                return $str;
            }
            $msg=getRandomStr(1);
        }else{
            $is=mb_substr($content,-2); 
            if($content=="天气"){
                echo "<xml>
                <ToUserName><![CDATA[".$xmlObj->FromUserName."]]></ToUserName>
                <FromUserName><![CDATA[".$xmlObj->ToUserName."]]></FromUserName>
                <CreateTime>".time()."</CreateTime>
                <MsgType><![CDATA[text]]></MsgType>
                <Content><![CDATA[请输入城市天气]]></Content>
            </xml>";  
            }
            if($is=="天气"){
                //获取天气
                $cit=mb_substr($content,0,mb_strlen($content)-mb_strlen($is));
                $url="http://wthrcdn.etouch.cn/weather_mini?city=".$cit; 
                //调用接口获得天气数据
                $str = file_get_contents($url);
                //这一步很重要 解压
                $result= gzdecode($str);   
                //end
                 $students= json_decode($result, true);
              
               //获取天气
               $tianqi=$students;
               //清除用不到的元素
               unset($tianqi['desc']);
               unset($tianqi['status']);
               unset($tianqi['data']['city']);
               array_pop($tianqi['data']);
               array_pop($tianqi['data']);
               //处理数组
               foreach ($tianqi as $key => $val) {
                    foreach ($val as $k => $v) {
                        $array["$k"]=$v;
                    }
               }
               //获取昨天天气
               $yesterday=$array['yesterday'];
               //获取今天以及之后的天气
               $forecast=$array['forecast'];
               $yes="昨天{$cit}天气是";
               //删除风力
               unset($yesterday['fl']);
               foreach ($yesterday as $key => $value) {
                   //拼接昨天的天气
                       $yes.=$value.",";
               }
               $yes.="今天以及以后的天气为：";
               foreach ($forecast as $key => $value) {
                    foreach ($value as $k => $v) {
                        //拼接今天以及明天的天气
                        if($forecast[$key]['fengli']==$v){
                            $a=1;
                        }else{
                            $yes.=$v.',';
                        }
                    }
               }
               $msg=$yes;
            }
            
        }
        echo "<xml>
                <ToUserName><![CDATA[".$xmlObj->FromUserName."]]></ToUserName>
                <FromUserName><![CDATA[".$xmlObj->ToUserName."]]></FromUserName>
                <CreateTime>".time()."</CreateTime>
                <MsgType><![CDATA[text]]></MsgType>
                <Content><![CDATA[".$msg."]]></Content>
            </xml>";
    }
    //回复图片
    if($type=="image"){
        echo "<xml>
        <ToUserName><![CDATA[".$xmlObj->FromUserName."]]></ToUserName>
        <FromUserName><![CDATA[".$xmlObj->ToUserName."]]></FromUserName>
        <CreateTime>".time()."</CreateTime>
        <MsgType><![CDATA[image]]></MsgType>
        <Image>
          <MediaId><![CDATA[obb1xfaBIMyvgLVQAtTT5naTMqQ1a355dfO_AykyIcilNt4PBZ2RFki--IQfdrJR]]></MediaId>
        </Image>
      </xml>";
    }
    //关注
    if($xmlObj->MsgType=="event" && $xmlObj->Event=="subscribe"){
        
        //获取access_token
        $ass=file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx1f9161f806626795&secret=e145f36cee7a9f4ffc77305520fe4a89");
        $ass=json_decode($ass,true);
        $access=$ass['access_token'];
        $UserId=$xmlObj->FromUserName;
        $Info=file_get_contents("https://api.weixin.qq.com/cgi-bin/user/info?access_token={$access}&openid={$UserId}&lang=zh_CN");
        $Info=json_decode($Info,true);
        if($Info['sex']==1){
            $sex="男士";
        }else{
            $sex="女士";
        }
        $msg=$Info['nickname'].$sex."您好 欢迎订阅关注";
        echo "<xml>
                <ToUserName><![CDATA[".$xmlObj->FromUserName."]]></ToUserName>
                <FromUserName><![CDATA[".$xmlObj->ToUserName."]]></FromUserName>
                <CreateTime>".time()."</CreateTime>
                <MsgType><![CDATA[text]]></MsgType>
                <Content><![CDATA[".$msg."]]></Content>
            </xml>";
    }
    //取消关注
    if($xmlObj->MsgType=="event" && $xmlObj->Event=="unsubscribe"){
        $msg="确定要取消关注订阅吗";
        echo "<xml>
                <ToUserName><![CDATA[".$xmlObj->FromUserName."]]></ToUserName>
                <FromUserName><![CDATA[".$xmlObj->ToUserName."]]></FromUserName>
                <CreateTime>".time()."</CreateTime>
                <MsgType><![CDATA[text]]></MsgType>
                <Content><![CDATA[".$msg."]]></Content>
            </xml>";
    }
    if($type=='voice'){
        $msg="对不起，暂时不支持语音";
        echo "<xml>
                <ToUserName><![CDATA[".$xmlObj->FromUserName."]]></ToUserName>
                <FromUserName><![CDATA[".$xmlObj->ToUserName."]]></FromUserName>
                <CreateTime>".time()."</CreateTime>
                <MsgType><![CDATA[text]]></MsgType>
                <Content><![CDATA[".$msg."]]></Content>
            </xml>";
            
    }
    if($type=='video'){
        $msg="对不起，暂时不支持视频识别";
        echo "<xml>
                <ToUserName><![CDATA[".$xmlObj->FromUserName."]]></ToUserName>
                <FromUserName><![CDATA[".$xmlObj->ToUserName."]]></FromUserName>
                <CreateTime>".time()."</CreateTime>
                <MsgType><![CDATA[text]]></MsgType>
                <Content><![CDATA[".$msg."]]></Content>
            </xml>";
    }
    if($type=='music'){
        $msg="歌曲很好听！";
        echo "<xml>
                <ToUserName><![CDATA[".$xmlObj->FromUserName."]]></ToUserName>
                <FromUserName><![CDATA[".$xmlObj->ToUserName."]]></FromUserName>
                <CreateTime>".time()."</CreateTime>
                <MsgType><![CDATA[text]]></MsgType>
                <Content><![CDATA[".$msg."]]></Content>
            </xml>";
    }
    
?>