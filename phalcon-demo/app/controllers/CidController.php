<?php
use Phalcon\Mvc\Controller;
use Phalcon\Crypt;

class CidController extends Controller
{
    //Encryption/Decryption
    public function codeAction(){
        // Create an instance
        $crypt =  new Crypt();


        // Get the supported algorithms
        $algorithms = $crypt->getAvailableHashAlgos();


        //encrypt,default AES-256-CFB
        $key  = "This is a secret key (32 bytes).";
        $text = "This is the text that you wAnt to encrypt.";
        $encrypted = $crypt->encrypt($text, $key);
        //decrypt
        echo $crypt->decrypt($encrypted, $key);


        //encrypt,default blowfish
        $crypt->setCipher("bf-cbc");
        $key  = "le password";
        $text = "This is a secret text";
        $encrypted = $crypt->encrypt($text, $key);
        //decrypt
        echo $crypt->decrypt($encrypted, $key);


        //Base64 Support
        $encrypt = $crypt->encryptBase64($text, $key);
        echo $crypt->decryptBase64($encrypt, $key);


        //Setting up an Encryption service
        //use Phalcon\Crypt;
        //$di->set('crypt',function () {
        //    $crypt = new Crypt();
              //Set a global encryption key
        //    $crypt->setKey("%31.1e$i86e$f!8jz");
        //    return $crypt;
        //},true);

        //$this->crypt->encrypt($text);
        //$this->crypt->decrypt($encrypt);

    }

    //Security
    public function warningAction(){
        // Store the password hashed
        $password = $this->request->getPost('password');
        $pass = $this->security->hash($password);
        //chech the password hashed
        $this->security->checkHash($password, $pass);


        //Cross-Site Request Forgery (CSRF) protection
        echo "<input type='hidden' name='<?=$this->security->getTokenKey()?>' value='<?=$this->security->getToken()?>'/>";
        //chech the token csrf
        $this->security->checkToken();
        //Remember to add a session adapter to your Dependency Injector, otherwise the token check won't work:
        //$di->setShared('session',function () {
        //    $session = new \Phalcon\Session\Adapter\Files();
        //    $session->start();
        //    return $session;
        //});


        //Setting up the component
        //$di->set('security', function () {
        //    $security = new \Phalcon\Security();
            // Set the password hashing factor to 12 rounds
        //    $security->setWorkFactor(12);
        //    return $security;
        //}, true);


        //Random
        $random = new \Phalcon\Security\Random();
        $bytes      = $random->bytes();
        // Generate a random hex string of length $len.
        $hex        = $random->hex($len);
        // Generate a random base64 string of length $len.
        $base64     = $random->base64($len);
        // Generate a random URL-safe base64 string of length $len.
        $base64Safe = $random->base64Safe($len);
        // Generate a UUID (version 4).
        $uuid       = $random->uuid();
        // Generate a random integer between 0 and $n.
        $number     = $random->number($n);

    }
}