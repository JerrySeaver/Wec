<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Redis;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
class WechatController extends Controller
{   
    
    public function index(Request $request)
    {
        
    }
    private function checkSignature($request)
    {
        $signature = $request ->signature;
        $timestamp = $request ->timestamp;
        $nonce = $request ->nonce;
        $token = 'dgbxd';
        $tmpArr = array($timestamp, $nonce,$token);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $signature == $tmpStr){
            return true;
        }else{
            return false;
        }
    }
}
?>