<?php
namespace App\Http\Controllers;
use App\Model\Weeks;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mail;
class WeeksController extends Controller
{
	/*登陆首页*/
	public function index()
	{
		return view('weeks/index');
	}
	/*注册首页*/
	public function regis()
	{
		return view('weeks/regis');
	}
	/*注册处理页面*/
	public function reg(Request $request)
	{
		$all=request()->all();
		if($all['password']!=$all['repassword']){
			echo 1;
			//密码不一致
			die;
		}else{
			$rand=session('rand');
			if($rand!=$all['rand']){
				echo 2;
				//验证码不正确
				die;
			}else{
				unset($all['repassword']);
				$all['password']=md5(md5($all['password']));
				$data=Weeks::insert($all);
				if($data){
					echo 0;
				}else{
					echo 3;
					//添加失败
				}
			}
		}
	}
	/*验证邮箱唯一性*/
	public function checkemail(Request $request)
	{
		$username=request()->all();
		$data=Weeks::where($username)->get()->toArray();
		if(empty($data)){
			echo 2;
		}else{
			echo 1;
		}
	}
	/*发送邮箱*/
	 public function email(Request $request)
    {
    	$data=request()->input();
    	$res=$this->sendMail($data['email']);
    	$session=session('rand');
    	$time=session($session."time",time());
    	echo $session;
    }
    /*邮箱类*/
    public function sendMail($email)
    {
        //在闭包函数内部不能直接使用闭包函数外部的变量  使用use导入闭包函数外部的变量$username
        Mail::send('/Login/view',['1'=>rand(1111,9999)], 				          
        	function($message)use($email){
                      //设置主题
                      $message->subject("邮件标题");
                      //设置接收方
                    $message->to($email);
             });
	}
	/*登陆处理页面*/
	public function login(Request $request)
	{
		$isLogin=request()->input();
		// dd($isLogin);
        $isLogin['password']=md5(md5($isLogin['password']));
        // dd($isLogin);
        $Info=Weeks::where(['username'=>$isLogin['username']])->get();
        $res=$Info[0];
        if(!$Info){
        	return redirect('/login')->with('msg','账号或者密码错误');
        }else{}
        $result=Weeks::where($isLogin)->count();
        $now=time();
        if($result==1){
        	$data=Weeks::where($isLogin)->first();
        	if($res['errorcount'] >=3 && $now-$res['errortime']<3600){
        		$count=60-ceil( (time()-$res['errortime'])/60);
        		echo "请过".$count."分钟后重试";
        	}else{
        		$Weeks = Weeks::where(['username'=>$isLogin['username']])->first();
        		// dd($Weeks);
		        $Weeks->errorcount=0;
		        $Weeks->errortime=null;
		        $resInfo=$Weeks->save();
		        cache(['username'=>$isLogin['username']],60);
		        session()->put('login',$res);
		        return "登陆成功";
        	}
        }else{
        	if($now-$res['errortime']>3600){
        		$Weeks = Weeks::where(['username'=>$isLogin['username']])->first();
		        $Weeks->errorcount='1';
		        $Weeks->errortime=$now;
		        $resInfo =$Weeks->save();
		        return '密码错误1';
        	}else{
        		if($res['errorcount']>=5){
        			$count=60-ceil( ($now-$res['errortime'])/60);
        			return "账号已被锁定 请".$count."分钟后登陆";
        		}else{
        			$Weeks = Weeks::where(['username'=>$isLogin['username']])->first();
			        $Weeks->errorcount=$res['errorcount']+1;
			        $Weeks->errortime=$now;
			        $resInfo=$Weeks->save();
			        return '密码错误2';
        		}
        	}
        }
	}
	public function user()
	{
		$username=cache('username');
		if(!$username){
			echo "没走缓存";
        }
        $Weeks = Weeks::where(['username'=>$username])->first();
        // dd($Weeks);
		return view('weeks/user',compact('username','Weeks'));
	}
}
?>