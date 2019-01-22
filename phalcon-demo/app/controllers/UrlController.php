<?php
use Phalcon\Mvc\Controller;
use Phalcon\Mvc\Url;

class UrlController extends Controller
{
    public function indexAction(){

        $url = new Url();
        // Select base URI
        echo $url->getBaseUri();
        // Setting a relative base URI
        $url->setBaseUri('/invo/');
        // Setting a full domain as base URI
        $url->setBaseUri('//my.domain.com/');
        // Setting a full domain as base URI
        $url->setBaseUri('/invo/index.php?_url=/');
        // This produce: /invo/index.php?_url=/products/save
        echo $url->get("products/save");

        //$di->set('url',function () {
        //    $url = new Url();
        //    $url->setBaseUri('/invo/');
        //    return $url;
        //});


        //The function “url” is available in volt to generate URLs using this component:
        echo "<a href='{{ url('posts/edit/1002') }}''>Edit</a>";
        //Generate static routes:
        echo "<link rel='stylesheet' href='{{ static_url('CSS/style.css') }}' type='text/css' />";


        // Static resources go through a CDN
        $url->setStaticBaseUri('http://static.mywebsite.com/');

    }
}