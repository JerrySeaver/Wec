<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Redis;
use App\Model\Goods;
use App\Model\Users;
use App\Model\Buy;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\Favorites;
use App\Model\Historys;
use App\Model\Add;
use App\Model\Area;
use App\Model\Orders;
use \Log;
use App\Model\OrdersAdd;
use App\Model\OrdersGoods;
class IndexController extends Controller
{
    /*判断是否登陆*/
     public function Loginis()
    {
        $session=session('login');
        if($session){
            return false;
        }
        return true;
    }
    /*用户id*/
	/*首页*/
    public function index(Request $request)
    {
    	$result=$request->all();
        $goods_name=$result['goods_name']??'';
    	$GoodsModel=new Goods;
    	//搜索
        $where=[];
        if($goods_name){
            $where=[
            	['goods_name','like',"%$goods_name%"],                              
            ];
    		$data=$GoodsModel->where($where)->get();
        }else{
    		$data=$GoodsModel->orderBy('goods_id', 'desc')->take(10)->get();
        }
        //小图标
    	$res=$GoodsModel->take(4)->get();
    	//数量
    	$count=$GoodsModel->count();
        $loginIs=$this->Loginis();
    	return view('/index/index',compact('data','res','count','loginIs'));
    }
    /*所有商品页面*/
    public function prolist(Request $request)
    {
    	//所有商品点击条件
    	$res=request()->all();
    	$GoodsModel=new Goods;
    	$where=[];
    	$whereEr=[];
    	$goods_name=request()->goods_name;

    	if($goods_name){
    		$whereEr=[
    			['goods_name','like',"%$goods_name%"],
    		];
    	}
    	if(empty($res['prolist'])){

    		$data=$GoodsModel->where($whereEr)->get();
    		return view('index/prolist',compact('data','goods_name'));

    	}else if($res['prolist']=='news'){

    		$data=$GoodsModel->where($whereEr)->get();
    		return view('index/div',compact('data','goods_name'));

    	}else if($res['prolist']=='number'){

    		$data=$GoodsModel->where($whereEr)->orderBy('goods_id', 'desc')->get();
    		return view('index/div',compact('data','goods_name'));

    	}else if($res['prolist']=='price'){

			$data=$GoodsModel->where($whereEr)->orderBy('shop_price', 'desc')->get();
    		return view('index/div',compact('data','goods_name'));

    	}
    	
    	return view('index/prolist',compact('data','goods_name'));
    }
    /*商品详情页*/
    public function proinfo(Request $request)
    {
    	$Goods_id=Request()->all();
        $data=cache('datas_'.$Goods_id['goods_id']);
        if(!$data){
            $GoodsModel=new Goods;
            $data=$GoodsModel->where($Goods_id)->get();
            cache(['datas_'.$Goods_id['goods_id']=>$data],60*24);
        }
    	return view('index/proinfo',compact('data'));
    }
    /*个人中心*/
    public function user()
    {
        $this->isLogin();
        $session=session('login');
        $id=($session['id']);
        $sanm=cache('User_'.$session['id']);
        if(!$sanm){
            $data=Users::where(['id'=>$id])->get();
            if($data[0]['name']){
                $sanm=$data[0]['name'];
            }else{
                $sanm=$data[0]['email'];
            }
            cache(['User_'.$session['id']=>$sanm],60*24);
        }
        
    	return view('user/user',compact('sanm'));
    }
    /*判断是否登陆*/
    public function isLogin()
    {
        $session=session('login');
        if($session){
            return redirect('/login')->with('msg','请先登录');
        }
        return view('login/login');
    }
    /*购物车列表*/
    public function car()
    {
        if($this->isLogin()){
            $data=$this->DbCar();
        }else{
            $data=$this->CookieCar();
        }
        //计算总价格
        $count=[];
        foreach ($data as $key => $value) {
            $count[$key]=$value['shop_price'];
            $ges=$key+1;
        }
        if(!isset($ges)){
            $ges="0";
        }
        $count=array_sum($count);
        return view('index/car',compact('data','count','ges'));
    }
    /*登陆后的购物车*/
    public function DbCar()
    {
        $id=session('login');
        $where=[
            ['id'=>$id['id']],
            ['is_del'=>1],
        ];
        $Buy=Buy::where(['id'=>$id['id'],'is_del'=>1])->get()->toarray();
        if(!empty($Buy)){
            foreach ($Buy as $k=> $v) {
                $goods[]=Goods::where(['goods_id'=>$Buy[$k]['goods_id']])->get()->toarray();
                // dump($goods[$k][0]);

                $data[$k]=array_merge($goods[$k][0],$Buy[$k]);
            }
            return $data;  
        }else{
            return $Buy=[];
        } 
    }
    /*未登录的购物车*/
    public function CookieCar()
    {


    }
    /*获取小计*/
    public function CountPrice(Request $request)
    {
        $goods_id=request()->goods_id;
        $goods_number=request()->goods_number;
        if($this->isLogin()){
            $data=$this->CountPriceDb($goods_id,$goods_number);
        }else{
            $data=$this->CountPriceCookie();
        }
    }
    /*登陆后的小计*/
    public function CountPriceDb($goods_id,$goods_number)
    {
        $data=Goods::where(['goods_id'=>$goods_id])->get();
        $count=$data[0]['shop_price']*$goods_number;
        $id=session('login');
        $where=[
            ['goods_id','=',$goods_id],
            ['id','=',$id['id']],
            ['is_del','=',1]
        ];
        $data = Buy::where($where)->update(['number'=>$goods_number]);
        echo $count;
    }
    /*计算总价格*/
    public function checkCounts(Request $request)
    {
        $goods_id=request()->goods_id;
        if($this->isLogin()){
            $data=$this->checkCountsDb($goods_id);
        }else{
            $data=$this->CountPriceCookie($goods_id);
        }
        return $data;
    }
    /*登陆后的总价格*/
    public function checkCountsDb($goods_id)
    {
        $id=session('login');
        $goods_id=explode(',',$goods_id);
        $BuyModel=new Buy;
        $GoodsModel=new Goods;
        $counter=0;
        foreach ($goods_id  as $k => $v) {
            $where=[
                ['goods_id','=',$v],
                ['is_del','=',1],
                ['id','=',$id['id']]
            ]; 
            $a=$BuyModel->where($where)->get()->toArray();
            $BuyInfo[$k]=$a[0];
            $number[$k]=$BuyInfo[$k]['number'];
            $b=$GoodsModel->where(['goods_id'=>$v])->get()->toArray();
            $GoodsInfo[$k]=$b[0];
            $price[$k]=$GoodsInfo[$k]['shop_price'];
            // $price+=$number[$k]*$price[$k];
            $counter+=$price[$k]*$number[$k];
        }
        return $counter;
    }
    /*未登录的总价格*/
    public function CountPriceCookie($goods_id)
    {
    }
    /*点击加入购物车*/
    public function OneBuy(Request $request)
    {
        $goods_id=request()->goods_id;
        $loginIs=$this->Loginis();
        if(!$loginIs){
            $this->OneBuyDb($goods_id);
        }else{
            $this->OneBuyCookie($goods_id);
        }
    }
    /*登陆后的添加购物车*/
    public function OneBuyDb($goods_id)
    {
        $id=session('login');
        $Buy=new Buy;
        $res=$Buy->where(['goods_id'=>$goods_id,'id'=>$id['id'],'is_del'=>1])->get()->toArray();
        if($res){
            $data = Buy::find($res[0]['buy_id']);
            $data->number=$res[0]['number']+1;
            $ress =$data->save();
            if($ress){
                echo 1;
            }else{
                echo 2;
            }
        }else{
            $where=['goods_id'=>$goods_id,'number'=>1,'id'=>$id['id'],'is_del'=>1];
            $result=$Buy->insert($where);  
            if($result){
                echo 1;
            }else{
                echo 2;
            }
        }
    }
    /*未登录的添加购物车*/
    public function OneBuyCookie($goods_id)
    {


    }
    /*收藏商品*/
    public function Favorites(Request $request)
    {
        $goods_id=request()->goods_id;
        if($this->isLogin()){
            $id=session('login');
            $FavorInfo=Favorites::where(['goods_id'=>$goods_id,'id'=>$id['id']])->get()->toArray();
            if(empty($FavorInfo)){
                $where=['goods_id'=>$goods_id,'id'=>$id['id'],'is_del'=>1];
                $result=Favorites::insert($where); 
                if($result){
                    echo 1;
                }else{
                    echo 3;
                }
            }else{
                echo 2;
            }
        }else{
            echo 0;
        }
    }
    /*收藏显示页面*/
    public function shoucang()
    {
        $id=session('login');
        $Favor=Favorites::where([['id','=',$id['id']],['is_del','=',1]])->get()->toArray();
        if($Favor){
            $count=Favorites::where([['id','=',$id['id']],['is_del','=',1]])->count();
            foreach ($Favor as $key => $val) {
                $a[]=Goods::where('goods_id','=',$val['goods_id'])->get()->toArray(); 
                $goods[$key]=$a[$key][0];
                $whereEr=[
                    ['f_id','=',$val['f_id']],
                    ['is_del','=',1]
                ];
                $b[]=Favorites::where($whereEr)->get()->toArray();
                $FavorInfos[]=$b[$key][0];
                // dump($b);
                $FavorInfo[]=array_merge($goods[$key],$FavorInfos[$key]);
            }
        }else{
            $FavorInfo=[];
        }
        return view('/user/shoucang',compact('FavorInfo','count'));
    }
    /*取消收藏*/
    public function shoucangOn(Request $request)
    {
        $f_id=request()->f_id;
        $Favorites = Favorites::find($f_id);
        $Favorites->is_del=2;
        $res =$Favorites->save();
        if($res){
            echo 1;
        }else{
            echo 2;
        }
    }
    /*浏览记录*/
    public function history(Request $request)
    {
        $LoginIs=$this->Loginis();
        $goods_id=request()->goods_id;
        if(!$LoginIs){
            $this->historyDb($goods_id);
        }else{
            $this->historyCookie($goods_id);
        }
    }
    /*登陆的浏览记录*/
    public function historyDb($goods_id)
    {
        $id=session('login');
        $where=[
            ['id','=',$id['id']],
            ['goods_id','=',$goods_id],
            ['is_del','=',1]
        ];
        $res=Historys::where($where)->get()->toArray();
        if(empty($res)){
            $Historys=new Historys;
            $whereEr=['id'=>$id['id'],'goods_id'=>$goods_id,'is_del'=>1,'time'=>time()];
            $res=$Historys->insert($whereEr);
            dump($res);
        }else{
            $data = Historys::find($res[0]['h_id']);
            $data->time=time();
            $ress =$data->save();
        }
    }
    /*未登陆的浏览记录*/
    public function historyCookie($goods_id)
    {


    }
    /*浏览记录显示页面*/
    public function historys()
    {
        $id=session('login');
        $ress=Historys::where(['id'=>$id['id'],'is_del'=>1])->orderBy('time','desc')->get()->toArray();
        foreach ($ress as $key => $val) {
           $a[]=Goods::where(['goods_id'=>$val['goods_id']])->get()->toArray();
           $result[$key]=$a[$key][0];
           $res[$key]=array_merge($result[$key],$val);
        }
        return view('/index/history',compact('res'));
    }
    /*删除收藏记录*/
    public function HistoryIn(Request $request)
    {
        $h_id=request()->h_id;
        $Historys = Historys::find($h_id);
        $Historys->is_del=2;
        $res =$Historys->save();
        if($res){
            echo 1;
        }else{
            echo 2;
        }
    }
    /*结算之前判断是否登陆*/  
    public function ispay(Request $request)
    {
        $is=session('login');
        if(empty($is)){
            echo 1;
        }else{
            echo 2;
        }
    }
    /*确认结算页面*/
    public function pay(Request $request)
    {

        $result=request()->all();
        if(empty($result)){
            echo "111";
            return false;
        }else{
            $user_id=session('login');
            $add=Add::where(['id'=>$user_id['id'],'is_default'=>1])->get()->toArray();
            foreach($add as $k=>$v){
                $add[$k]['province']=Area::where(['id'=>$v['province']])->value('name');
                $add[$k]['city']=Area::where(['id'=>$v['city']])->value('name');
                $add[$k]['area']=Area::where(['id'=>$v['area']])->value('name');
            }
            $Goods=[];
            $goods_id=$result['goods_id'];
            $goodsId=explode(',',$goods_id);
            $id=session('login');
            $count=null;
            foreach ($goodsId as $k => $v) {

               $arrayGoods[$k]=Goods::where(['goods_id'=>$v])->get()->toArray();
               $arrayBuy[$k]=Buy::where(['is_del'=>1,'id'=>$id['id'],'goods_id'=>$v])->get()->toArray();
               $GoodsInfo[$k]=$arrayGoods[$k][0];
               $buyInfo[$k]=$arrayBuy[$k][0];
               $Info[$k]=array_merge($GoodsInfo[$k],$buyInfo[$k]);
               $price=$GoodsInfo[$k]['shop_price'];
               $number=$buyInfo[$k]['number'];
               $count+=$price*$number;
            }
            $resAdd=Add::where(['id'=>$id['id'],'is_default'=>1])->get()->toArray();
            return view('index/pay',compact('Info','count','goods_id','resAdd','add'));   
        }
    }
    /*地址显示页面*/
    public function address()
    {
        $user_id=session('login');
        $add=Add::where(['id'=>$user_id['id'],'is_del'=>1])->get()->toArray();
        foreach($add as $k=>$v){
            $add[$k]['province']=Area::where(['id'=>$v['province']])->value('name');
            $add[$k]['city']=Area::where(['id'=>$v['city']])->value('name');
            $add[$k]['area']=Area::where(['id'=>$v['area']])->value('name');
        }
        return view('/index/address',compact('add'));
    }
    /*查询地址*/
    public function addressadd()
    {
        // $user_id=session('login');
        $areaInfo=Area::where(['pid'=>0])->get()->toArray();
        return view('/index/addressadd',compact('areaInfo'));
    }
    /*三级联动*/
    public function AddProvince(Request $request)
    {
        $pid=request()->pid;
        $Area=new Area;
        $areaInfo=$Area->where('pid','=',$pid)->get();
        // dump($pid);
        return $areaInfo;
    }
    /*地址添加处理页面*/
    public function doaddadd(Request $request)
    {
        $add=new Add;
        $user_id=session('login');
        $obj=request()->all();
        if($obj['obj']['is_default']==1){
            $where=[
                'id'=>$user_id['id'],
                'is_default'=>1,
                'is_del'=>1
            ];
           $ass=$add->where($where)->get()->toArray();
           if(empty($ass)){
                $where=[
  
                'province'=>$obj['obj']['province'],
                'city'=>$obj['obj']['city'],
                'area'=>$obj['obj']['area'],
                'address_detail'=>$obj['obj']['address_detail'],
                'address_tel'=>$obj['obj']['address_tel'],
                'is_default'=>$obj['obj']['is_default'],
                'id'=>$user_id['id']
            ];
            $result=$add->insert($where); 
            if($result){
                echo 1;
            }else{
                echo 2;
            }
           }else{
               $addInfo = $add->where(['add_id'=>$ass[0]['add_id']])->update(['is_default'=>2]);
               if($addInfo){
                    $where=[
                        'add_name'=>$obj['obj']['add_name'],
                        'province'=>$obj['obj']['province'],
                        'city'=>$obj['obj']['city'],
                        'area'=>$obj['obj']['area'],
                        'address_detail'=>$obj['obj']['address_detail'],
                        'address_tel'=>$obj['obj']['address_tel'],
                        'is_default'=>$obj['obj']['is_default'],
                        'id'=>$user_id['id']
                    ];
                    $result=$add->insert($where); 
                    if($result){
                        echo 1;
                    }else{
                        echo 2;
                    }
               }else{
                echo 22;
               }
           }
        }else{
            $where=[
                'add_name'=>$obj['obj']['add_name'],
                'province'=>$obj['obj']['province'],
                'city'=>$obj['obj']['city'],
                'area'=>$obj['obj']['area'],
                'address_detail'=>$obj['obj']['address_detail'],
                'address_tel'=>$obj['obj']['address_tel'],
                'is_default'=>$obj['obj']['is_default'],
                'id'=>$user_id['id']
            ];
            $result=$add->insert($where); 
            if($result){
                echo 1;
            }else{
                echo 2;
            }
        }
    }
    /*修改地址显示页面*/
    public function upadd(Request $request)
    {
        $areaInfo=Area::where(['pid'=>0])->get()->toArray();
        /*查询省份*/
        $add_id=request()->add_id;
        $addInfo=Add::where(['add_id'=>$add_id])->get()->toArray();
        $addInfo=$addInfo[0];
        /*查询市区*/
        $cityInfo=Area::where(['pid'=>$addInfo['province']])->get()->toArray();
        // dump($cityInfo);
        /*地区*/
        $areaInfos=Area::where(['pid'=>$addInfo['city']])->get()->toArray();
        return view('index/upadd',compact('areaInfo','addInfo','cityInfo','areaInfos'));
    }
    /*修改处理页面*/
    public function doupadd(Request $request)
    {
        $Info=request()->all();
        $user_id=session('login');
        if(!empty($Info['obj']['is_default'])){
            $add=Add::where(['is_default'=>1,'id'=>$user_id['id']])->get()->toArray();
            $add_id=$Info['obj']['add_id'];
            if($add[0]['add_id']!=$Info['obj']['add_id']){
                $results = Add::find($add[0]['add_id']);
                $results->is_default=2;
                $ress =$results->save();
                // dd($results);
                if($ress){
                    $result = Add::find($Info['obj']['add_id']);
                    $result->add_name=$Info['obj']['add_name'];
                    $result->province=$Info['obj']['province'];
                    $result->city=$Info['obj']['city'];
                    $result->area=$Info['obj']['area'];
                    $result->address_detail=$Info['obj']['address_detail'];
                    $result->address_tel=$Info['obj']['address_tel'];
                    $result->is_default=$Info['obj']['is_default'];
                    $res =$result->save();
                        if($res){
                            echo 1;
                        }else{
                            echo 2;
                        }
                }
            }
        }else{
            $result = Add::find($Info['obj']['add_id']);
            $result->add_name=$Info['obj']['add_name'];
            $result->province=$Info['obj']['province'];
            $result->city=$Info['obj']['city'];
            $result->area=$Info['obj']['area'];
            $result->address_detail=$Info['obj']['address_detail'];
            $result->address_tel=$Info['obj']['address_tel'];
            $res =$result->save();
                if($res){
                    echo 1;
                }else{
                    echo 2;
                }
        }
    }
    /*提交订单*/
    public function issuccess(Request $request)
    {
        $Orders=new Orders;
        $OrdersAdd=new OrdersAdd;
        $OrdersGoods=new OrdersGoods;
        $Add=new Add;
        $Goods=new Goods;
        $goods_id=request()->goods_id;
        $id=session('login');
        /*添加订单表*/
        $isadd=Add::where(['id'=>$id['id'],'is_default'=>1])->first();
        if(empty($isadd)){
            echo '22';
            return;
        }
        $count=$this->checkCountsDb($goods_id);//计算总价格
        $order_number=rand(1111,9999).time().$id['id'];
        $whereOr=['order_amount'=>$count,'order_number'=>$order_number,'time'=>time(),'id'=>$id['id'],'is_status'=>1];
        $res=$Orders->insertGetId($whereOr);
        if($res){
            /*地址*/
            $resAdd=$Add->where(['id'=>$id['id'],'is_default'=>1])->get()->toArray();
            if(empty($resAdd)){
                echo '22';
                return;
            };
            $whereAd=[
                'add_name'=>$resAdd[0]['add_name'],
                'province'=>$resAdd[0]['province'],
                'city'=>$resAdd[0]['city'],
                'area'=>$resAdd[0]['area'],
                'address_detail'=>$resAdd[0]['address_detail'],
                'order_id'=>$res,
                'is_del'=>1,
                'id'=>$id['id'],
                'time'=>time()
            ];
            $resAd=$OrdersAdd->insertGetId($whereAd);
            if($resAd){
                $goods_id=explode(',',$goods_id);
                foreach ($goods_id as $k => $v) {
                    $a=$Goods->where(['goods_id'=>$v])->get()->toArray();
                    $buy_number=Buy::where(['goods_id'=>$v,'id'=>$id['id']])->get()->toArray();
                    // dd($buy_number);
                    $whereGo=[
                        'goods_id'=>$v,
                        'goods_name'=>$a[0]['goods_name'],
                        'shop_price'=>$a[0]['shop_price'],
                        'goods_img'=>$a[0]['goods_img'],
                        'buy_number'=>$buy_number[0]['number'],
                        'time'=>time(),
                        'is_del'=>1,
                        'order_id'=>$res,
                        'id'=>$id['id'],
                    ];
                    $resGo=$OrdersGoods->insert($whereGo);
                    //减库存
                    if($resGo){
                        $resNum=Goods::where(['goods_id'=>$v])->get()->toArray();
                        $resNums=Goods::where(['goods_id'=>$v])->update(['goods_number'=>$resNum[0]['goods_number']-$buy_number[0]['number']]);
                        //删购物车
                        if($resNums){
                            $resDels=Buy::where(['goods_id'=>$v,'id'=>$id['id']])->update(['is_del'=>2]);
                            if($resDels){
                                unset($count);
                               
                            }
                        }
                    }
                }

            }
            
        }
         return $res;
    }
    /*订单提交成功显示页面*/
    public function success(Request $request)
    {
        $order_id=request()->all();
        $Orders=Orders::where(['order_id'=>$order_id])->get();
        return view('index/success',compact('Orders'));
    }
    /*支付宝支付*/
    public function alipay(Request $request)
    {   
        $order_id=request()->order_id;
        $Orders=new Orders;
        $res=$Orders->where(['order_id'=>$order_id])->get();
        session()->put('order',$order_id);
        
        $config=config('aliPay');
        // dd($config);
        require_once app_path('libs/alipay/pagepay/service/AlipayTradeService.php');
        require_once app_path('libs/alipay/pagepay/buildermodel/AlipayTradePagePayContentBuilder.php');
        
        //商户订单号，商户网站订单系统中唯一订单号，必填
        $out_trade_no = $res[0]['order_number'];
    
        //订单名称，必填
        $subject = '刘瑞教育';

        //付款金额，必填
        $total_amount = $res[0]["order_amount"];

        //商品描述，可空
        $body = "";

        //构造参数
        $payRequestBuilder = new \AlipayTradePagePayContentBuilder();
        $payRequestBuilder->setBody($body);
        $payRequestBuilder->setSubject($subject);
        $payRequestBuilder->setTotalAmount($total_amount);
        $payRequestBuilder->setOutTradeNo($out_trade_no);

        $aop = new \AlipayTradeService($config);

        /**
         * pagePay 电脑网站支付请求
         * @param $builder 业务参数，使用buildmodel中的对象生成。
         * @param $return_url 同步跳转地址，公网可以访问
         * @param $notify_url 异步通知地址，公网可以访问
         * @return $response 支付宝返回的信息
        */
        $response = $aop->pagePay($payRequestBuilder,$config['return_url'],$config['notify_url']);

        //输出表单
        var_dump($response);
    }
    /*订单列表显示页面*/
    public function order(Request $request)
    {
        $user=session('login');
        $is_status=request()->is_status;
        if(isset($is_status)){
            $res=Orders::where(['id'=>$user['id'],'is_status'=>$is_status])->get()->toArray();
            foreach ($res as $k => $v) {
               $ress=OrdersGoods::where(['order_id'=>$v['order_id']])->get()->toArray();
            }
        }else{
            $res=Orders::where(['id'=>$user['id']])->get()->toArray();
            foreach ($res as $k => $v) {
               $ress=OrdersGoods::where(['order_id'=>$v['order_id']])->get()->toArray();
            }
        }
        return view('index/order',compact('res','ress'));
    }
    public function order_div(Request $request)
    {
        $user=session('login');
        $is_status=request()->is_status;
        if(isset($is_status)){
            $res=Orders::where(['id'=>$user['id'],'is_status'=>$is_status])->get()->toArray();
            foreach ($res as $k => $v) {
               $ress=OrdersGoods::where(['order_id'=>$v['order_id']])->get()->toArray();
            }
        }else{
            $res=Orders::where(['id'=>$user['id']])->get()->toArray();
            foreach ($res as $k => $v) {
               $ress=OrdersGoods::where(['order_id'=>$v['order_id']])->get()->toArray();
            }
        }
        return view('index/order_div',compact('res','ress'));
    }
    public function is_order(Request $request)
    {
        $user=session('login');
        $order_id=request()->order_id;
        $res=Orders::where(['id'=>$user['id'],'order_id'=>$order_id])->update(['is_status'=>5]);
        if($res){
            echo 1;
        }else{
            echo 0;
        }
    }
    public function show($id)
    {
        $data=cache('data_'.$id);
        if(!$data){
            $data=Goods::where(['goods_id'=>$id])->first()->toArray();
            cache(['data_'.$id=>$data],60*24);
        }
        $redis=new Redis;
        Redis::set('name','刘瑞');
        echo Redis::get('name');
    }
    /*支付宝同步 */
    public function return_url()
    {
        /* *
        * 功能：支付宝页面跳转同步通知页面
        * 版本：2.0
        * 修改日期：2017-05-01
        * 说明：
        * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。

        *************************页面功能说明*************************
        * 该页面可在本机电脑测试
        * 可放入HTML等美化页面的代码、商户业务逻辑程序代码
        */
        $config=config('aliPay');
        require_once app_path('libs/alipay/pagepay/service/AlipayTradeService.php');
        

        $arr=$_GET;
        
        $alipaySevice = new \AlipayTradeService($config); 
        $result = $alipaySevice->check($arr);
        /* 实际验证过程建议商户添加以下校验。
        1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
        2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
        3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
        4、验证app_id是否为该商户本身。
        */
        if($result) {//验证成功
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //请在这里加上商户的业务逻辑程序代码
            
            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
            //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表

            //商户订单号
            $where['order_number'] = htmlspecialchars($_GET['out_trade_no']);
            $where['order_amount'] = htmlspecialchars($_GET['total_amount']);
            $data=Orders::where($where)->get()->count();
            // dd($data);
            $json=json_encode($arr);
            if(!$data){
                Log::channel('aliPay')->info('订单号和金额不符合'.$json);
            }
            if(htmlspecialchars($_GET['seller_id'])!=config('aliPay.seller_id')||htmlspecialchars($_GET['app_id'])!=config('aliPay.app_id')){
                Log::channel('aliPay')->info("订单商户不符".$json);
            }
            
            //支付宝交易号
            $trade_no = htmlspecialchars($_GET['trade_no']);
            Log::channel('aliPay')->info("验证成功<br />支付宝交易号：".$trade_no);
        

            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
            
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        }
        else {
            //验证失败
            echo "验证失败";
        }
        echo 1;
    }
    /*支付宝异步 */
    public function notify_url()
    {
        /* *
        * 功能：支付宝服务器异步通知页面
        * 版本：2.0
        * 修改日期：2017-05-01
        * 说明：
        * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。

        *************************页面功能说明*************************
        * 创建该页面文件时，请留心该页面文件中无任何HTML代码及空格。
        * 该页面不能在本机电脑测试，请到服务器上做测试。请确保外部可以访问该页面。
        * 如果没有收到该页面返回的 success 信息，支付宝会在24小时内按一定的时间策略重发通知
        */

        $config=config('aliPay');
        require_once app_path('alipay/pagepay/service/AlipayTradeService.php');

        $arr=$_POST;
        $alipaySevice = new AlipayTradeService($config); 
        $alipaySevice->writeLog(var_export($_POST,true));
        $result = $alipaySevice->check($arr);

        /* 实际验证过程建议商户添加以下校验。
        1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
        2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
        3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
        4、验证app_id是否为该商户本身。
        */
        if($result) {//验证成功
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //请在这里加上商户的业务逻辑程序代

            
            //——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
            
            //获取支付宝的通知返回参数，可参考技术文档中服务器异步通知参数列表
            
            //商户订单号

            $out_trade_no = $_POST['out_trade_no'];

            //支付宝交易号

            $trade_no = $_POST['trade_no'];

            //交易状态
            $trade_status = $_POST['trade_status'];


            if($_POST['trade_status'] == 'TRADE_FINISHED') {

                //判断该笔订单是否在商户网站中已经做过处理
                    //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                    //请务必判断请求时的total_amount与通知时获取的total_fee为一致的
                    //如果有做过处理，不执行商户的业务程序
                        
                //注意：
                //退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
            }
            else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
                //判断该笔订单是否在商户网站中已经做过处理
                    //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
                    //请务必判断请求时的total_amount与通知时获取的total_fee为一致的
                    //如果有做过处理，不执行商户的业务程序			
                //注意：
                //付款完成后，支付宝系统发送该交易状态通知
            }
            //——请根据您的业务逻辑来编写程序（以上代码仅作参考）——
            echo "success";	//请不要修改或删除
        }else {
            //验证失败
            echo "fail";

        }
    }
    public function weChat(Request $request)
    {
        $echostr = $request -> echostr;
        echo $echostr;
    }
    public function a()
    {
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
             $access=cache('access_token');
             if(!$access){
                 $ass=file_get_contents("https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx1f9161f806626795&secret=e145f36cee7a9f4ffc77305520fe4a89");
                 $ass=json_decode($ass,true);
                 $access=$ass['access_token'];
                 cache(['access_token'=>$access],60*2);
             }
             $msg.=cache('access_token');
             dd($msg);
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
         //语音
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
         //视频
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
         //音乐
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
    }
}
