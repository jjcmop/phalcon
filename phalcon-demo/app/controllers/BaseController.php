<?php
use Phalcon\Mvc\Controller;
use Phalcon\Http\Request;

class BaseController extends Controller{
    public $request; //wap接收数据
    public $data; //sdk接收数据
    const APP_KEY = "MDc2OWJkYWI0ZGJiMmMxMzBjNzA3MGQ5NTU0MDVkODE=";
    public function initialize(){
        //$this->request = new Request();
        $this->data = file_get_contents("php://input");
        if(empty($this->data)){
            echo json_encode(['code'=>104,'message'=>"请求方式错误"]);
        }
    }
    public function jsonmessageAction($code,$message,$reqdata){
        $resdata = ["code"=>$code,"message"=>$message,"data"=>$reqdata];
        return json_encode($resdata);
    }
    public function issignAction($lockdata,$sdkdata){
        $newdata = $this->signAction($lockdata);
        if($sdkdata != $newdata){
            echo json_encode(['code'=>100,'message'=>"签名错误"]);exit;
        }else{
            return "ok";
        }
    }
    public function signAction($data){
        $arrData = $this->argSortAction($data);
        $signstr = $this->strlinkAction($arrData);
        return hash_hmac("sha256",$signstr,self::APP_KEY);
    }
    public function strlinkAction($para) {
        $arg  = "";
        while (list ($key, $val) = each ($para)) {
            $arg.=$key."=".$val."&";
        }
        //去掉最后一个&字符
        $arg = substr($arg,0,count($arg)-2);
        //如果存在转义字符，那么去掉转义
        if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}
        return $arg;
    }
    public function argSortAction($para) {
        ksort($para);
        reset($para);
        return $para;
    }
    public function buildordernoAction(){
        return "pet".date('YmdHis').substr(implode(array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8)."tap";
    }
    public function get7dayAction($timex){
        return strtotime(date("Y-m-d H:i:s",$timex+60*60*24*7));
    }
}