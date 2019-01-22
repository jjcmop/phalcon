<?php
use Phalcon\Mvc\Controller;
use Phalcon\Paginator\Adapter\Model as PaginatorModel;
class CardController extends Controller{
    public function indexAction(){
        $filter = new Zs_card();
        $arrData = $filter::find();
        $currentPage = empty($_GET["page"])?"1":$_GET["page"];
        $paginator = new PaginatorModel(["data"  => $arrData,"limit" => 10,"page"  => $currentPage]);
        $page = $paginator->getPaginate();
        $this->view->setVar("page",$page);
    }
    public function delAction(){

    }
    public function classifyAction(){
        $zs_card_type = new Zs_card_type();
        $arrData = $zs_card_type::find();
        $this->view->setVar("arrData", $arrData);
    }
    public function addclassifyAction(){
         if($this->request->isPost()){
             $title = $this->request->getPost("title");
             $zs_card_type = new Zs_card_type();
             $zs_card_type->title = $title;
             if ($this->request->hasFiles() == true) {
                 $time = time();
                 $dir = BASE_PATH."/public/card/".$time;
                 if(!file_exists($dir)){
                     mkdir($dir,0777,true);
                 }
                 foreach ( $this->request->getUploadedFiles('pic') as $files){
                     $fileArr = "/card/".$time."/".$files->getName();
                     $files->moveTo($dir."/".$files->getName());
                 }
                 $zs_card_type->pic = $fileArr;
                 $bool = $zs_card_type->save();
                 statusUrl($bool,"添加成功","/card/classify","添加失败");
             }else{
                 error("缺少图片资源");
             }
             $this->view->disable();
             exit;
         }
    }
    public function classifydelAction(){
        $id = $this->request->get("id");
        $zs_card_type = new Zs_card_type();
        $result = $zs_card_type::findFirstById($id);
        $bool = $result->delete();
        if($bool){
            return $this->response->setJsonContent(['msg'=>"ok"]);
        }else{
            return $this->response->setJsonContent(['msg'=>"no"]);
        }
    }
    public function classifyeditAction(){
        $zs_card_type = new Zs_card_type();
        if($this->request->isPost()){
            $id = $this->request->getPost("id");
            $result = $zs_card_type::findFirstById($id);
            $result->title = $this->request->getPost("title");
            if ($this->request->hasFiles() == true) {
                $time = time();
                $dir = BASE_PATH."/public/card/".$time;
                if(!file_exists($dir)){
                    mkdir($dir,0777,true);
                }
                foreach ( $this->request->getUploadedFiles('pic') as $files){
                    $fileArr = "/card/".$time."/".$files->getName();
                    $files->moveTo($dir."/".$files->getName());
                }
                $result->pic = $fileArr;
                $bool = $result->save();
                statusUrl($bool,"修改成功","/card/classify","修改失败");
            }else{
                $bool = $result->save();
                statusUrl($bool,"修改成功","/card/classify","修改失败");
            }
            $this->view->disable();
            exit;
        }else{
            $id = $this->request->get("id");
            $result = $zs_card_type::findFirstById($id);
            $this->view->setVar("arrData", $result);
        }
    }
    //NATIVE 发帖
    public function addcardAction(){
        if($this->request->isPost()){
            $data = json_decode($_POST['json'],true);
            $zs_card = new Zs_card();
            $zs_card->u_id = $data['u_id'];
            $zs_card->t_id = $data['t_id'];
            $zs_card->content = $data['content'];
            $zs_card->click = 0;
            $zs_card->create_time = $data['ts'];
            $zs_card->lbs = $data['location_province']." ".$data['location_city'];
            if ($this->request->hasFiles() == true) {
                $time = time();
                $dir = BASE_PATH."/public/card/".$time;
                if(!file_exists($dir)){
                    mkdir($dir,0777,true);
                }
                $fileArr = [];
                foreach ( $this->request->getUploadedFiles('image') as $files){
                    $fileArr[] = $time."/".$files->getName();
                    $files->moveTo($dir."/".$files->getName());
                }
                $zs_card->card_pic = json_encode($fileArr,320);
            }else{
                $zs_card->card_pic = json_encode([]);
            }
            $bool = $zs_card->save();
            if($bool){
                echo json_encode(["code"=>0,"message"=>"success"]);
            }else{
                echo json_encode(["code"=>500,"message"=>"系统繁忙，请稍候再试"]);
            }
        }else{
            echo json_encode(["code"=>104,"message"=>"请求方式错误"]);
        }
    }
    //NATIVE 点赞
    public function clickAction(){
        $data = file_get_contents("php://input");
        $reqdata = json_decode($data,true);
        if(!empty($reqdata)){
            $u_id = $reqdata['u_id'];
            $c_id = $reqdata['c_id'];
            $zs_card = new Zs_card();
            //$cardData = $zs_card::findFirst(" u_id = {$u_id} and id = {$c_id}");
            $cardData = $zs_card::findFirst(" id = {$c_id}");
            if(!empty($cardData)){
                $cardData->click = (int)$cardData->click + 1;
                $bool = $cardData->save();
                $zs_card_click = new Zs_card_click();
                if($bool){
                    $zs_card_click->c_id = $c_id;
                    $zs_card_click->u_id = $u_id;
                    $zs_card_click->status = 1;
                    $zs_card_click->save();
                    echo json_encode(["code"=>0,"message"=>"success"]);
                }else{
                    echo json_encode(["code"=>500,"message"=>"系统繁忙，请稍候再试"]);
                }
            }else{
                echo json_encode(["code"=>1100,"message"=>"没有找到此帖"]);
            }
        }else{
            echo json_encode(["code"=>104,"message"=>"请求方式错误"]);
        }
    }
    //NATIVE 评论
    public function feedbackAction(){
        $data = file_get_contents("php://input");
        $reqdata = json_decode($data,true);
        if(!empty($reqdata)){
            $u_id = $reqdata['u_id'];
            $c_id = $reqdata['c_id'];
            $zs_card = new Zs_card();
            $cardData = $zs_card::findFirst(" id = {$c_id}");
            if(!empty($cardData)){
                $zs_card_feedback = new Zs_card_feedback();
                $zs_card_feedback->u_id = $u_id;
                $zs_card_feedback->c_id = $c_id;
                $zs_card_feedback->body = $reqdata['body'];
                $zs_card_feedback->feedtime = $reqdata['ts'];
                $bool = $zs_card_feedback->save();
                if($bool){
                    echo json_encode(["code"=>0,"message"=>"success"]);
                }else{
                    echo json_encode(["code"=>500,"message"=>"系统繁忙，请稍候再试"]);
                }
            }else{
                echo json_encode(["code"=>1100,"message"=>"没有找到此帖"]);
            }
        }else{
            echo json_encode(["code"=>104,"message"=>"请求方式错误"]);
        }
    }
    //NATIVE 列表（热门/关注/最新）版块
    public function listAction(){
        $u_id = $id = $this->request->get("u_id");
        $t_id = $id = $this->request->get("t_id");
        $zs_card = new Zs_card();
        $zs_user = new Zs_user();
        $zs_card_click = new Zs_card_click();
        $cardData = $zs_card::findByT_id($t_id)->toArray();
        if(!empty($cardData)){
            $builder = $this->modelsManager->createBuilder()
                ->columns("c.id,u.avatar,u.nick_name,u.uid,u.location_province,u.location_city,c.content,c.card_pic,c.click as click_num")
                ->addfrom('\zs_card',"c")
                ->leftjoin('zs_user','u.id = c.u_id',"u")
                ->where("c.t_id = {$t_id}")
                ->getQuery()
                ->execute();
            $currentPage = empty($_GET["page"])?"1":$_GET["page"];
            $currentShowPage = empty($_GET["showpage"])?"9":$_GET["showpage"];
            $paginator = new PaginatorModel(["data"  => $builder,"limit" => $currentShowPage,"page"  => $currentPage]);
            $page = $paginator->getPaginate();
            foreach($page->items as $key=>$value){
                $page->items[$key]->is_click = !empty($zs_card_click::find(" u_id = {$u_id} and c_id = {$value->id} ")->toArray()) ? 1 : 0;
            }
            echo json_encode($page->items);
        }else{
            echo json_encode(["code"=>1100,"message"=>"没有找到此帖"]);
        }
    }
    //NATIVE 帖子内容
    public function cotentAction(){
        $u_id = $id = $this->request->get("u_id");
        $c_id = $id = $this->request->get("c_id");
        $zs_card = new Zs_card();
        $zs_user = new Zs_user();
        $zs_card_click = new Zs_card_click();
        $zs_card_feedback = new Zs_card_feedback();
        $cardOne =  $zs_card::findFirstById($c_id)->toArray();
        if(!empty($cardOne)){
            $builder = $this->modelsManager->createBuilder()
                ->columns("c.id,u.uid,u.avatar,u.competence,u.nick_name,u.location_province,u.location_city,c.content,c.card_pic,c.click as click_num")
                ->addfrom('zs_card',"c")
                ->leftjoin('zs_user','u.id = c.u_id',"u")
                ->where("c.id = {$c_id}")
                ->getQuery()
                ->execute()
                ->toArray();
            $builder[0]['is_click'] = !empty($zs_card_click::find(" u_id = {$u_id} and c_id = {$c_id}")->toArray()) ? 1 : 0;
            $feedback = $this->modelsManager->createBuilder()
                ->columns("f.id as feedback_id,f.body,f.feedtime,f.c_id,f.u_id,u.avatar,u.uid,u.nick_name")
                ->addfrom('zs_card_feedback',"f")
                ->leftjoin('zs_user','u.id = f.u_id',"u")
                ->where("f.c_id = {$c_id}")
                ->getQuery()
                ->execute()
                ->toArray();
            $builder[0]['feedbackdata'] =  $feedback;
            echo json_encode($builder);
        }else{
            echo json_encode(["code"=>1100,"message"=>"没有找到此帖"]);
        }
    }

    public function testAction(){
        user_bp(32,"+5积分",time(),"登录",5);
    }
    //h5分享页面
    public  function shareAction(){

    }
}
