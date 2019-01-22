<?php

class ApiController extends BaseController{
    //登录SDK接口
    public function loginAction(){
        $reqdata = json_decode($this->data,true);
        $zs_user = new Zs_user();
        $arr['username'] = $reqdata['username'];
        $arr['ts'] = $reqdata['ts'];
        $resultdb = $zs_user::findFirstByMobile_num($arr['username']);
        if(empty($resultdb)){
            echo json_encode(['code'=>1004,'message'=>"手机号不存在，请注册"]);exit;
        }else if( ($resultdb->is_online == '0') && (($arr['ts'] - $resultdb->last_activity_date) < 604800 ) ){
            //7天之内免登录
            $resultdb->register_ip = server("REMOTE_ADDR");
            $resultdb->last_activity_date = $arr['ts'];
            $resultdb->save();
            //积分系统
            user_bp($resultdb->id,"+5积分",time(),"7天免登录",5);
            echo json_encode(['code'=>0,'message'=>"success","data"=>$resultdb]);exit;
        }else{
            $arr['password'] = $reqdata['password'];
            $arr['uuid'] = $reqdata['uuid'];
            $sign = $reqdata['sign'];
            $result = $this->issignAction($arr,$sign);
            if($result == "ok"){
                $res = $zs_user::findFirst(" mobile_num = {$arr['username']} and password = '{$arr['password']}' ");
                if($res){
                    $res->is_online = '0';
                    $res->register_ip = server("REMOTE_ADDR");
                    $res->last_activity_date = $arr['ts'];
                    $res->save();
                    //积分系统
                    user_bp($res->id,"+5积分",time(),"登录",5);
                    echo json_encode(['code'=>0,'message'=>"success","data"=>$res]);exit;
                }else{
                    echo json_encode(['code'=>1007,'message'=>"账号密码错误，请稍后再试"]);exit;
                }
            }else{
                echo json_encode(['code'=>100,'message'=>"签名错误"]);exit;
            }
        }
    }
    //注册SDK接口
    public function registerAction(){
        $reqdata = json_decode($this->data,true);
        $zs_user = new Zs_user();
        $arr['username'] = $reqdata['username'];
        $resultdb = $zs_user::findFirstByMobile_num($arr['username']);
        if(!empty($resultdb)){
            echo json_encode(['code'=>1003,'message'=>"手机号已经注册过，请登入"]);exit;
        }else{
            $arr['password'] = $reqdata['password'];
            $arr['uuid']= $reqdata['uuid'];
            $arr['ts'] = $reqdata['ts'];
            $sign = $reqdata['sign'];
            //验证sign
            $result = $this->issignAction($arr,$sign);
            if($result == "ok"){
                $user['uid'] =  $this->buildordernoAction();
                $user['account'] =  $arr['username'];
                $user['password'] =  $arr['password'];
                $user['mobile_num'] =  $arr['username'];
                $user['sex'] =  "保密";
                $user['nick_name'] =  "";
                $user['location_province'] =  0;
                $user['location_city'] =  0;
                $user['location_area'] =  0;
                $user['real_name'] =  "";
                $user['id_card'] =  "";
                $user['user_level'] = 0;
                $user['user_exp'] =  0;
                $user['lock_status'] =  '0';
                $user['register_time'] =  $arr['ts'];
                $user['register_ip'] =  server("REMOTE_ADDR");
                $user['last_activity_date'] =  $arr['ts'];
                $user['is_online'] =  '1';
                $user['avatar'] = '';
                $outtime = $this->get7dayAction($arr['ts']);
                $rand1 = range("a","z");
                $rand2 = range("0","9");
                $rand3 = range("A","Z");
                $randstr = $rand1[0].$rand2[0].$rand3[0];
                $basestr = $user['uid'].$outtime.$randstr;
                $user['access_token'] = hash_hmac("sha256",$basestr,self::APP_KEY);
                $bool = $zs_user->save($user);
                if($bool){
                    echo json_encode(['code'=>0,'message'=>"success","data"=>$user]);exit;
                }else{
                    echo json_encode(['code'=>1006,'message'=>"注册失败，请稍后再试"]);exit;
                }
            }
        }
    }
    //手机验证码登录
    public function phoneloginAction(){
        $reqdata = json_decode($this->data,true);
        $zs_user = new Zs_user();
        $arr['username'] = $reqdata['username'];
        $resultdb = $zs_user::findFirstByMobile_num($arr['username']);
        if(empty($resultdb)){
            echo json_encode(['code'=>1004,'message'=>"手机号不存在，请注册"]);exit;
        }else{
            $arr['dynamic_code'] = $reqdata['dynamic_code'];
            $arr['uuid'] = $reqdata['uuid'];
            $arr['ts'] = $reqdata['ts'];
            $sign = $reqdata['sign'];
            $result = $this->issignAction($arr,$sign);
            if($result){
                $locktime = time();
                $zs_short_message = new Zs_short_message();
                $telsvcode = $zs_short_message::findFirst(" phone = '{$arr['username']}' and code = '{$arr['dynamic_code']}' ");
                $datatime = $telsvcode->send_time;
                if( ($locktime - $datatime) < 600){
                    $datacode = $telsvcode->code;
                    $lockcode = $reqdata['dynamic_code'];
                    if($datacode == $lockcode){
                        $resultdb->is_online = '0';
                        $resultdb->register_ip = server("REMOTE_ADDR");
                        $resultdb->last_activity_date = $arr['ts'];
                        $resultdb->save();
                        //积分系统
                        user_bp($resultdb->id,"+5积分",time(),"7天免登录",5);
                        echo json_encode(['code'=>0,'message'=>"success","data"=>$resultdb]);exit;
                    }else{
                        echo json_encode(["code"=>1002,"message"=>"验证码错误"]);
                    }
                }else{
                    echo json_encode(["code"=>1005,"message"=>"验证码过期"]);
                }
            }else{
                echo json_encode(['code'=>100,'message'=>"签名错误"]);exit;
            }

        }
    }
    //退出SDK接口
    public function logoutAction(){
        $reqdata = json_decode($this->data,true);
        $zs_user = new Zs_user();
        $arr['username'] = $reqdata['username'];
        $resultdb = $zs_user::findFirstByMobile_num($arr['username']);
        if(empty($resultdb)){
            echo json_encode(['code'=>1000,'message'=>"请输入正确的手机号"]);exit;
        }else{
            $resultdb->is_online = '1';
            $resultdb->save();
            echo json_encode(['code'=>0,'message'=>"success"]);exit;
        }
    }
    //找回密码SDK接口
    public function forgetpasswordAction(){
        $reqdata = json_decode($this->data,true);
        $zs_user = new Zs_user();
        $arr['username'] = $reqdata['username'];
        $resultdb = $zs_user::findFirstByMobile_num($arr['username']);
        if(empty($resultdb)){
            echo json_encode(['code'=>1004,'message'=>"手机号不存在，请注册"]);exit;
        }else{
            $arr['dynamic_code'] = $reqdata['dynamic_code'];
            $zs_message = new Zs_short_message();
            $phonecode = $zs_message::findFirst(" phone = '{$arr['username']}' and code = '{$arr['dynamic_code']}' ");
            if(!empty($phonecode)){
                $arr['password'] = $reqdata['password'];
                if(!empty($arr['password'])){
                    $resultdb->password = $arr['password'];
                    $bool = $resultdb->save();
                    if($bool){
                        echo json_encode(["code"=>0,"message"=>"success"]);
                    }else{
                        echo json_encode(["code"=>500,"message"=>"系统繁忙，请稍候再试"]);exit;
                    }
                }else{
                    echo json_encode(['code'=>1008,'message'=>"	密码为空"]);exit;
                }
            }else{
                echo json_encode(['code'=>1002,'message'=>"验证码错误"]);exit;
            }
        }
    }
    /**
     * 发送验证码判断
     */
    public function sendvcodeAction() {
        $reqdata = json_decode($this->data,true);
        $phone = isset($reqdata['phone'])?$reqdata['phone']:"";
        $name = $phone;
        if (empty($phone) || empty($name)) {
            echo json_encode(['code'=>1000,'message'=>"请输入正确的手机号"]);exit;
        }
        $this->telsvcodeAction($phone);
    }
    /**
     * 发送手机安全码
     */
    public function telsvcodeAction($phone=null,$delay=10,$flag=true) {
        include APP_PATH."/core/Xigu.php";
        $zs_short_message = new Zs_short_message();
        if (empty($phone)) {
            echo json_encode(['code'=>1000,'message'=>"请输入正确的手机号"]);exit;
        }
        /// 产生手机安全码并发送到手机且存到session
        $rand = rand(100000,999999);
        $smsconfig = ['sms_set' => [
            'smtp' => 'MDAwMDAwMDAwMK62sG1_enZnf7HJmLHc',
            'smtp_account' => 'MDAwMDAwMDAwMLq5qLB_oIJnf4u73bDc',
            'smtp_password' => '273',
            'smtp_port' => '25615'
        ]];
        $xigu = new \app\core\Xigu($smsconfig);
        $param = $rand.",".$delay;
        $result = json_decode($xigu->sendSM($smsconfig['sms_set']['smtp_account'],$phone,$smsconfig['sms_set']['smtp_password'],$param),true);
        // 存储短信发送记录信息
        $result['create_time'] = time();
        $result['pid']=0;

        file_put_contents(dirname(__FILE__).'/code.txt', json_encode($result)."=====".$rand);

        $zs_short_message->phone = $phone;
        $zs_short_message->code = $rand;
        $zs_short_message->send_time = $result['create_time'];
        $zs_short_message->smsId = $result['smsId'];
        $zs_short_message->create_time = $result['create_time'];
        $zs_short_message->pid = $result['pid'];
        $status = ($result['code'] == 200) ? 1 : 0;
        $zs_short_message->status = $status;
        $zs_short_message->ratio = 0;

        $bool = $zs_short_message->save();

        if ($result['send_status'] != '000000') {
            echo json_encode(['code'=>1001,'message'=>'获取验证码频率过高，请稍后']);exit;
        }
        $telsvcode['code']=$rand;
        $telsvcode['phone']=$phone;
        $telsvcode['time']=$result['create_time'];
        $telsvcode['delay']=$delay;
        //$this->session->set("telsvcode", $telsvcode);
        if ($flag) {
            echo json_encode(['code'=>0,'message'=>"success"]);
        } else{
            echo json_encode(['code'=>0,'message'=>"success"]);
        }

    }
    /**
     * 手机验证码验证
     */
    public function ajaxsmsAction(){
        $reqdata = json_decode($this->data,true);
        $locktime = time();
        $zs_short_message = new Zs_short_message();
        $phone = $reqdata['phone'];
        $lockcode = $reqdata['dynamic_code'];
        $telsvcode = $zs_short_message::findFirst(" phone = '{$phone}' and code = '{$lockcode}' ");
        if($telsvcode){
            $datatime = $telsvcode->send_time;
            if( ($locktime - $datatime) < 600){
                $datacode = $telsvcode->code;
                if($datacode == $lockcode){
                    echo json_encode(["code"=>0,"message"=>"success"]);
                }else{
                    echo json_encode(["code"=>1002,"message"=>"验证码错误"]);
                }
            }else{
                echo json_encode(["code"=>1005,"message"=>"验证码过期"]);
            }
        }else{
            echo json_encode(["code"=>1002,"message"=>"验证码错误"]);
        }
    }
}