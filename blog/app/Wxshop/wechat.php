<?php


namespace App\Wxshop;


use Illuminate\Database\Eloquent\Model;


class wechat extends Model
{
    //图灵机器人
    public static function rbot($keywords,$url)
    {
        $data = [
            'reqType'=>0,
            'perception'=>[
                'inputText'=>[
                    'text'=>$keywords,
                ],
            ],
            'userInfo'=>[
                'apiKey'=>'3c833287bd65490280e9f95252099fbe',
                'userId'=>'Jerry'
            ]
        ];
        $post_data = json_encode($data,JSON_UNESCAPED_UNICODE);
        //初始化
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置头文件的信息作为数据流输出
        curl_setopt($curl, CURLOPT_HEADER, 0);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //设置post方式提交
        curl_setopt($curl, CURLOPT_POST, 1);
        //设置post数据
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
        //执行命令
        $data = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        //显示获得的数据
        $data = json_decode($data,true);
        return $data['results'][0]['values']['text'];
    }
}
