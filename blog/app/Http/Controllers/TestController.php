<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\wechatpublic;
use App\Model\Goods;
use App\Model\Menus;
use App\Model\OpenId;
use App\Model\Action;
use CURLFile;
class TestController extends wechatpublic
{
    public function index()
    {
        $obtain=file_get_contents('php://input');
        \file_put_contents('1.txt',$obtain);
        $objXml=simplexml_load_string($obtain);
        $access_token=$this->access_token();//获取access_token
        $url="https://api.weixin.qq.com/cgi-bin/user/info?access_token={$access_token}&openid={$objXml->FromUserName}&lang=zh_CN";
            $open=\file_get_contents($url);
            $open=json_decode($open,true);
        if($objXml->MsgType=="event" && $objXml->Event=="subscribe"){
            $conditions=[
                'open_id'=>$open['openid'],
                'action'=>'Search',
                'time'=>time(),
            ];
            $data=OpenId::where(['openid'=>$open['openid']])->count();
            if($data==0){
                $xml=$this->XmlText($objXml,"请输入商品名字字样");//XML
                Action::insert($conditions);
                echo $xml;
            }else{
                $xml=$this->XmlText($objXml,"请输入商品名字字样");//同上
                Action::insert($conditions);
                echo $xml;
            }
        }
        $data=Action::where(['open_id'=>$open['openid']])->OrderBy('time','desc')->get()->toArray();
        if($objXml->MsgType=="text" && $data[0]['action']){
            $content=$objXml->Content;
            $cache=cache("$content");
            if(!$cache){
                $where[]=['goods_name','like',"%$content%"];
                $data=Goods::where($where)->get()->ToArray();
                $rand=Goods::where($where)->get()->count();
                $k=rand(0,$rand-1);
                echo "没走缓存";
                $url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token={$access_token}";
                $Array['touser']=$open['openid'];
                $Array['template_id']="GLR1BES-MajCLIfinfOb6rceSPuf_3egAdpSqZHDSsY";
                $Array['url']="http://www.baidu.com";
                $Array['data']['goods_name']['value']="{$data[$k]['goods_name']}";
                $Array['data']['goods_name']['color']="#173177";
                $Array['data']['shop_price']['value']="{$data[$k]['shop_price']}￥";
                $Array['data']['shop_price']['color']="#173177";
                $Array['data']['goods_number']['value']="{$data[$k]['goods_number']}";
                $Array['data']['goods_number']['color']="#173177";
                $Json=json_encode($Array,JSON_UNESCAPED_UNICODE);
                $curl=$this->curlPost($url,$Json);//发送
                cache(["{$data[$k]['goods_name']}"=>"{$data[$k]['goods_name']}"],60*2);
            }else{
                $data=Goods::where(['goods_name'=>$cache])->get()->ToArray();
                $rand=Goods::where(['goods_name'=>$cache])->get()->count();
                $k=rand(0,$rand-1);
                $url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token={$access_token}";
                $Array['touser']=$open['openid'];
                $Array['template_id']="GLR1BES-MajCLIfinfOb6rceSPuf_3egAdpSqZHDSsY";
                $Array['url']="http://www.baidu.com";
                $Array['data']['goods_name']['value']="{$data[$k]['goods_name']}";
                $Array['data']['goods_name']['color']="#173177";
                $Array['data']['shop_price']['value']="{$data[$k]['shop_price']}￥";
                $Array['data']['shop_price']['color']="#173177";
                $Array['data']['goods_number']['value']="{$data[$k]['goods_number']}";
                $Array['data']['goods_number']['color']="#173177";
                $Json=json_encode($Array,JSON_UNESCAPED_UNICODE);
                $curl=$this->curlPost($url,$Json);//发送
            }
            
        }
    }
}