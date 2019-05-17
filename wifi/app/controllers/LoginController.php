<?php
use Phalcon\Mvc\Controller;
use Phalcon\Http\Request;

class LoginController extends Controller
{
    public function loginAction(){
        $request = new Request();
        if($request->isPost()){
            $zs_system = new Zs_system();
            $u = addslashes($request->getPost("u"));
            $p = $this->hmac256(addslashes($request->getPost("p")));
            $conditons = ' user = :user: and pass = :pass: ';
            $parameters  = [ 'user' => $u, 'pass' => $p ];
            $result = $zs_system::findFirst([$conditons,'bind'=>$parameters])->toArray();
            if($u == $result['user'] && $p == $result['pass']){
                $this->session->set("username", $u);
                return $this->response->setJsonContent(['msg'=>"ok"]);
            }else{
                return $this->response->setJsonContent(['msg'=>"no"]);
            }
            $this->view->disable();
        }
    }

    public function logoutAction(){
        $this->session->remove("username");
        header("location:/login/login");
    }

    public function codeAction(){
        include APP_PATH."/core/Image.php";
        header("content-type:image/png");
        \app\core\Image::code(160,56,25,15,35,35,"/fonts/MSYHBD.TTC");
        $this->view->disable();
    }

    public function hmac256($data){
        return hash_hmac("sha256",$data,"MDc2OWJkYWI0ZGJiMmMxMzBjNzA3MGQ5NTU0MDVkODE=");
    }
}