<?php
use Phalcon\Mvc\Controller;
use Phalcon\Http\Request;
use Phalcon\Paginator\Adapter\Model as PaginatorModel;
class SetController extends Controller
{
    public $set;
    public function initialize() {
        if($this->session->has("username")){
            $this->set = $this->session->get("username");
        }else{
            header("location:/login/login");exit;
        }
    }

    public function indexAction(){
        $filter = new Zs_filter();
        $arrData = $filter::find();
        $currentPage = empty($_GET["page"])?"1":$_GET["page"];
        $paginator = new PaginatorModel(["data"  => $arrData,"limit" => 15,"page"  => $currentPage]);
        $page = $paginator->getPaginate();
        $this->view->setVar("page",$page);
    }

    public function addindexAction(){
        if($this->request->isPost()){
            $word = $this->request->getPost("word");
            $filter = new Zs_filter();
            $filter->word = $word;
            $bool = $filter->save();
            statusUrl($bool,"添加成功","/set/index","添加失败");
            $this->view->disable();
        }
    }

    public function delindexAction(){
        $id = $this->request->get("id");
        $filter = new Zs_filter();
        $result = $filter::findFirstById($id);
        $bool = $result->delete();
        if($bool){
            return $this->response->setJsonContent(['msg'=>"ok"]);
        }else{
            return $this->response->setJsonContent(['msg'=>"no"]);
        }
        $this->view->disable();
    }

    public function searchAction(){
        $search = $this->request->get("search");
        $zs_filter = new Zs_filter();
        $result = $zs_filter::find([
            'columns'=>['id','word'],
            'conditions' => 'word LIKE :kw:',
            'bind' => ['kw' => '%'.$search.'%']
        ])->toArray();
        $this->view->setVar("result",$result);
    }

}
