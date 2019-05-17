<?php
use Phalcon\Mvc\Controller;

class SignupController extends Controller
{
    public function indexAction(){

    }

    public function registerAction(){
         $user = new Users();
         //添加与修改
         $success = $user->save($this->request->getPost(),['name','email']);

         if($success){
             echo "Thanks for registering!";
         }else{
             echo "Sorry, the following problems were generated: ";
             $messages = $user->getMessages();
             foreach ($messages as $message){
                 echo   $message->getMessage(),"<br />";
             }
         }

         $this->view->disable();
    }

    public function userlistAction(){
        $user = new Users();
        //多条数据查询(数组结果)
//        $result = $user::find()->toArray();
//        print_r($result);
        //多条数据查询
//        $result = $user::find();
//        $res = [];
//        foreach ($result as $key=>$value){
//            $res[] = [
//                'id' => $value->id,
//                'name' => $value->name,
//                'email' => $value->email
//            ];
//        }
        //单条数据查询
//        $result2 = $user::findFirst();  echo $d;
//        $res2 = [
//            'id' => $result2->id,
//            'name' => $result2->name,
//            'email' => $result2->email
//        ];
        //多条数据json格式
        //return $this->response->setJsonContent($result);
        //单条数据json格式
        //return $this->response->setJsonContent($result2);

        //预处理带参数查询
//        $conditons = ' name = :name: ';
//        $parameters  = [
//           'name' => "abc"
//        ];
//        $result = $user::findFirst([$conditons,'bind'=>$parameters]);
        //带参数查询
        //$result = $user::findFirst("name = 'abc'");
        //findBy《属性》 findFirstBy《属性》
//        $result = $user::findFirstByName('abc');
//        if($result){
//            return $this->response->setJsonContent($result);
//        }

        //删除数据
//        $result = $user::findFirstByName('abc');
//        $result->delete();

        //修改数据
//        $result = $user::findFirstByName('abc');
//        $result->name = 'def';
//        $result->save();

        //添加数据
//        $zs_message = new Zs_short_message();
//        $zs_message->phone = "18318058460";
//        $zs_message->code = '000000';
//        $zs_message->send_time = "1544595040";
//        $zs_message->smsId = "sd132ds24jfh43kwy55";
//        $zs_message->create_time = "1544595040";
//        $zs_message->pid = "11011";
//        $zs_message->status = 1;
//        $zs_message->ratio = 0;
//        $bool = $zs_message->save();

        exit;
    }
}