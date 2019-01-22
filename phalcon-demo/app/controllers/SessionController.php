<?php
use Phalcon\Mvc\Controller;
use Phalcon\Session\Adapter\Files as Session;

class SessionController extends Controller
{

    public function indexAction(){

        // Check if the cookie has previously set
        $this->cookie->has('remember-me');
        // Get the cookie
        $rememberMeCookie = $this->cookie->get('remember-me');
        // Get the cookie's value
        $value = $rememberMeCookie->getValue();
        // Set the cookie
        $this->cookies->set('remember-me','some value',time()+15*86400);
        $this->cookies->send();
        // Delete the cookie
        $this->cookies->get('remember-me')->delete();

        //You can disable encryption as follows==>index.php:
        //use Phalcon\Http\Response\Cookies;
        //$di->set('cookies',function () {
        //     $cookies = new Cookies();
        //     $cookies->useEncryption(false);
        //     return $cookies;
        //});


        //If you wish to use encryption, a global key must be set in the crypt service==>index.php:
        //use Phalcon\Crypt;
        //$di->set('crypt',function(){
        //     $crypt = new Crypt();
        //     $crypt->setKey('#1dj8$=dp?.ak//j1V$'); // 使用你自己的key！
        //     return $crypt;
        //});

    }

    //Storing data in Session
    public function sessAction(){
        //Starting the Session
        //$di->setShared('session', function () {
        //    $session = new Session();
        //    $session->start();
        //    return $session;
        //});

        //Storing/Retrieving data in Session
        $this->session->set("user-name", "Michael");
        $this->session->has("user-name");
        $this->session->get("user-name");

        //Removing/Destroying Sessions
        $this->session->remove("user-name");
        $this->session->destroy();

    }


}