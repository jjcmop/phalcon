<?php
use Phalcon\Mvc\Controller;
use Phalcon\Paginator\Adapter\Model as PaginatorModel;
use Phalcon\Http\Request;
class AdminController extends Controller
{
    public $user;
    public function initialize() {
        if($this->session->has("username")){
            $this->user = $this->session->get("username");
        }else{
            header("location:/login/login");exit;
        }
    }

    public function indexAction(){
        $filename = BASE_PATH."/public/menu.json";
        $jsonstr = file_get_contents($filename);
        $arr = json_decode($jsonstr,true);
        $this->view->setVar("username", $this->user);
        $this->view->setVar("arr", $arr);
    }

    public function newslistAction(){
        $zs_news = new Zs_news();
        $arrData = $zs_news::find();
        $currentPage = empty($_GET["page"])?"1":$_GET["page"];
        $paginator = new PaginatorModel(["data"  => $arrData,"limit" => 10,"page"  => $currentPage]);
        $page = $paginator->getPaginate();
        $this->view->setVar("page",$page);
    }

    public function newsaddAction(){
        $request = new Request();
        if($request->isPost()){
            if ($this->request->hasFiles() == true) {
                $data = $request->getPost();
                $data['content'] = htmlspecialchars($request->getPost("content"));
                $time = time();
                $dir = BASE_PATH."/public/upload/".$time;
                if(!file_exists($dir)){
                    mkdir($dir,0777,true);
                }
                $fileArr = [];
                foreach ( $this->request->getUploadedFiles('filename') as $files){
                    $fileArr[] = $time."/".$files->getName();
                    $files->moveTo($dir."/".$files->getName());
                }
                $data['pic'] = json_encode($fileArr,320);
                $data['create_time'] = time();
                $zs_news = new Zs_news();
                $bool = $zs_news->save($data);
                statusUrl($bool,"添加成功","/admin/newslist","添加失败");
            }else{
                success("缺少展示资源","/admin/newslist");
            }
            $this->view->disable();
            exit;
        }
    }

    public function newssearchAction(){
        $request = new Request();
        $search = $request->get("search");
        $zs_news = new Zs_news();
        $arrData = $zs_news::find("title LIKE '%$search%'");
        $currentPage = empty($_GET["page"])?"1":$_GET["page"];
        $paginator = new PaginatorModel(["data"  => $arrData,"limit" => 10,"page"  => $currentPage]);
        $page = $paginator->getPaginate();
        $this->view->setVar("page",$page);
    }

    public function newsdelAction(){
        $request = new Request();
        $id = $request->get("id");
        $zs_news = new Zs_news();
        $result = $zs_news::findFirstById($id);
        $bool = $result->delete();
        if($bool){
            return $this->response->setJsonContent(['msg'=>"ok"]);
        }else{
            return $this->response->setJsonContent(['msg'=>"no"]);
        }
        $this->view->disable();
    }

    public function uploadAction(){
        if(!empty($_FILES['file'])){
            $time = time();
            $dir = BASE_PATH."/public/upload/".$time;
            if(!file_exists($dir)){
                mkdir($dir,0777,true);
            }
            foreach ( $this->request->getUploadedFiles('file') as $file){
                $file->moveTo($dir."/".$file->getName());
            }
            echo json_encode(['code'=>0,'msg'=>"上传成功","data"=>['src'=>"http://".server("SERVER_ADDR")."/upload/".$time."/".$file->getName(),"title"=>$file->getName()] ]);
        }else{
            echo json_encode(['msg'=>"上传失败"]);
        }
    }

    public function bannerAction(){
        $zs_banner = new Zs_banner();
        $arrData = $zs_banner::find();
        $currentPage = empty($_GET["page"])?"1":$_GET["page"];
        $paginator = new PaginatorModel(["data"  => $arrData,"limit" => 10,"page"  => $currentPage]);
        $page = $paginator->getPaginate();
        $this->view->setVar("page",$page);
    }

    public function banneraddAction(){
        if($this->request->isPost()){
            if ($this->request->hasFiles() == true) {
                $time = time();
                $dir = BASE_PATH."/public/banner/".$time;
                if(!file_exists($dir)){
                    mkdir($dir,0777,true);
                }
                foreach ( $this->request->getUploadedFiles('banner') as $files){
                    $fileArr = "/banner/".$time."/".$files->getName();
                    $files->moveTo($dir."/".$files->getName());
                }
                $data['pic'] = $fileArr;
                $zs_banner = new Zs_banner();
                $bool = $zs_banner->save($data);
                statusUrl($bool,"添加成功","/admin/banner","添加失败");
            }else{
                success("缺少展示资源","/admin/banneradd");
            }
            $this->view->disable();
        }else{

        }
    }

    public function addcommentAction(){
        $zs_news = new Zs_news();
        $arrData = $zs_news::find();
        $this->view->setVar("arrData",$arrData);
    }

    public function feedbackAction(){
        $body = $this->request->getPost("body");
        $nid = $this->request->getPost("nid");
        $zs_feedback = new Zs_feedback();
        $zs_user = new Zs_user();
        $userData = $zs_user::find([ 'columns'=>['id'] ])->toArray();
        $zs_feedback->u_id = $userData[rand(0,count($userData)-1)]['id'];
        $zs_feedback->n_id = $nid;
        $zs_feedback->body = $body;
        $zs_feedback->feedtime = time();
        $bool = $zs_feedback->save();
        if($bool){
            echo json_encode(["msg"=>"成功"]);
        }else{
            echo json_encode(["msg"=>"失败"]);
        }

    }
}
