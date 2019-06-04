<?php
namespace App\Model;
use App\Model\OpenId;
use App\Model\Menus;
class wechatpublic 
{
    //天气
	public function isWeather($content,$is)
	{
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
						$yes.="\n\r".$v;
					}
				}
			$msg=$yes;
    	}
		return $msg;
	}
	//关注	open id 
	public function access($xmlObj,$is,$Ticket="")
	{
		$msg="";
		$access=cache('access_token');
		if(!$access){
			$ass=file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx1f9161f806626795&secret=e145f36cee7a9f4ffc77305520fe4a89");
			$ass=json_decode($ass,true);
			$access=$ass['access_token'];
			cache(['access_token'=>$access],60*2);
		}
		$access=cache('access_token');
		$UserId=$xmlObj->FromUserName;
		$Info=file_get_contents("https://api.weixin.qq.com/cgi-bin/user/info?access_token={$access}&openid={$UserId}&lang=zh_CN");
		$Info=json_decode($Info,true);
		if($is=="FocusOn"){
			$data=OpenId::where(['openid'=>$Info['openid']])->count();
			if($data==1){
				$open=OpenId::where(['openid'=>$Info['openid']])->update(['is_focus'=>1,'ticket'=>$Ticket]);
			}else{
				$data=OrCode::where(['ticket'=>$Ticket])->get()->toArray();
				$opens=OpenId::where(['ticket'=>$Ticket,'is_focus'=>1])->get()->count();
				$dd=OrCode::where(['ticket'=>$Ticket])->update(['number'=>$opens]);
				$region=$Info['city'].$Info['province'].$Info['country'];
				$conditions=[
					'nickname'=>$Info['nickname'],
					'sex'=>$Info['sex'],
					'openid'=>$Info['openid'],
					'region'=>$region,
					'headimgurl'=>$Info['headimgurl'],
					'subscribe_time'=>$Info['subscribe_time'],
					'is_focus'=>1,
					'ticket'=>$Ticket,
				];
				$open=OpenId::insert($conditions);
			}
			
			// $open=OpenId::insert();
			if($Info['sex']==1){
				$sex="男士";
			}else{
				$sex="女士";
			}
			$msg=$Info['nickname'].$sex."您好 欢迎订阅关注";
		}else if($is=="Cancel"){
			$get=OpenId::where(['openid'=>$Info['openid']])->get();
			$data=OpenId::where(['openid'=>$Info['openid']])->update(['is_focus'=>2]);
			if($get[0]['ticket']!=""){
				$res=OrCode::where(['ticket'=>$get[0]['ticket']])->get()->toArray();
				OrCode::where(['ticket'=>$get[0]['ticket']])->update(['number'=>$res[0]['number']-1]);
			}
			if($data){
				return true;
			}else{
				return false;
			}
		}
		
		return $msg;
	}
	//随机回复人名
	public function getRandomStr($len, $special=true)
	{
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
	//回复文本
	public function XmlText($xmlObj,$msg)
	{
		$Xml= "<xml>
				<ToUserName><![CDATA[".$xmlObj->FromUserName."]]></ToUserName>
				<FromUserName><![CDATA[".$xmlObj->ToUserName."]]></FromUserName>
				<CreateTime>".time()."</CreateTime>
				<MsgType><![CDATA[text]]></MsgType>
				<Content><![CDATA[".$msg."]]></Content>
			</xml>"; 
			return $Xml;
	}
	//access_token
	public function access_token()
	{
		//获取access_token
		$access=cache('access_token');
		if(!$access){
			$ass=file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx1f9161f806626795&secret=e145f36cee7a9f4ffc77305520fe4a89");
			$ass=json_decode($ass,true);
			$access=$ass['access_token'];
			cache(['access_token'=>$access],60*2);
		}
		$access=cache('access_token');
		return $access;
	}
	//上传图片的CURL
	public function curlPost($url,$post_data)
	{
		//初始化
		$curl = curl_init();
		//设置抓取的url
		curl_setopt($curl, CURLOPT_URL,$url);
		//设置获取的信息以文件流的形式返回，而不是直接输出。
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		//设置post方式提交
		curl_setopt($curl, CURLOPT_POST, 1);
		//设置post数据
		curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);//这个是重点。
		//执行命令
		$data = curl_exec($curl);
		//关闭URL请求
		curl_close($curl);
		//显示获得的数据
		return $data;
	}
	//父级分类
	public function ParentId()
	{
		$data=Menus::where(['parent_id'=>0])->get()->toArray();
		return $data;
	}
	//首页天气处理
	public function nowapi_call($a_parm)
	{
		if(!is_array($a_parm)){
			return false;
		}
		//combinations
		$a_parm['format']=empty($a_parm['format'])?'json':$a_parm['format'];
		$apiurl=empty($a_parm['apiurl'])?'http://api.k780.com/?':$a_parm['apiurl'].'/?';
		unset($a_parm['apiurl']);
		foreach($a_parm as $k=>$v){
			$apiurl.=$k.'='.$v.'&';
		}
		$apiurl=substr($apiurl,0,-1);
		if(!$callapi=file_get_contents($apiurl)){
			return false;
		}
		//format
		if($a_parm['format']=='base64'){
			$a_cdata=unserialize(base64_decode($callapi));
		}elseif($a_parm['format']=='json'){
			if(!$a_cdata=json_decode($callapi,true)){
				return false;
			}
		}else{
			return false;
		}
		//array
		if($a_cdata['success']!='1'){
			echo $a_cdata['msgid'].' '.$a_cdata['msg'];
			return false;
		}
		return $a_cdata['result'];
	}
	//更新标签列表
	public function Label()
	{	
		$access_token=$this->access_token();
		$ab="https://api.weixin.qq.com/cgi-bin/tags/get?access_token={$access_token}";
		$res=\file_get_contents($ab);
		$json=json_decode($res,true);
		foreach($json['tags'] as $k => $v){
			$Lable=Label::where(['tagid'=>$v['id']])->update(['name'=>$v['name'],'count'=>$v['count']]);
		}
		return true;
	}
	//无限极分类
	public function CaTe($data,$parent_id=0,$html=">",$level=0)
	{
		static $arr=[];
		foreach($data as $v){
			if($parent_id==$v['parent_id']){
				$v['level']=$level;
				$arr[]=$v;
				$this->CaTe($data,$v['id'],$html=">",$level+1);
				// dd($arr);
			}
		}
		return $arr; 
	}
	//网页授权获取openid
	public function getOpenid()
	{
		$openid = session('openid');
        if(!empty($openid)){
            //如果有openid 正常返回
            return $openid;
		}
		 //没有 再去访问微信授权流程 获取openid
		 $SERVER_NAME = $_SERVER['HTTP_HOST'];  //获取域名
		 $REQUEST_URI = $_SERVER['REQUEST_URI']; //获取参数
		 $redirect_uri = urlencode('http://' . $SERVER_NAME . $REQUEST_URI);  //动态组装一个回调地址
		 $code = request('code');
		 if (! $code) {
            // 网页授权当scope=snsapi_userinfo时才会提示是否授权应用
            $autourl = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx1f9161f806626795&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_base&state=123#wechat_redirect";
            //echo $autourl;die;
            header("location:$autourl");
        } else {
            // 获取openid
            $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=wx1f9161f806626795&secret=e145f36cee7a9f4ffc77305520fe4a89&code=$code&grant_type=authorization_code";
            $row = file_get_contents($url);
            $row = json_decode($row,true);
            $openid = $row['openid'];
            //获取到openid之后 存session
            session(['openid'=>$openid]);
            return $openid;
        }
	}
	public static function http_post_xml($url,$xml)
    {
        $header[]="Content-type:text/xml";
        //初始化
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL,$url);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl,CURLOPT_HTTPHEADER,$header);
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, 1);
        //设置post数据
        curl_setopt($curl, CURLOPT_POSTFIELDS, $xml);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);//这个是重点。
        //执行命令
        $data = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        //显示获得的数据
        return $data;
    }
}


?>