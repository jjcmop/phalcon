<?php
use Phalcon\Mvc\Controller;
use Phalcon\Http\Response;

class ResponseController extends Controller{

    public function indexAction()
    {
        // Getting a response instance
        $response = new Response();
        // Set status code
        $response->setStatusCode(404, 'Not Found');
        // Set the content of the response
        $response->setContent("Sorry, the page doesn't exist");
        // Send response to the client
        $response->send();

        // Setting a header by its name
        $response->setHeader('Content-Type', 'application/pdf');
        $response->setHeader('Content-Disposition', "attachment; filename='downloaded.pdf'");
        // Setting a raw header
        $response->setRawHeader('HTTP/1.1 200 OK');

        // Get the headers bag
        $headers = $response->getHeaders();
        // Get a header by its name
        $contentType = $headers->get('Content-Type');
    }

    //Making Redirections
    public function linkAction()
    {
        $response = new Response();
        // Redirect to the default URI
        $response->redirect();
        // Redirect to the local base URI
        $response->redirect("posts/index");
        // Redirect to an external URL
        $response->redirect("http://en.wikipedia.org", true);
        // Redirect specifying the HTTP status code
        $response->redirect("http://www.example.com/new-location", true, 301);
        // Send response to the client
        $response->send();

        // Redirect based on a named route
        return $response->redirect([
                "for"        => "index-lang",
                "lang"       => "jp",
                "controller" => "index"
        ]);
        //值得注意的时候重定向并不禁用view组件，所以如果当前的action存在一个关联的view的话，将会继续执行它。
        //在控制器中可以通过 $this->view->disable() 来禁用view。
    }


    //HTTP缓存
    //提高应用程序性能和减少流量的最简单方法之一是使用HTTP缓存。大多数现代浏览器都支持HTTP缓存，这也是许多网站目前速度很快的原因之一。
    //在第一次提供页面时，应用程序发送的以下标头值可以更改HTTP缓存：
    //Expires: 使用此标头，应用程序可以设置将来或过去的日期，告知浏览器何时页面必须到期。
    //Cache-Control: 此标头允许指定页面在浏览器中应被视为新鲜的时间。
    //Last-Modified: 此标头告诉浏览器哪个网站最后一次更新，从而避免重新加载页面。
    //ETag: etag是必须创建的唯一标识符，包括当前页面的修改时间戳
    public function cacheAction(){

        $response = new Response();

        //Setting an Expiration Time
        $expireDate = new DateTime();
        $expireDate->modify('+2 months');
        $response->setExpires($expireDate);

        //Setting an Expiration Time
        $expireDate = new DateTime();
        $expireDate->modify('-10 minutes');
        $response->setExpires($expireDate);

        //Cache-Control
        // Starting from now, cache the page for one day
        $response->setHeader('Cache-Control', 'max-age=86400');
        // Never cache the served page
        $response->setHeader('Cache-Control', 'private, max-age=0, must-revalidate');

        //E-Tag
        // Calculate the E-Tag based on the modification time of the latest news
        $mostRecentDate = News::maximum(['column' => 'created_at']);
        $eTag = md5($mostRecentDate);
        // Send an E-Tag header
        $response->setHeader('E-Tag', $eTag);

    }
}
