<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Wxshop\wechat;
use App\Model\wechatpublic;
use App\Model\WechatUpload;
use App\Model\Login;
use App\Model\Menus;
use App\Model\OrCodeLogin;
use App\Model\OpenId;
use App\Model\Label;
use App\Model\Material;
use App\Model\OrCode;
use App\Model\Role;
use App\Model\Right;
use App\Model\Premiss;
use App\Model\Love as LoveModel;
use App\Model\Count;
use CURLFile;
use App\Model\Notice;
use Illuminate\Support\Facades\Cache;
use App\Model\Action;
use App\Model\Result;
use App\Model\TheTest;
use App\Model\Vouchers;
use App\Model\Coupons;
use App\Http\Controllers\LoveController as Love;
class WechatController extends wechatpublic
{ 
	//微信回复
	public function index()
	{
		//接收用户发送XML数据 POST方式
		$xml=file_get_contents("php://input");
		//把XML数据记录到本地文件里.。
		file_put_contents('1.txt',$xml);
		//xml专为对象
		$xmlObj=simplexml_load_string($xml);
		//得到用户留言
		$user=$xmlObj->FromUserName;
		$keywords=$xmlObj->Content;
		//没有该关键字则图灵机器人回复
		$url ="http://openapi.tuling123.com/openapi/api/v2";//接口地址
		$msg = wechat::rbot($keywords,$url);
		$content=trim($xmlObj->Content);
		$type=$xmlObj->MsgType;
		$chars = array(
			"周红包", "王鑫飞", "刘博", "叶云阳", "苏杭", "芹为非", "马吉檬", "吴炳坤", "王建", "王峰", "谷锐冻",
			"吕祥宇", "蔺鑫宇", "张家豪", "张晓乐",
		);
		// dd($xmlObj->EventKey=="Login");
		//回复文本
		if($type=="event" && $xmlObj->EventKey=="ToView"){
			$Love=new Love;
			$data=$Love->ToView($xmlObj);
			if($data){
				$msg="请输入要查询人的名字";
				$Xml=$this->XmlText($xmlObj,$msg); 
				echo $Xml;
			}
		}
		//点击表白
		if($type=="event" && $xmlObj->EventKey=="SendLove"){
			$Love=new Love;
			$data=$Love->SendLove($xmlObj);
			if($data){
				$msg="请输入要表白的姓名";
				$Xml=$this->XmlText($xmlObj,$msg); 
				echo $Xml;
			}
		}
		//点击答题
		if($type=="event" && $xmlObj->EventKey=="Answer"){
			$data=$this->Answer($xmlObj);
			// dd($data);
		}
		//点击我的成绩
		if($type=="event" && $xmlObj->EventKey=="Results"){
			$data=$this->Results($xmlObj);
			// dd($data);
		}
		$is=mb_substr($content,-2);
		//输入发送姓名之后
		if($type=="text"){
			$Action=Action::where(['open_id'=>$user])->orderBy('time','desc')->get()->toArray();
			$res=$Action[0];
			if($res['action']=="SendLove" && time()-$res['time']<60){
				$LoveModel=LoveModel::insertGetId(['name'=>$content]);
				$Action=Action::insert(['open_id'=>$user,'action'=>'EnterName','time'=>time()]);
				cache(['SendLoveId'.$user=>$LoveModel],1*60);
				if($LoveModel){ 
					$msg="请输入表白内容";
					$Xml=$this->XmlText($xmlObj,$msg); 
					echo $Xml;	
				}  
			}
			//输入发送表白内容之后
			if($res['action']=="EnterName" && time()-$res['time']<60){
				$id=cache("SendLoveId{$user}");
				$a=["id"=>$id];
				$LoveModel=LoveModel::where($a)->update(['content'=>$content]);
				if($LoveModel){
					$msg="表白成功";
					$Xml=$this->XmlText($xmlObj,$msg); 
					echo $Xml;	
				}
			}
			//点击查看表白之后
			if($res['action']=="ToView" && time()-$res['time']<60){
				$count=LoveModel::where(['name'=>$content])->count();
				$data=LoveModel::where(['name'=>$content])->get()->ToArray();
				$msg="一共有{$count}人给TA表白\n\r";
				foreach ($data as $key => $val) {
					$a=$key+1;
					$msg.= "第".$a."个:".$val['name']."!".$val['content']."\n\r";
				}
				$Xml=$this->XmlText($xmlObj,$msg); 
				echo $Xml;
			}
			//答题
			if($res['action']=="Answer" && time()-$res['time']<120){
				$this->questions($xmlObj);
			}
			if($content=="1"){
				$msg= implode(',',$chars);
			}elseif($content=="2"){
				$msg=$this->getRandomStr(1);
			}elseif($content=="头像"){
				$msg=$this-> XmlImage($xmlObj);
				echo $msg;
			}elseif($content=="天气"){
				$msg="请输入城市天气";
				$Xml=$this->XmlText($xmlObj,$msg);
			}elseif($is=="天气"){
				$msg=$this-> isWeather($content,$is);
			}
				$Xml=$this->XmlText($xmlObj,$msg); 
				echo $Xml;
		}
		//回复图片
		if($type=="image"){
			$msg=$this->XmlImage($xmlObj);
			echo $msg;
		}
		//扫码登陆
		if($type=="event" && $xmlObj->EventKey=="Login"){
			$data=Login::where(['openid'=>$xmlObj->FromUserName])->get()->toArray();
			if(!empty($data)){
				$cache=cache(['login'=>$data[0]],60*60*24);
				$cache=cache(['islogin'=>$data[0]],3);
			}else{
				$msg="没有找到此账号";
				$Xml=$this->XmlText($xmlObj,$msg); 
				echo $Xml;
			}
		}
		//关注
		if($type=="event" && $xmlObj->Event=="subscribe"){
			if(empty($xmlObj->EventKey)){
				$Ticket=$xmlObj->Ticket;
				$data=OrCode::where(['ticket'=>$Ticket])->get()->toArray();
				OrCode::where(['ticket'=>$Ticket])->update(['number'=>$data[0]['number']+1]);
				$msg=$this->access($xmlObj,'FocusOn',$Ticket);
				$Xml=$this->XmlText($xmlObj,$msg); 
				echo $Xml;
			}else{
				$msg=$this->access($xmlObj,'FocusOn');
				$Xml=$this->XmlText($xmlObj,$msg); 
				echo $Xml;
			}
			
		}
		//取消关注
		if($xmlObj->MsgType=="event" && $xmlObj->Event=="unsubscribe"){
			$is=$this->access($xmlObj,'Cancel');
			if($is){
				$msg="确定要取消关注订阅吗";
				$Xml=$this->XmlText($xmlObj,$msg); 
				echo $Xml;
			}
		}
		//语音
		if($type=='voice'){
			$msg="对不起，暂时不支持语音";
			$Xml=$this->XmlText($xmlObj,$msg); 
			echo $Xml;	
		}
		//视频
		if($type=='video'){
			$msg="对不起，暂时不支持视频识别";
			$Xml=$this->XmlText($xmlObj,$msg); 
			echo $Xml;
		}
		//音乐
		if($type=='music'){
			$msg="歌曲很好听！";
			$Xml=$this->XmlText($xmlObj,$msg); 
			echo $Xml;
		}
	}
	//主页
	public function Isindex(Request $request)
	{
		$username=cache('login');
		$data=Right::where(['user_id'=>$username])->get()->toArray();
		$arr=explode(',',$data[0]['id']);
		$parent=Premiss::where(['parent_id'=>0])->get()->toArray();
		$role=[];
		foreach($parent as $k => $v){
			$role[$k]['id']=$v['id'];
			$role[$k]['methods']=$v['methods'];
		}
		$per=[];
		foreach($role as $k => $v){
			if(in_array($role[$k]['id'],$arr)){
				$per[$k]=$v['methods'];
			}
		}
		return view('index/index',compact('per'));
	}
	//微信图片显示页面
	public function IsUpload(Request $request)
	{
		$type=$request->all();
		$where=[];
		$name=$type['type']??'';
		$Wechat=new WechatUpload;
		if($name){
			$where=$name;
			$data=$Wechat::where(['type'=>$where])->paginate(4);
		}else{
			$data=$Wechat::paginate(4);

		}
		return view('index/isupload',compact('data','type'));
	}
	//上传图片显示页面
	public function WechatImg()
	{
		return view('index/wechatimg');
	}
	//处理上传图片
	public function dowechatimg(Request $request)
	{
		$all=$request->all();
		if($request->hasFile('img_url')){
			$all['img_url']=$this->uploads($request,"img_url");
			
		}
		if($all['material']=='voice'){
			$all['material']=1;
			$all['type']="voice";
			$all['add_time']=time();
		}elseif($all['material']=="thumb"){
			$all['add_time']=time();
			$all['type']="thumb";
			$all['material']=1;
		}elseif($all['material']=='music'){
			$all['material']=1;
			$all['type']="music";
			$all['add_time']=time();
		}elseif($all['material']=='video'){
			$all['material']=1;
			$all['type']="video";
			$all['add_time']=time();
		}elseif($all['material']=='always'){
			$all['add_time']=time();
			$all['type']="image";
			$all['material']=1;
		}elseif($all['material']=="temporary"){
			$all['add_time']=time();
			$all['type']="image";
			$all['material']=0;
			$all['Expiration_time']=time()+60*60*24*3;
		}
		// dd($all);
		unset($all['_token']);
		$is=$this->upload($all['img_url'],$all['material'],$all['type']);
		// dd($is);
		$all['media_id']=$is['media_id'];
		
		$data=WechatUpload::insert($all);
		if($data){
			return redirect('/IsUpload')->with('msg','添加成功');
		}
	}
	//图片上传
	public function uploads(Request $request,$name)
	{
        if ($request->file($name)->isValid()) {
			$photo = $request->file($name);
			$extension =$photo->getClientOriginalExtension();
            $store_result = $photo->storeAs("upload/".date('Ymd'), date('YmdHis').rand(100,999).'.'.$extension);
            return $store_result;
        }
        exit('未获取到上传文件或上传过程出错');
	}
	//微信 Midia id
	public function upload($imgurl,$material,$type="image")
	{
		//上传临时素材接口 
		$access_token=$this->access_token();

		if($material==0){
			$url = "https://api.weixin.qq.com/cgi-bin/media/upload?access_token={$access_token}&type={$type}";
		}else{
			$url = "https://api.weixin.qq.com/cgi-bin/material/add_material?access_token={$access_token}&type={$type}";
		}
		//素材路径 必须是绝对路径 
		$img = public_path().'/'.$imgurl;
		$imgPath = new CURLFile($img); //通过CURLFile处理
		$post_data = [
			'media'=>$imgPath  //素材路径 
		];
		if($type=="video"){
			$post_data['description'] = '{
				"title":"视频",
				"introduction":"测试"
			}';
		}
		//发请求
		// dd($post_data);
		$res = $this->curlPost($url,$post_data);
		$imgs=json_decode($res,true);
		// dd($imgs);
		return $imgs;
		
	}
	//删除微信图片
	public function imgdel(Request $request)
	{
		$img_id=$request->all();
		$data=WechatUpload::where($img_id)->delete();
		if($data==1){
			return redirect('/IsUpload')->with('msg','删除成功');
		}
	}
	//修改图片
	public function upd(Request $request)
	{
		$img_id=$request->all();
		$Wechat=new WechatUpload;
		$data=$Wechat::where($img_id)->get()->toArray();
		$res=$data[0];
		return view('index/imgupd',compact('res'));
	}
	//执行修改图片
	public function doupd(Request $request)
	{
		$img=$request->all();
		if($img['material']=='always'){
			$img['add_time']=time();
			$img['type']="image";
			$img['material']=1;
		}
		if($img['material']=="thumb"){
			$img['add_time']=time();
			$img['type']="thumb";
			$img['material']=1;
		}
		if($img['material']=='music'){
			$img['material']=1;
			$img['type']="music";
			$img['add_time']=time();
		}
		if($img['material']=='voice'){
			$img['material']=1;
			$img['type']="voice";
			$img['add_time']=time();
		}
		if($img['material']=='video'){
			$img['material']=1;
			$img['type']="video";
			$img['add_time']=time();
		}
		if($img['material']=="temporary"){
			$img['add_time']=time();
			$img['type']="image";
			$img['material']=0;
			$img['Expiration_time']=time()+60*60*24*3;
		}
		if(empty($img['img_url']))
		{
			$result = WechatUpload::where(['img_id'=>$img['img_id']])->update(['img_name'=>$img['img_name']]);
			if($result==1){
				return redirect('/IsUpload')->with('msg','修改成功');
			}	
		}else{
			if($request->hasFile('img_url')){
				$img['img_url']=$this->uploads($request,"img_url");
			}
			$is=$this->upload($img['img_url'],$img['material'],$img['type']);
			$img['media_id']=$is['media_id'];
			if($img['material']==0){
				$img['add_time']=time();
				$img['Expiration_time']=time()+60*60*24*3;
			}else{
				$img['add_time']=time();
			}
			unset($img['_token']);
			$data=WechatUpload::where(['img_id'=>$img['img_id']])->update($img);
			if($data){
				return redirect('/IsUpload')->with('msg','添加成功');
			}
		}
	}
	//获取随机图片
	public function RandImg()
	{
		$MId=[];
		$content=WechatUpload::where(['type'=>'image'])->get()->toArray();
		// dd($count);
		foreach($content as $key => $val){
			$MId[]=$val['media_id'];
		}
		foreach($MId as $k => $v){
			$count=$k;
		}
		$img_id=rand(0,$count);
		$media_id=$MId[$img_id];
		return $media_id;
	}
	//回复图片
	public function XmlImage($xmlObj)
	{
		$media_id=$this->RandImg();
		$Xml= "<xml>
				<ToUserName><![CDATA[".$xmlObj->FromUserName."]]></ToUserName>
				<FromUserName><![CDATA[".$xmlObj->ToUserName."]]></FromUserName>
				<CreateTime>".time()."</CreateTime>
				<MsgType><![CDATA[image]]></MsgType>
				<Image>
				<MediaId><![CDATA[".$media_id."]]></MediaId>
				</Image>
			</xml>";
			return $Xml;
	}
	//公众号菜单显示页面
	public function Menu()
	{	
		$data=Menus::paginate(8);
		return view('index/menu',compact('data'));
	} 
	//公众号添加菜单页面
	public function InMenu()
	{
		$data=Menus::where(['parent_id'=>0])->get();
		return view('index/inmenu',compact('data'));
	} 
	//公众号添加菜单处理
	public function DoInMenu(Request $request)
	{
		$all=$request->all();
		if($all['parent_id']=="0"){
			$count=Menus::where(['parent_id'=>0])->count();
			if($count>=3){
				return redirect('/Menu')->with('msg','超过菜单最大限制');
			}else{
				$Menus=Menus::insert($all);
				if($Menus){
					return redirect('/Menu')->with('msg','添加成功');
				}
			}
		}else{
			$counts=Menus::where(['parent_id'=>$all['parent_id']])->count();
			if($counts>=5){
				return redirect('/Menu')->with('msg','超过子类菜单最大限制');
			}else{
				$insert=[
					'type'=>null,
					'key'=>null,
				];
				$parent_id=$all['parent_id'];
				$res=Menus::where(['id'=>$parent_id])->update($insert);
					$data=Menus::insert($all);
					if($data){
						return redirect('/Menu')->with('msg','添加成功');
					}
			}
		}
	} 
	//公众号修改菜单页面
	public function UpMenu(Request $request)
	{
		$id=$request->all();
		$data=Menus::where($id)->get()->toArray();
		$parent=$this->ParentId();
		return view('index/UpMenu',compact('data','parent'));
	} 
	//公众号修改菜单页面
	public function DoUpMenu(Request $request)
	{
		$all=$request->all();
		$id=$all['id'];
		$insert=[
			'type'=>null,
			'key'=>null,
		];
		if($all['parent_id']=="0"){
			$count=Menus::where(['parent_id'=>0])->count();
			if($count>=3){
				return redirect('/Menu')->with('msg','超过菜单最大限制');
			}else{
				
				unset($all['id']);
				$parent_id=$all['parent_id'];
				$res=Menus::where(['id'=>$parent_id])->update($insert);
				$data=Menus::where(['id'=>$id])->update($all);
				if($data){
					return redirect('/Menu')->with('msg','修改成功');
				}
			}
		}else{
				$parent_id=$all['parent_id'];
				$res=Menus::where(['id'=>$parent_id])->update($insert);
				$data=Menus::where(['id'=>$id])->update($all);
					if($data){
						return redirect('/Menu')->with('msg','修改成功');
					}
		}
		
	}
	//公众号删除菜单页面
	public function DelMenu(Request $request)
	{
		$id=$request->all();
		$parent=Menus::where(['parent_id'=>$id])->count();
		if($parent){
			return redirect('/Menu')->with('msg','有未删除的子分类');
		}
		$data=Menus::where(['id'=>$id])->delete();
		if($data){
			return redirect('/Menu')->with('msg','删除成功');
		}
	} 
	//一键同步公众号菜单
	public function synchronous()
	{
		$Menus=Menus::where(['parent_id'=>0])->get()->toArray();
		$typeArr = ['click'=>'key','view'=>'url'];
		foreach($Menus as $key => $val){
			if(empty($val['type'])){
				$Menus_id=Menus::where(['parent_id'=>$val['id']])->get()->toArray();
				foreach ($Menus_id as $k => $v) {
					$sub[$k]=['type'=> $v['type'],'name'=> $v['names'],$typeArr[$v['type']] =>$v['key']];
					$Menus_r['button'][$key]= [
						'name'=> $val['names'],
						'sub_button'=>$sub,
					];
				}
			}else{
				$Menus_r['button'][$key] = [
					'type'=> $val['type'],
					'name'=> $val['names'],
					$typeArr[$val['type']] => $val['key']
			   ];
			}
		}
		$post_data=json_encode($Menus_r,JSON_UNESCAPED_UNICODE);
		$access_token=$this->access_token();
		$url="https://api.weixin.qq.com/cgi-bin/menu/create?access_token={$access_token}";
		$res=$this->curlPost($url,$post_data);
		$data=json_decode($res,true);
		if($data['errmsg']=="ok"){
			return redirect('/Menu')->with('msg','同步成功');
		}
	}
	//群发显示页面
	public function IsAMass()
	{
		return view('index/IsAMass');
	}
	//群发
	public function AMass(Request $request)
	{
		$all=$request->all();
		if($all['radio']==1){
			$AMass['filter']=[
				"is_to_all"=>true,
				"tag_id"=>'',
			];
			$AMass['text']=[
				"content"=>"{$all['content']}"
			];
			$AMass['msgtype']="text";
			$AMassJson=\json_encode($AMass,JSON_UNESCAPED_UNICODE);
			$access_token=$this->access_token();
			$url="https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token={$access_token}";
			$curl=$this->curlPost($url,$AMassJson);
			$success=json_decode($curl,true);
			if($success['errcode']==0){
				return redirect('/IsAMass')->with('msg','发送成功');
			}else{
				return redirect('/IsAMass')->with('msg','发送次数过多');
			}
		}elseif($all['radio']==0){
			$content=$all['content'];
			$AMass['touser']=$all['openid'];
			$AMass['msgtype']="text";
			$AMass['text']=['content'=>$content];
			$AMassJson=json_encode($AMass,JSON_UNESCAPED_UNICODE);
			$access_token=$this->access_token();
			$url="https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token={$access_token}";
			$curl=$this->curlPost($url,$AMassJson);
			$success=json_decode($curl,true);
			if($success['errcode']==0){
				return redirect('/IsAMass')->with('msg','发送成功');
			}else{
				return redirect('/IsAMass')->with('msg','发送次数过多');
			}
		}elseif($all['radio']==2){
			$AMass['filter']=[
				"is_to_all"=>false,
				"tag_id"=>$all['tagid'],
			];
			$AMass['text']=[
				"content"=>"{$all['content']}"
			];
			$AMass['msgtype']="text";
			$AMassJson=\json_encode($AMass,JSON_UNESCAPED_UNICODE);
			$access_token=$this->access_token();
			$url="https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token={$access_token}";
			$curl=$this->curlPost($url,$AMassJson);
			$success=\json_decode($curl,true);
			if($success['errcode']==0){
				return redirect('/IsAMass')->with('msg','发送成功');
			}else{
				return redirect('/IsAMass')->with('msg','发送次数过多');
			}
		}
		
	}
	//群发部分openid
	public function PartAMass(Request $request)
	{
		$all=$request->all();
		if($all['radio']==1){
			$data=OpenId::get();
			return view('index/Part',compact('data'));
		}elseif($all['radio']==2){
			$data=Label::get();                                                                                
			return view('index/LabelParts',compact('data'));
		}
	}
	//二维码显示页面
	public function IsQrCode()
	{	
		$data=OrCode::paginate(6);
		return view('index/IsOrCode',compact('data'));
	}
	//二维码添加页面
	public function addOrCode()
	{
		return view('index/AddOrCode');
	}
	//二维码生成处理
	public function RateQrCode(Request $request)
	{
		$all=$request->all();
			$access_token=$this->access_token();
			$url="https://api.weixin.qq.com/cgi-bin/qrcode/create?access_token={$access_token}";
			$Ticket['expire_seconds']=604800;
			$Ticket['action_name']="QR_SCENE";
			$scene['scene']['scene_id']=$all['keyword'];
			$Ticket['action_info']=$scene; 
			$TicketJson=json_encode($Ticket);
			$curl=$this->curlPost($url,$TicketJson);
			$OrCode=json_decode($curl,true);
			$ticket=$OrCode['ticket'];
			$all['ticket']=$ticket;
			$csurl="https://mp.weixin.qq.com/cgi-bin/showqrcode?ticket={$ticket}";
			$da=Public_Path().'/'."OrCode/".$scene['scene']['scene_id'].".jpg";
			$get=file_get_contents($csurl); 
			$put=file_put_contents($da,$get);
			$all['img']="OrCode/".$scene['scene']['scene_id'].".jpg";
			$data=OrCode::insert($all);
			if($data){
				return redirect('/IsQrCode')->with('msg','添加成功'); 
			}
	}
	//二维码删除处理
	public function OrCodeDel(Request $request)
	{
		$id=$request->id;
		$data=OrCode::where(['id'=>$id])->delete();
		if($data){
			return redirect('/IsQrCode')->with('msg','删除成功');
		}
	}
	//二维码修改显示
	public function OrCodeUpd(Request $request)
	{
		$id=$request->id;
		$res=OrCode::where(['id'=>$id])->get()->toArray();
		$data=$res[0];
		return view('index/OrCodeUpd',compact('data'));
	}
	//二维码修改处理
	public function DoQrCodeUpd(Request $request)
	{
		$all=$request->all();
		$data=OrCode::where(['id'=>$all['id']])->update(['typename'=>$all['typename']]);
		if($data){
			return redirect('/IsQrCode')->with('msg','修改成功');
		}
	}
	//调柱形显示
	public function statistical(Request $request)
	{
		$data=OrCode::get()->toArray();
		$dataStr="";
		$dataInt="";
		foreach($data as $k=>$v){
			$dataStr.="'".$v['typename']."',";
			$dataInt.=$v['number'].",";

		}
		$dataStr=rtrim($dataStr,',');
		$dataInt=rtrim($dataInt,',');
		// dd($dataStr);
		return view("index/statistical",compact('dataStr','dataInt'));
	}
	//首页天气
	public function weather()
	{
		$weaid="北京";
		$nowapi_parm['app']='weather.future';
		$nowapi_parm['weaid']=$weaid;
		$nowapi_parm['appkey']='42493';
		$nowapi_parm['sign']='11bf7ea021846df6f628aa2335f1e4c7';
		$nowapi_parm['format']='json';
		$arr=$this->nowapi_call($nowapi_parm);
		// dump($arr);
		$week="";
		$a="";
		foreach($arr as $k=>$v)
		{
			$week.="'".$v['week']."',";
			// dump ($v['week']);
			$temp[]=$v['temp_high'].",".$v['temp_low'];
		}
		$week=rtrim($week,',');
		return view('index/weather',compact('week','temp'));
	}
	//Ajax首页天气
	public function AjaxWeather(Request $request)
	{
		$WeaId=$request->all();
		$weaid=$WeaId['WeaId'];
		$nowapi_parm['weaid']=$weaid;
		$nowapi_parm['app']='weather.future';
		$nowapi_parm['appkey']='42493';
		$nowapi_parm['sign']='11bf7ea021846df6f628aa2335f1e4c7';
		$nowapi_parm['format']='json';
		$arr=$this->nowapi_call($nowapi_parm);
		// dump($arr);
		$week="";
		$a="";
		foreach($arr as $k=>$v)
		{
			$week.="'".$v['week']."',";
			// dump ($v['week']);
			$temp[]=$v['temp_high'].",".$v['temp_low'];
		}
		$week=rtrim($week,',');
		return view('index/AjaxWeather',compact('week','temp','weaid'));
	}
	//粉丝列表
	public function fans()
	{
		$data=OpenId::where(['is_focus'=>1])->paginate(8);
		return view('index/fans',compact('data'));
	}
	//管理标签
	public function TubeLabel()
	{
		$data=Label::get();
		return view('index/TubeLabel',compact('data'));
	}
	//添加标签
	public function addLabel()
	{
		return view('index/addLabel');
	}
	//添加标签处理页面
	public function doAddLabel(Request $request)
	{
		$all=$request->all();
		$arr['tag']=$all;
		$Json=json_encode($arr,JSON_UNESCAPED_UNICODE);
		$access_token=$this->access_token();
		$url="https://api.weixin.qq.com/cgi-bin/tags/create?access_token={$access_token}";
		$curl=$this->curlPost($url,$Json);
		$data=json_decode($curl,true);
		$res['name']=$data['tag']['name'];
		$res['tagid']=$data['tag']['id'];
		$res=Label::insert($res); 
		if($res){
			return redirect('/TubeLabel')->with('msg','修改成功');
		}
	}
	//标签添加添加粉丝
	public function addFans(Request $request)
	{
		$tagid=$request->tagid;
		$data=OpenId::get();
		return view('index/addFans',compact('data','tagid'));
	}
	//标签添加粉丝处理页面
	public function DoAddFans(Request $request)
	{
		$all=$request->all();
		$Json=json_encode($all);
		$access_token=$this->access_token();
		$url="https://api.weixin.qq.com/cgi-bin/tags/members/batchtagging?access_token={$access_token}";
		$curl=$this->curlPost($url,$Json);
		$json=$this->Label(); 
		return redirect('/TubeLabel')->with('msg','添加成功');
	}
	//修改标签
	public function updLabel(Request $request)
	{
		$tagid=$request->tagid;
		$Lable=Label::where(['tagid'=>$tagid])->get()->toArray();
		$res=$Lable[0];
		return view('index/updLabel',compact('res','tagid'));
	}
	//修改标签处理页面
	public function DoUpdLabel(Request $request)
	{
		$all=$request->all();
		$arr['tag']=['id'=>$all['tagid'],'name'=>$all['name']];
		$json=json_encode($arr,JSON_UNESCAPED_UNICODE);
		$access_token=$this->access_token();
		$url="https://api.weixin.qq.com/cgi-bin/tags/update?access_token={$access_token}";
		$curl=$this->curlPost($url,$json);
		$json=$this->Label();
		return redirect('/TubeLabel')->with('msg','修改成功');
	}
	//删除标签
	public function delLabel(Request $request)
	{
		$tagid=$request->tagid;
		$access_token=$this->access_token();
		$url="https://api.weixin.qq.com/cgi-bin/tags/delete?access_token={$access_token}";
		$tag['tag']=['id'=>$tagid];
		$json=json_encode($tag);
		$curl=$this->curlPost($url,$json);
		$Lable=Label::where(['tagid'=>$tagid])->delete();
		if($Lable){
			return redirect('/TubeLabel')->with('msg','删除成功');
		}
	}
	//登陆页面
	public function Login(Request $request)
	{
		$JsSdk=$this->JsSdk();
		// dd($JsSdk);
		return view('Login/Login',compact('JsSdk'));
	}
	//处理登陆页面
	public function isLogin(Request $request)
	{
		$all=$request->all();
		if($all['username']==""){
			return redirect('/Login')->with('msg','请填写用户名');
		}
		if($all['password']==""){
			return redirect('/Login')->with('msg','请填写密码');
		}
		if($all['password']==""){
			return redirect('/Login')->with('msg','请填写密码');
		}
		$code=cache("{$all['username']}验证码");
		if($code!=$all['code']){
			return redirect('/Login')->with('msg','验证码错误');
		}
		unset($all['code']);
		$time=time();
		$res=Login::where(['username'=>$all['username']])->update(['LastTime'=>$time]);
		$all['password']=md5(md5($all['password']));
		$Info=Login::where(['username'=>$all['username']])->get();
		$res=$Info[0];
		$result=Login::where($all)->count();
		$now=time();
		if($result==1){
			$data=Login::where($all)->first();
        	if($res['errorcount'] >=3 && $now-$res['errortime']<3600){
        		$count=60-ceil( (time()-$res['errortime'])/60);
        		echo "请过".$count."分钟后重试";
        	}else{
        		$Login = Login::where(['username'=>$all['username']])->first();
		        $Login->errorcount=0;
		        $Login->errortime=null;
		        $resInfo=$Login->save();
		        $cache=cache(['login'=>$data[0]],60*60*24);
				return redirect('/Isindex')->with('msg','登陆成功');
        	}
		}else{
			if($now-$res['errortime']>3600){
        		$Login = Login::where(['username'=>$all['username']])->first();
		        $Login->errorcount='1';
		        $Login->errortime=$now;
		        $resInfo =$Login->save();
		        return '密码错误1';
			}else{
				if($res['errorcount']>=5){
        			$count=60-ceil( ($now-$res['errortime'])/60);
        			return "账号已被锁定 请".$count."分钟后登陆";
        		}else{
        			$Login = Login::where(['username'=>$all['username']])->first();
			        $Login->errorcount=$res['errorcount']+1;
			        $Login->errortime=$now;
			        $resInfo=$Login->save();
			        return '密码错误2';
        		}
			}
		}
	}
	//登陆获取验证码
	public function code(Request $request)
	{
		$username=$request->all();
		$time=time();
		$time=date('Y年m月d日 h:i:s',$time);
		$data=Login::where($username)->get()->toArray();
		$LastTime=date('Y年m月d日 h:i:s',$data[0]['LastTime']);
		$code=rand(100000,999999);
		cache(["{$data[0]['username']}验证码"=>$code],60*2);
		cache(["username"=>$data[0]['username']],60*60*24);
		cache(["login"=>$data[0]],60*60*24);
		$access_token=$this->access_token();
		$url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token={$access_token}";
		$Array['touser']=$data[0]['openid'];
		$Array['template_id']="YIE9-JgmJg49TbKBmrz2vXZfHIn7SFkMXb2S4StK2vY";
		$Array['url']="http://www.baidu.com";
		$Array['data']['code']['value']="{$code}";
		$Array['data']['code']['color']="#8B008B";
		$Array['data']['username']['value']="{$data[0]['username']}";
		$Array['data']['username']['color']="#8A2BE2";
		$Array['data']['time']['value']="$time";
		$Array['data']['time']['color']="#8A2BE2";
		$Array['data']['LastTime']['value']="{$LastTime}";
		$Array['data']['LastTime']['color']="#8A2BE2";
		$Json=json_encode($Array,JSON_UNESCAPED_UNICODE);
        $curl=$this->curlPost($url,$Json);//发送
		$res=Login::where($username)->update(['LastTime'=>time()]);
		if($res){
			echo 1;
		}
	}
	//测试
	public function test(Request $request)
	{
		$urlEncode=urlEncode('http://39.97.101.66/binding/');
		$url="http://open.weixin.qq.com/connect/oauth2/authorize?appid=wx1f9161f806626795&redirect_uri=$urlEncode&response_type=code&scope=snsapi_userinfo&state=STATE#wechat_redirect";
		$content=file_get_contents($url);
		return view('index/test',compact('url'));
	}
	//获取微信用户个人信息
	public function binding(Request $request)
	{
		$all=$request->all();
		$url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=wx1f9161f806626795&secret=e145f36cee7a9f4ffc77305520fe4a89&code={$all['code']}&grant_type=authorization_code";
		$content=file_get_contents($url);
		$arr=json_decode($content,true);
		$OpenId=$arr['openid'];
		session(['openid'=>$OpenId]);
		return view('index/binding',compact('OpenId'));
	}
	public function isss()
	{
		$a=urlEncode("39.97.101.66/MyCoupon");
		dd($a);
		// $date=1555555555;
		// $time=date('Y年m月d日H时i分s秒',$date);
		// dd($time);
		// $access_token=$this->access_token();
		// $json=['appid'=>'wx1f9161f806626795'];
		// $appid=json_encode($json);
		// $url="https://api.weixin.qq.com/cgi-bin/clear_quota?access_token={$access_token}";
		// $curl=$this->curlPost($url,$appid);
		// dd($curl);
		// /usr/local/php/bin/php    curl http://39.97.101.66/linddec


	}
	//签到视图
	public function SignView()
	{
		$username=cache('login');
		// dd($username);
		$username=Login::where(['id'=>$username['id']])->get()->toArray();
		$username=$username[0];
		return view('index/SignView',compact('username'));
	}
	//签到
	public function Sign()
	{
		$username=cache('login');
		$data=Login::where(["username"=>$username['username']])->get()->toArray();
		$res=$data[0];
		$time=time();
		$check_time=$res['check_time'];
		if($check_time==null){
			$conditions=[
				'check_time'=>time(),
				'sing_number'=>1,
				'integral'=>$res['integral']+5,
			];
			$Login=Login::where(['id'=>$res['id']])->update($conditions);
			$a=$res['integral']+5;
			if($Login==1){
				echo "签到成功 成功签到1天 您现在的积分为{$a}";
			}
		}elseif($time-$check_time>172800){
			$conditions=[
				'check_time'=>time(),
				'sing_number'=>1,
				'integral'=>$res['integral']+5,
			];
			$Login=Login::where(['id'=>$res['id']])->update($conditions);
			$a=$res['integral']+5;
			if($Login==1){
				echo "签到成功 成功签到1天 您的积分为 {$a} ";
			}
		}elseif($time-$check_time>86400){
			$count=$res['sing_number']+1;
			if($count>=5){
				$integral=$res['integral']+25;
			}else{
				$is=5*$count;
				$integral=$res['integral']+$is;
				echo $integral;
			}
			$conditions=[
				'check_time'=>time(),
				'sing_number'=>$count,
				'integral'=>$integral,
			];
			$Login=Login::where(['id'=>$res['id']])->update($conditions);
			if($Login==1){
				echo "签到成功 成功签到{$count}天,增加积分{$is},您现在的积分为{$integral}";
			}
			
		}elseif($time-$check_time<86400){
			echo "明天再来签到吧！";
		}
	}
	//角色管理页面
	public function role()
	{
		$data=Right::get();
		return view('index/role',compact('data'));
	}
	//角色添加显示页面
	public function addRole()
	{
		$parent=Premiss::where(['parent_id'=>0])->get()->toArray();
		foreach($parent as $key => $val){
			$static[]=Premiss::where(['parent_id'=>$val['id']])->get()->toArray();
			array_unshift($static[$key],$val);
		}
		$login=Login::get();
		return view('index/addRole',compact('static','login'));
	}
	//角色添加处理页面
	public function doAddRole(Request $request)
	{
		$all=$request->all();
		$all['id']=implode(',',$all['id']);
		$data=Right::insert($all);
		if($data){
			return redirect('/role')->with('msg','添加成功');
		}
		
	}
	//角色删除
	public function delRole(Request $request)
	{
		$id=$request->all();
		$data=Right::where($id)->delete();
		if($data){
			return redirect('/role')->with('msg','删除成功');
		}
	}
	//角色修改显示页面
	public function updRole(Request $request)
	{
		$id=$request->all();
		$parent=Premiss::where(['parent_id'=>0])->get()->toArray();
		foreach($parent as $key => $val){
			$static[]=Premiss::where(['parent_id'=>$val['id']])->get()->toArray();
			array_unshift($static[$key],$val);
		}
		$login=Login::get();
		$data=Right::where($id)->get()->toArray();
		$data=$data[0];
		$id=$data['id'];
		$id=explode(',',$id);
		return view('index/updRole',compact('static','login','data','id'));
	}
	//角色修改处理页面
	public function doUpdRole(Request $request)
	{
		$all=$request->all();
		$all['id']=implode(',',$all['id']);
		$data=Right::where(['r_id'=>$all['r_id']])->update($all);
		if($data){
			return redirect('/role')->with('msg','添加成功');
		}
	}
	//添加权限显示页面
	public function PerMiss()
	{
		$data=Premiss::where(['parent_id'=>0])->get()->toArray();
		return view('index/PerMiss',compact('data'));
	}
	//添加权限处理页面
	public function doPerMiss(Request $request)
	{
		$all=$request->all();
		$data=Premiss::insert($all);
		if($data){
			return redirect('/role')->with('msg',"添加成功");
		}
	}
	//AJAX判断是否扫码
	public function automatic()
	{
		$cache=cache('islogin');
		if(!$cache){
			echo 1;
		}else{
			echo 2;
		}
	}
	//JsSdk分享
	public function JsSdk()
	{
		$jsapi_ticket=cache('ticket');
		if(empty($cache)){
			$access=$this->access_token();
			$url="https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token={$access}&type=jsapi";
			$curl=file_get_contents($url);
			$curl=json_decode($curl,true);
			$cache=cache(['ticket'=>$curl['ticket']],7200);
			$jsapi_ticket=cache('ticket');
		}
		$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    	$url = "$protocol$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
		$timestamp = time();
		$nonceStr = $this->createNonceStr();
		$string="jsapi_ticket={$jsapi_ticket}&noncestr={$nonceStr}&timestamp={$timestamp}&url={$url}";
		$signature=sha1($string);
		$signPackage = array(
			"appId"     => "wx1f9161f806626795",
			"nonceStr"  => $nonceStr,
			"timestamp" => $timestamp,
			"url"       => $url,
			"signature" => $signature,
			"rawString" => $string
		  );
		return $signPackage;
	}
	//定时任务测试
	public function linddec()
	{
		$where=[
			'title'=>'这里是定时任务',
			'content'=>"您好",
			'release_status'=>1,
			'release_time'=>time(),
		];
		$res=Notice::insert($where);
		dd($res);
		$a=1;
		$a++;
		 echo $a;
	}
	//定时消息
	public function TimingMass()
	{
		$AMass['filter']=[
			"is_to_all"=>true,
			"tag_id"=>'',
		];
		$AMass['text']=[
			"content"=>"您好 这里是群发消息定时的"
		];
		$AMass['msgtype']="text";
		$AMassJson=\json_encode($AMass,JSON_UNESCAPED_UNICODE);
		$access_token=$this->access_token();
		$url="https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token={$access_token}";
		$curl=$this->curlPost($url,$AMassJson);
		$success=json_decode($curl,true);
	}
	//考试系统
	public function TheTest()
	{
		$data=TheTest::get();
		return view('index/TheTest',compact('data'));
	}
	public function addTheTest()
	{
		return view('index/addTheTest');
	}
	//添加题目
	public function doAddTheTest(Request $request)
	{
		$all=$request->all();
		$data=TheTest::insert($all);
		if($data){
			return redirect('/TheTest')->with('msg',"添加成功");
		}
	}
	//点击答题
	public function Answer($xmlObj)
	{
		$user=$xmlObj->FromUserName;
		$count=TheTest::get()->count();
		$data=TheTest::get()->toArray();
		$k=rand(0,$count-1);
		$msg=$data[$k]['t_name']."A:".$data[$k]['answer_a'].','."B:".$data[$k]['answer_b'];
		$insert=[
			'open_id'=>$user,
			'action'=>'Answer',
			'time'=>time(),
			't_id'=>$data[$k]['id'],
		];
		$action=Action::insert($insert);
		$xml=$this->XmlText($xmlObj,$msg);
		echo $xml;
	}
	//答题
	public function questions($xmlObj)
	{
		$user=$xmlObj->FromUserName;
		$Action=Action::where(['open_id'=>$user])->orderBy('time','desc')->get()->toArray();
		$t_id=$Action[0]['t_id'];
		$data=TheTest::where(['id'=>$t_id])->get()->ToArray();
		$correct=$data[0]['correct'];
		if($correct==$xmlObj->Content){
			$msg="恭喜你回答正确！";
			$xml=$this->XmlText($xmlObj,$msg);
			$insert=[
				'openid'=>$user,
				't_id'=>$t_id,
				'result'=>1,
				'time'=>time(),
			];
			$data=Result::insert($insert);
			// dd($data);
			echo $xml;

		}else{
			$msg="很抱歉回答错误";
			$insert=[
				'openid'=>$user,
				't_id'=>$t_id,
				'result'=>2,
				'time'=>time(),
			];
			$data=Result::insert($insert);
			$xml=$this->XmlText($xmlObj,$msg);
			echo $xml;
		}
	}
	//点击成绩
	public function Results($xmlObj)
	{
		$user=$xmlObj->FromUserName;
		$count=Result::where(['openid'=>$user])->get()->count();
		$correct=Result::where(['openid'=>$user,'result'=>1])->get()->count();
		$error=Result::where(['openid'=>$user,'result'=>2])->get()->count();
		$msg="您一共答了{$count}道题,正确{$correct}道题,错误{$error}道题，再接再厉！";
		$xml=$this->XmlText($xmlObj,$msg);
		echo $xml;
	}
	public function okok()
	{
		$name="刘瑞";
		$age=129;
		$str=md5($name.$age."10a");
		$url="http://47.105.106.201/crontab.php?name={$name}&age={$age}&sign={$str}"; 
		$a=file_get_contents($url);
		dd($a);
	}
	private function createNonceStr($length = 16) 
	{
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
		$str = "";
		for ($i = 0; $i < $length; $i++) {
		  $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
		}
		return $str;
	}
	//微信支付
	public function Pays()
	{
		//调用微信接口
		$nouce = $this->createNonceStr();
		 //订单号
		 $out_trade_no = "10a".date("YmdHi").rand(1000,9999);
		 $appid = 'wxd5af665b240b75d4';
		 $mch_id = '1500086022';
		 $nonce_str = $this->createNonceStr();
		 $notify_url = "http://39.97.101.66/success"; //支付成功后异步通知地址
		 $spbill_create_ip = $_SERVER['REMOTE_ADDR'];
		 $total_fee = 1; //钱 单位是分
		 $trade_type = "NATIVE";
		 $body = "打赏";

		 $signArr = [
				'appid'=>$appid, //公众账号ID
				'body'=>$body, //订单内容
				'mch_id'=>$mch_id, //商户ID
				'nonce_str'=>$nonce_str, //随机字符串
				'notify_url'=>$notify_url, //通知地址
				'out_trade_no'=>$out_trade_no, //商户订单号
				'spbill_create_ip'=>$spbill_create_ip, //终端IP
				'total_fee'=>$total_fee, //标价金额
				'trade_type'=>$trade_type, //标价币种
		];
		ksort($signArr);
		$string = $this->ToUrlParams($signArr);
		//签名步骤二：在string后加入KEY
		$string = $string . "&key="."7c4a8d09ca3762af61e59520943AB26Q";
		 //签名步骤三：MD5加密或者HMAC-SHA256
		 $string = md5($string);
		 //签名步骤四：所有字符转为大写
		 $sign = strtoupper($string);
		  //组装xml数据
			$xml = '<xml>
				<appid>'.$appid.'</appid>
				<body>'.$body.'</body>
				<mch_id>'.$mch_id.'</mch_id>
				<nonce_str>'.$nonce_str.'</nonce_str>
				<notify_url>'.$notify_url.'</notify_url>
				<out_trade_no>'.$out_trade_no.'</out_trade_no>
				<spbill_create_ip>'.$spbill_create_ip.'</spbill_create_ip>
				<total_fee>'.$total_fee.'</total_fee>
				<trade_type>'.$trade_type.'</trade_type>
				<sign>'.$sign.'</sign>
		</xml>';
			//微信支付地址
			$url = "https://api.mch.weixin.qq.com/pay/unifiedorder";
			//发送post请求 发送xml数据
			$res = $this->http_post_xml($url,$xml);
			$resObj = simplexml_load_string($res);
			if($resObj->return_code == 'SUCCESS'){
					$code_url = $resObj->code_url;
					// echo $code_url;die;
			}
			echo "<image align='center' src='data:image/png;base64,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'>";
	}
	public function success()
	{
			echo "打赏成功";
	}
	private function ToUrlParams($signArr)
    {
        $buff = "";
        foreach ($signArr as $k => $v)
        {
            if($k != "sign" && $v != "" && !is_array($v)){
                $buff .= $k . "=" . $v . "&";
            }
        }
        
        $buff = trim($buff, "&");
        return $buff;
	}
	public function coupons()
	{
		return view('index/coupons');
	}
	public function addCoupons(Request $request)
	{
		$all=$request->all();
		$all['time']=time();
		$res=Coupons::orderby('time','desc')->get()->toArray();
		if((time()-$res[0]['time'])<86400){ 
			echo "今天已经添加过优惠了";die;
		}
		$data=Coupons::insert($all);
		if($data){
			return redirect('/coupons')->with('msg',"添加成功");
		}
	}
	public function LuckyDraw(Request $request)
	{
		$all=$request->all();
		$url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=wx1f9161f806626795&secret=e145f36cee7a9f4ffc77305520fe4a89&code={$all['code']}&grant_type=authorization_code";
		$content=file_get_contents($url);
		$arr=json_decode($content,true);
		$OpenId=$arr['openid'];
		session(['openid'=>$OpenId]);
		return view('index/LuckyDraw',compact('OpenId'));
	}
	public function Draw(Request $request)
	{
		$time=time();
		$openid=session('openid');
		$da=Count::where(['openid'=>$openid])->get()->toArray();
		if(empty($da)){
			Count::insert(['openid'=>$openid,'count'=>2,'time'=>time()]);
		}elseif($da[0]['count']==0 && $time-$da[0]['time']<86400){
			echo "您今天已经抽过了三次 明天再来吧";die;
		}elseif($da[0]['count']!=0){
			Count::where(['openid'=>$openid])->update(['count'=>$da[0]['count']-1,'time'=>time()]);
		}else{
			Count::where(['openid'=>$openid])->update(['count'=>2,'time'=>time()]); 
		}
		$data=Vouchers::where(['openid'=>$openid])->orderby('last_time','desc')->get()->ToArray();
		$chance=6;
		$rand=rand(1,10);
		if($chance!=$rand){ 
			echo "抱歉您没中奖";
			//抽奖时间添加数据库
			$res=Vouchers::insert(['openid'=>$openid,"last_time"=>$time]);
		}else{
			$is=Vouchers::where(['openid'=>$openid,"is_use"=>0])->count();
			if($is>=3){
				echo "您的优惠券太多了 先用完再来吧";die;
			};
			$Coupons=Coupons::where('number','>',0)->count();
			if($Coupons==0){
                echo "暂无可用优惠券";die;
			}		
			$c_id=rand(1,$Coupons);
			$where=[
				'openid'=>$openid,
				"last_time"=>$time,
				'c_id'=>$c_id,
				'tor_time'=>$time,
				'is_use'=>0
			];
			$res=Vouchers::insert($where);
			$data=Coupons::where(['c_id'=>$c_id])->get()->toarray();
			$data=Coupons::where(['c_id'=>$c_id])->update(['number'=>$data[0]['number']-1]);
			echo "恭喜您中大奖了";
		}
	}
	public function MyCoupon(Request $request)
	{
		$all=$request->all();
		$url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=wx1f9161f806626795&secret=e145f36cee7a9f4ffc77305520fe4a89&code={$all['code']}&grant_type=authorization_code";
		$content=file_get_contents($url);
		$arr=json_decode($content,true);
		$OpenId=$arr['openid'];
		$times=time();
		$time=Vouchers::where(['openid'=>$OpenId])->get()->toarray();
		foreach($time as $k => $v){
			if($times-$v['tor_time']>60*60*24*7){
				Vouchers::where(['c_id'=>$v['c_id']])->delete();
			}
		}
		$where=Vouchers::where(['openid'=>$OpenId,'is_use'=>0])->get()->toarray();
		if(empty($where)){
			echo "您还没有优惠券！";die;
		}   
		foreach($where as $k =>$v)
		{
			$a=Coupons::where(['c_id'=>$v['c_id']])->get()->toArray();
			$array[$k]=$a[0];
			$array[$k]['time']=$v['tor_time'];
		}
		return view('index/MyCoupon',compact('array'));
	}
}  
