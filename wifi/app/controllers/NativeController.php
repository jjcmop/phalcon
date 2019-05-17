<?php
use Phalcon\Mvc\Controller;
use Phalcon\Paginator\Adapter\Model as PaginatorModel;
class NativeController extends Controller{
    //NATIVE首页banner
    public function bannerAction(){
        $zs_banner = new Zs_banner();
        $arrData = $zs_banner::find();
        return $this->response->setJsonContent($arrData);
        $this->view->disable();
    }
    //NATIVE首页文章
    public function newsAction(){
        $zs_banner = new Zs_news();
        $arrData = $zs_banner::find();
        $currentPage = empty($_GET["page"])?"1":$_GET["page"];
        $currentShowPage = empty($_GET["showpage"])?"9":$_GET["showpage"];
        $paginator = new PaginatorModel(["data"  => $arrData,"limit" => $currentShowPage,"page"  => $currentPage]);
        $page = $paginator->getPaginate();
        foreach ($page->items as $key=>$value){
            $page->items[$key]->content = mb_substr(strip_tags(htmlspecialchars_decode($value->content)),0,60,"utf-8");
        }
        return $this->response->setJsonContent($page->items);
        $this->view->disable();
    }
    //NATIVE帖子类型版块
    public function cardindexAction(){
        $zs_card_type = new Zs_card_type();
        $zs_card = new Zs_card();
        $arrData = $zs_card_type::find()->toArray();  //需要联合帖子查询数量
        foreach ($arrData as $key=>$value){
            $arrData[$key]['num'] = count($zs_card::find(" t_id = {$value['id']} "));;
        }
        return $this->response->setJsonContent($arrData);
        $this->view->disable();
    }
    //WAP文章内容页
    public function cardcontentAction(){
        $id = $this->request->get("id");
        $zs_news = new Zs_news();
        $card = $zs_news::findFirstById($id);
        $card->content = htmlspecialchars_decode($card->content);
        $this->view->setVar("card", $card);
    }
    //NATIVE文章内容页
    public function cardcontent2Action(){
        $id = $this->request->get("id");
        $zs_news = new Zs_news();
        $zs_user = new Zs_user();
        $zs_collection = new Zs_collection();
        $zs_feedback = new Zs_feedback();
        $card = $zs_news::find($id)->toArray();
        if(!empty($card)){
            $card[0]['content'] = "";
            $card[0]['collection_num'] = count($zs_collection::find( " n_id = {$id} " ));
            $card[0]['cardfeed_num'] = count($zs_feedback::find(" n_id = {$id} "));
            $feedback = $this->modelsManager->createBuilder()
                ->columns("f.id as feed_id,f.body,f.feedtime,f.n_id,f.u_id,u.avatar,u.uid,u.nick_name")
                ->addfrom('zs_feedback',"f")
                ->leftjoin('zs_user','u.id = f.u_id',"u")
                ->where("f.n_id = {$id}")
                ->getQuery()
                ->execute()
                ->toArray();
            $card[0]['feedbackdata'] = $feedback;
            return $this->response->setJsonContent($card[0]);
        }else{
            echo json_encode(['code'=>1012,'message'=>"没有此文章"]);exit;
        }
        $this->view->disable();
    }
    //玩转游戏列表
    public function gameAction(){}
    //NATIVE 文章点赞
    public function cardclickAction(){
        $id = $this->request->get("id");
        if(!empty($id)){
            $zs_news = new Zs_news();
            $card = $zs_news::findFirstById($id);
            $click =(int)$card->click + 1;
            $card->content = "";
            $card->click = $click;
            $bool = $card->save();
            if($bool){
                return $this->response->setJsonContent(["code"=>0,"message"=>"ok"]);
            }else{
                return $this->response->setJsonContent(["code"=>500,"message"=>"系统繁忙，请稍候再试"]);
            }
            $this->view->disable();
        }else{
            return $this->response->setJsonContent(["code"=>1201,"message"=>"缺少文章ID"]);
        }
    }
    //NATIVE 文章评论
    public function cardfeedAction(){
        $data = file_get_contents("php://input");
        $reqdata = json_decode($data,true);
        if(!empty($reqdata)){
            $news['u_id'] = isset($reqdata['u_id'])?$reqdata['u_id']:"";
            $news['n_id'] = isset($reqdata['n_id'])?$reqdata['n_id']:"";
            $news['body']= isset($reqdata['body'])?$reqdata['body']:"";
            $news['feedtime'] = isset($reqdata['ts'])?$reqdata['ts']:time();
            $zs_feedback = new Zs_feedback();
            $bool = $zs_feedback->save($news);
            if($bool){
                echo json_encode(["code"=>0,"message"=>"ok"]);
            }else{
                echo json_encode(["code"=>500,"message"=>"系统繁忙，请稍候再试"]);
            }
        }else{
            echo json_encode(["code"=>104,"message"=>"请求方式错误"]);
        }
    }
    //NATIVE 收藏
    public function collectionAction(){
        $data = file_get_contents("php://input");
        $reqdata = json_decode($data,true);
        if(!empty($reqdata)){
            $news['u_id'] = isset($reqdata['u_id'])?$reqdata['u_id']:"";
            $news['n_id'] = isset($reqdata['n_id'])?$reqdata['n_id']:"";
            $news['n_title']= isset($reqdata['n_title'])?$reqdata['n_title']:"";
            $zs_collection = new Zs_collection();
            $arrData = $zs_collection::findFirst(" u_id = {$news['u_id']} and n_id = {$news['n_id']} ");
            if(!empty($arrData)){
                //取消收藏操作
                $bool = $arrData->delete();
            }else{
                //加入收藏操作
                $zs_collection->u_id =  $news['u_id'];
                $zs_collection->n_id =  $news['n_id'];
                $zs_collection->n_title =  $news['n_title'];
                $bool = $zs_collection->save();
            }
            if($bool){
                return json_encode(["code"=>0,"message"=>"ok"]);
            }else{
                return json_encode(["code"=>500,"message"=>"系统繁忙，请稍候再试"]);
            }
        }else{
            echo json_encode(["code"=>104,"message"=>"请求方式错误"]);
        }
    }
    //WAP注册协议
    public function agreementAction(){ }
    //NATIVE用户基本信息修改SDK接口
    public function usereditAction(){
        if($this->request->isPost()){
            $reqdata = json_decode($_POST['json'],true);
            $zs_user = new Zs_user();
            $id = $reqdata['id'];
            $resultdb = $zs_user::findFirstById($id);
            if(!empty($resultdb)){
                $resultdb->nick_name = $reqdata['nick_name'];
                $resultdb->sex = $reqdata['sex'];
                $resultdb->location_province = $reqdata['location_province'];
                $resultdb->location_city = $reqdata['location_city'];
                $resultdb->u_sign = $reqdata['u_sign'];
                $resultdb->my_pet = $reqdata['my_pet'];
                $resultdb->pet_breed = $reqdata['pet_breed'];
                if ($this->request->hasFiles() == true) {
                    $time = time();
                    $dir = BASE_PATH."/public/user/".$time;
                    if(!file_exists($dir)){
                        mkdir($dir,0777,true);
                    }
                    foreach ( $this->request->getUploadedFiles('my_avatar') as $files){
                        $fileArr = $time."/".$files->getName();
                        $files->moveTo($dir."/".$files->getName());
                    }
                    $resultdb->avatar = $fileArr;
                }
                $bool = $resultdb->save();
                if($bool){
                    echo json_encode(["code"=>0,"message"=>"success"]);
                }else{
                    echo json_encode(["code"=>500,"message"=>"系统繁忙，请稍候再试"]);
                }
            }else{
                echo json_encode(['code'=>1004,'message'=>"手机号不存在，请注册"]);exit;
            }
        }else{
            echo json_encode(["code"=>104,"message"=>"请求方式错误"]);
        }

    }
    //NATIVE宠物品种SDK接口
    public function petbreedAction(){
        $pid = $this->request->get("p_id");
        if(!empty($pid)){
            $zs_pet_breed = new Zs_pet_breed();
            $result = $zs_pet_breed::find( "p_id = {$pid}")->toArray();
            if(!empty($result)){
                $builder = $zs_pet_breed::find( "p_id = {$pid}");
                $page = empty($_GET["page"])?"1":$_GET["page"];
                $showpage = empty($_GET["showpage"])?"9":$_GET["showpage"];
                $paginator = new PaginatorModel(["data"  => $builder,"limit" => $showpage,"page"  => $page]);
                $pagedata = $paginator->getPaginate();
                echo json_encode($pagedata->items);
            }else{
                echo json_encode(['code'=>1011,'message'=>"没有此品种"]);exit;
            }
        }else{
            echo json_encode(['code'=>1010,'message'=>"缺少品种ID"]);exit;
        }
    }

    //h5分享页面
    public  function shareAction(){

    }
}

