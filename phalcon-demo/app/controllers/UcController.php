<?php
use Phalcon\Mvc\Controller;
use Phalcon\Paginator\Adapter\Model as PaginatorModel;
class UcController extends Controller
{
    public $data; //sdk接收数据
    const APP_KEY = "MDc2OWJkYWI0ZGJiMmMxMzBjNzA3MGQ5NTU0MDVkODE=";
    public function initialize(){
        $this->data = file_get_contents("php://input");
        if(empty($this->data)){
            echo json_encode(['code'=>104,'message'=>"请求方式错误"]);exit;
        }
    }
    //用户基本信息SDK接口
    public function userinfoAction(){
        $reqdata = json_decode($this->data,true);
        $zs_user = new Zs_user();
        $resultdb = $zs_user::findFirstByMobile_num($reqdata['phone_number'])->toArray();
        if(empty($resultdb)){
            echo json_encode(['code'=>1004,'message'=>"手机号不存在，请注册"]);exit;
        }else{
            $u_id = $resultdb['id'];
            $zs_tag = new Zs_tag();
            $zs_card = new Zs_card();
            $resultdb['groupid'] = 1;
            $resultdb['follow_num'] = 10;
            $resultdb['fans_num'] = 10;
            $resultdb['card_num'] = count($zs_card::find( " u_id = {$u_id} "));
            $resultdb['tagdata'] = $zs_tag::find([ 'conditions'=> "u_id = {$u_id}",'columns' => ['id','tag',"u_id"],'limit' => 10 ])->toArray();
            echo json_encode(['code'=>0,'message'=>"success","data"=>$resultdb]);exit;
        }
    }
    //用户删除标签SDK接口
    public function usertagdelAction(){
        $reqdata = json_decode($this->data,true);
        $zs_tag = new Zs_tag();
        $tag_id = $reqdata['tag_id'];
        $u_id = $reqdata['id'];
        $result = $zs_tag::findFirst(" id = {$tag_id} and u_id = {$u_id} ");
        if(!empty($result)){
            $bool = $result->delete();
            if($bool){
                echo json_encode(["code"=>0,"message"=>"success"]);
            }else{
                echo json_encode(["code"=>500,"message"=>"系统繁忙，请稍候再试"]);
            }
        }else{
            echo json_encode(["code"=>1008,"message"=>"没有找到此私有标签"]);
        }
    }
    //用户添加标签SDK接口
    public function usertagaddAction(){
        $reqdata = json_decode($this->data,true);
        $zs_tag = new Zs_tag();
        $zs_tag->tag = $reqdata['tag'];
        $zs_tag->u_id = $reqdata['id'];
        $bool = $zs_tag->save();
        if($bool){
            echo json_encode(["code"=>0,"message"=>"success"]);
        }else{
            echo json_encode(["code"=>500,"message"=>"系统繁忙，请稍候再试"]);
        }
    }
    //用户标签列表SDK接口
    public function usertagAction(){
        $reqdata = json_decode($this->data,true);
        $u_id = $reqdata['id'];
        $zs_tag = new Zs_tag();
        //获取系统标签
        $arrData1 = $zs_tag::find([ 'conditions'=> 'u_id = 0','columns' => ['id','tag',"u_id"],'limit' => [10,rand(3,35)] ])->toArray();
        if(!empty($arrData1)){
            echo json_encode($arrData1);
        }else{
            echo json_encode(['code'=>1009,'message'=>"标签为空"]);
        }
    }
    //用户空间列表SDK接口
    public function pzoneAction(){
        $reqdata = json_decode($this->data,true);
        $u_id = $reqdata['id'];
        $showpage = empty($reqdata["showpage"])?"10":$reqdata["showpage"];
        $page = empty($reqdata["page"])?"0":($reqdata["page"]-1)*$showpage;
        $zs_card = new Zs_card();
        $zs_user = new Zs_user();
        $zs_card_click = new Zs_card_click();
        $userinfo = $zs_user::findFirstById($u_id);
        if(!empty($userinfo)){
            $userinfocard = $this->modelsManager->createBuilder()
                ->columns("c.id as c_id,u.avatar,u.nick_name,c.lbs,c.content,c.card_pic,c.click as click_num,c.create_time")
                ->addfrom('zs_card',"c")
                ->leftjoin('zs_user','u.id = c.u_id',"u")
                ->where("u.id = {$u_id}")
                ->limit($showpage,$page)
                ->getQuery()
                ->execute()
                ->toArray();
            foreach ($userinfocard as $key=>$value){
                $userinfocard[$key]['is_click'] = !empty($zs_card_click::find(" u_id = {$u_id} and c_id = {$value['c_id']}")->toArray()) ? 1 : 0;
            }
            echo json_encode($userinfocard);
        }else{
            echo json_encode(["code"=>1004,"message"=>"手机号不存在，请注册"]);
        }
    }
    //用户添加好友SDK接口
    public function addfrinedAction(){
        $reqdata = json_decode($this->data,true);
        $zs_filter = new Zs_frined();
        $zs_filter->frined_id = $reqdata['follow_id'];
        $zs_filter->me_id = $reqdata['id'];
        $zs_filter->ts = $reqdata['ts'];
        $zs_filter->frined_account = $reqdata['follow_account'];
        $bool = $zs_filter->save();
        if($bool){
            echo json_encode(["code"=>0,"message"=>"success"]);
        }else{
            echo json_encode(["code"=>500,"message"=>"系统繁忙，请稍候再试"]);
        }
    }
    //用户查找好友SDK接口
    public function searchAction(){
        $reqdata = json_decode($this->data,true);
        $zs_user = new Zs_user();
        $account = $reqdata['account'];
        $result = $zs_user::find([
            'columns'=>['id','account'],
            'conditions' => 'account LIKE :kw:',
            'bind' => ['kw' => '%'.$account.'%']
        ])->toArray();
        echo json_encode(["code"=>"0","message"=>"success","data"=>$result]);
    }
    //用户我的好友SDK接口
    public function frinedAction(){
        $reqdata = json_decode($this->data,true);
        $u_id = $reqdata['id'];
        $zs_filter = new Zs_frined();
        $arrData = $zs_filter::find([ 'columns'=>['frined_id','frined_account'],'conditions'=>"me_id = {$u_id}"])->toArray();
        echo json_encode(["code"=>0,"message"=>"success","data"=>$arrData]);
    }
    //用户主页标签列表SDK接口
    public function usertaglistAction(){
        $reqdata = json_decode($this->data,true);
        $u_id = $reqdata['id'];
        $zs_tag = new Zs_tag();
        //获取私有标签
        $arrData2 = $zs_tag::find([ 'conditions'=> "u_id = {$u_id}",'columns' => ['id','tag',"u_id"],'limit' => 10 ]);
        if(!empty($arrData2)){
            echo json_encode($arrData2);
        }else{
            echo json_encode(['code'=>1009,'message'=>"标签为空"]);
        }
    }
}
