<?php

namespace App\Http\Controllers;
use App\Model\Users;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mail;
class LoginController extends Controller
{
	/*登陆视图*/
    public function Login()
    {
    	return view('/login/login');
    }
    /*注册视图*/
    public function reg()
    {
    	return view('/login/reg');
    }
    /*验证邮箱唯一性*/
    public function checkEmail(Request $request)
	{
		$email=request()->all();
		$data=Users::where($email)->count();
		if($data){
			echo 1;
		}else{
			echo 0;
		}
	}
    /*发送邮箱*/
    public function email(Request $request)
    {
    	$data=request()->input();
    	$res=$this->sendMail($data['email']);
    	$session=session('rand');
    	echo $session;
    }
    /*邮箱类*/
    public function sendMail($email)
    {
        //在闭包函数内部不能直接使用闭包函数外部的变量  使用use导入闭包函数外部的变量$email
        Mail::send('/Login/view',['1'=>rand(1111,9999)], 				          
        	function($message)use($email){
                      //设置主题
                      $message->subject("邮件标题");
                      //设置接收方
                    $message->to($email);
             });
	}
	/*添加用户处理*/
	public function isreg(Request $request)
	{
		$isreg=request()->input();
		$request->validate([
            'email'=>'required|unique:users',
            'password'=>'required',
            'rand'=>'required',
        ],[
            'email.required'=>'邮箱不可为空',
            'email.unique'=>'邮箱已存在',
            'password.required'=>'密码不可为空',
            'repassword.required'=>'请在此输入密码',
            'rand.required'=>'验证码不可为空',
        ]);
        $session=session('rand');
        // dd($session);
        if($session!=$isreg['rand']){
            return redirect('/reg')->with('msg','验证码不一致');
        }
        $isreg['password']=md5(md5($isreg['password']));
        $Users=users::insert($isreg);
        if($Users){
        	return redirect('rlogin')->with('msg','添加成功');
        }
	}
	/*登陆处理页面*/
	public function islogin(Request $request)
	{
		$isLogin=request()->input();
		// dd($isLogin);
		$request->validate([
            'email'=>'required',
            'password'=>'required',
        ],[
            'email.required'=>'邮箱不可为空',
            'password.required'=>'密码不可为空',
        ]);
        $isLogin['password']=md5(md5($isLogin['password']));
        // dd($isLogin);
        $Info=Users::where(['email'=>$isLogin['email']])->get();
        $res=$Info[0];
        if(!$Info){
        	return redirect('/login')->with('msg','账号或者密码错误');
        }
        $result=Users::where($isLogin)->count();
        $now=time();
        if($result==1){
        	$data=Users::where($isLogin)->first();
        	if($res['errorcount'] >=3 && $now-$res['errortime']<3600){
        		$count=60-ceil( (time()-$res['errortime'])/60);
        		echo "请过".$count."分钟后重试";
        	}else{
        		$Users = Users::where(['email'=>$isLogin['email']])->first();
        		// dd($Users);
		        $Users->errorcount=0;
		        $Users->errortime=null;
		        $resInfo=$Users->save();
		        //同步浏览记录
		        //同步购物车记录
		        //存session
		        session()->put('login',$res);
		        return "登陆成功";
        	}
        }else{
        	if($now-$res['errortime']>3600){
        		$Users = Users::where(['email'=>$isLogin['email']])->first();
		        $Users->errorcount='1';
		        $Users->errortime=$now;
		        $resInfo =$Users->save();
		        return '密码错误1';
        	}else{
        		if($res['errorcount']>=5){
        			$count=60-ceil( ($now-$res['errortime'])/60);
        			return "账号已被锁定 请".$count."分钟后登陆";
        		}else{
        			$Users = Users::where(['email'=>$isLogin['email']])->first();
			        $Users->errorcount=$res['errorcount']+1;
			        $Users->errortime=$now;
			        $resInfo=$Users->save();
			        return '密码错误2';
        		}
        	}
        }
	}
    public function outLogin(Request $request)
    {
       $request->session()->forget('login');
    }
    
}