<?php
use Phalcon\Mvc\Controller;
use Phalcon\Paginator\Adapter\Model as PaginatorModel;
use Phalcon\Http\Request;
class IndexController extends Controller
{
    const APP_KEY = "MDc2OWJkYWI0ZGJiMmMxMzBjNzA3MGQ5NTU0MDVkODE=";
    //宠爱之家官网首页
    public function indexAction(){
        $zs_news = new Zs_news();
        $newsData = $zs_news::find([ 'columns'=>['id','title','create_time','content','type'],'conditions'=>'type = 1','order'=>'id DESC','limit'=>5])->toArray();
        foreach ($newsData as $key=>$value){
            $newsData[$key]['content'] = mb_substr(strip_tags(htmlspecialchars_decode($value['content'])),0,60,"utf-8");
        }
        $this->view->setVar("newsData", $newsData);
    }
    //宠爱之家官网关于
    public function aboutAction(){

    }
    //宠爱之家官网文章详情
    public function detailAction(){
        $id = $this->request->get("id");
        $zs_news = new Zs_news();
        $oneData = $zs_news::findFirstById($id);
        $this->view->setVar("oneData", $oneData);
    }
    //宠爱之家官网新闻
    public function newsAction(){
        $zs_news = new Zs_news();
        $arrData = $zs_news::find(" type = 1 ");
        $currentPage = empty($_GET["page"])?"1":$_GET["page"];
        $paginator = new PaginatorModel(["data"  => $arrData,"limit" => 12,"page"  => $currentPage]);
        $page = $paginator->getPaginate();
        foreach ($page->items as $key=>$value){
            $page->items[$key]->content = mb_substr(strip_tags(htmlspecialchars_decode($value->content)),0,60,"utf-8");
        }
        $this->view->setVar("page",$page);
    }
    //宠爱之家官网救助站
    public function rescueAction(){

    }
    //文件上传
    public function uploadAction(){
        if(!empty($_FILES['file'])){
            $time = time();
            $dir = BASE_PATH."/public/merc/".$time;
            if(!file_exists($dir)){
                mkdir($dir,0777,true);
            }
            $fileArr = "";
            foreach ( $this->request->getUploadedFiles('file') as $file){
                $fileArr = "/merc/".$time."/".$file->getName();
                $file->moveTo($dir."/".$file->getName());
            }
            echo json_encode(["msg"=>"ok","data"=>$fileArr]);
        }else{
            echo json_encode(["msg"=>"no"]);
        }
    }
    //救助站资料
    public function rescueinfoAction(){

    }
    //登录
    public function loginAction(){

    }
    public function rescueeditAction(){
        $mobile_num = $this->request->get("mobile_num");
        $zs_user_merc = new Zs_user_merc();
        $oneData = $zs_user_merc::findFirstByMobile_num($mobile_num);
        $this->view->setVar("oneData",$oneData);
    }
    public function rescueinfo2Action(){
        $mobile_num = $this->request->get("mobile_num");
        $zs_user_merc = new Zs_user_merc();
        $oneData = $zs_user_merc::findFirstByMobile_num($mobile_num);
        $this->view->setVar("oneData",$oneData);
    }
    //救助站接收资料
    public function getuserdataAction(){
        $Zs_user_merc = new Zs_user_merc();
        $Zs_user_merc->create_time = time();
        $success = $Zs_user_merc->save($this->request->getPost(),['commercial','sex','real_name','id_card','mobile_num','location_province','location_city','location_area','address','info','idcard','reidcard','handidcard','permit','field1','field2','field3','field4']);
        if($success){
            echo json_encode(["msg"=>"ok"]);
        }else{
            echo json_encode(["msg"=>"no"]);
        }
    }
    public function edituserdataAction(){
        $Zs_user_merc = new Zs_user_merc();
        $id = $this->request->getPost("id");
        $result = $Zs_user_merc::findFirstById($id);
        $success = $result->save($this->request->getPost(),['commercial','sex','real_name','id_card','mobile_num','location_province','location_city','location_area','address','info','idcard','reidcard','handidcard','permit','field1','field2','field3','field4','status']);
        if($success){
            echo json_encode(["msg"=>"ok"]);
        }else{
            echo json_encode(["msg"=>"no"]);
        }
    }
    //救助站检查用户是否提交审核资料
    public function ajaxmobilenumAction(){
        if(!empty($_COOKIE['mobile_num'])){
            $mobile_num = $_COOKIE['mobile_num'];
            $zs_user = new Zs_user();
            $userData = $zs_user::find("mobile_num = {$mobile_num}")->toArray();
            if(empty($userData)){
                $user['uid'] =  $this->buildordernoAction();
                $user['account'] =  $mobile_num;
                $user['password'] =  $this->hmac256("");;
                $user['mobile_num'] =  $mobile_num;
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
                $user['register_time'] =  time();
                $user['register_ip'] =  server("REMOTE_ADDR");
                $user['last_activity_date'] =  time();
                $user['is_online'] =  '1';
                $user['avatar'] = '';
                $outtime = $this->get7dayAction(time());
                $rand1 = range("a","z");
                $rand2 = range("0","9");
                $rand3 = range("A","Z");
                $randstr = $rand1[0].$rand2[0].$rand3[0];
                $basestr = $user['uid'].$outtime.$randstr;
                $user['access_token'] = $this->hmac256($basestr);
                $user['competence'] = '普通用户';
                $user['competence'] = '普通用户';
                $user['competence'] = '普通用户';
                $zs_user->save($user);
            }
            $zs_user_merc = new Zs_user_merc();
            $arrData = $zs_user_merc::find("mobile_num = {$mobile_num}")->toArray();
            if(!empty($arrData)){
                //跳转审核页面
                header("location:/index/examine");
            }else{
                //跳转提交参数页面
                header("location:/index/rescueinfo");
            }
        }else{
            //跳转登录页面
            header("location:/index/login");
        }
    }

    public function logoutAction(){
        if(!empty($_COOKIE['mobile_num'])){
            setcookie("mobile_num","",time()-1);
            header("location:/index/login");
        }else{
            header("location:/index/login");
            exit;
        }
    }
    //求助站审核页面
    public function examineAction(){
       if(empty($_COOKIE['mobile_num'])){
           success("请先登录","/index/login");
           exit;
       }
    }
    /**
     * 发送验证码判断
     */
    public function sendvcodeAction() {
        $reqdata = $this->request->getPost("mobile_num");
        $phone = isset($reqdata)?$reqdata:"";
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
        $reqdata = $this->request->getPost();
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
                    setcookie('mobile_num',$phone,time()+60*60);
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

    public function buildordernoAction(){
        return "pet".date('YmdHis').substr(implode(array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8)."tap";
    }
    public function get7dayAction($timex){
        return strtotime(date("Y-m-d H:i:s",$timex+60*60*24*7));
    }
    public function hmac256($data){
        return hash_hmac("sha256",$data,"MDc2OWJkYWI0ZGJiMmMxMzBjNzA3MGQ5NTU0MDVkODE=");
    }

}
