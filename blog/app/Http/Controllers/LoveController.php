<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\wechatpublic;
use App\Model\Login;
use App\Model\Action;
use CURLFile;
class LoveController extends wechatpublic
{
    //菜单
    public function index()
    {
        $access_token=$this->access_token();
        $url="https://api.weixin.qq.com/cgi-bin/menu/create?access_token={$access_token}";
        $post_data='
        {
            "button":[
                {    
                    "type":"click",
                    "name":"查看表白",
                    "key":"ToView"
                },
                {    
                    "type":"click",
                    "name":"我要表白",
                    "key":"SendLove"
                }
                ]
        }';
        $a='
        {
            "button":[
                {
                    "type":"click",
                    "name":"查看表白",
                    "key":"ToView"
                },
                {
                    "type":"click",
                    "name":"我要表白",
                    "key":"SendLove"
                },
                {
                    "type":"view",
                    "name":"百度一下",
                    "url":"www.baidu.com"
                }
                ]
        }';
        $res=$this->curlPost($url,$post_data);
        // dd($res);
    }
    //点击查看表白
    public function ToView($xmlObj)
    {
        $user=$xmlObj->FromUserName;
        $Action=Action::insert(['open_id'=>$user,'action'=>'ToView','time'=>time()]);
        return true;
    }
    //点击我要表白
    public function SendLove($xmlObj)
    {
        $user=$xmlObj->FromUserName;
        $Action=Action::insert(['open_id'=>$user,'action'=>'SendLove','time'=>time()]);
        return true;
    }
}