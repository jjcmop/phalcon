<?php
use Phalcon\Mvc\Controller;
use Phalcon\Http\Request;

class RequestController extends Controller{

    public function indexAction()
    {
        // Getting a request instance
        $request = new Request();   //$request = $this->request;
        // Check whether the request was made with method POST
        $request->isPost();
        // Check whether the request was made with Ajax
        $request->isAjax();
        // Check whether the request was made with Get
        $request->isGet();
        // Check if HTTP method match any of the passed methods
        $request->isMethod('POST');
        // Gets HTTP method which request has been made
        $request->getMethod();
        // Gets a variable from the $_REQUEST superglobal
        $request->get("id");
        // Checks whether $_REQUEST superglobal has certain index
        $request->has("id");
        // Manually applying the filter
        $filter = new Phalcon\Filter();
        $email  = $filter->sanitize($_POST["user_email"], "email");
        // Manually applying the filter to the value
        $filter = new Phalcon\Filter();
        $email  = $filter->sanitize($request->getPost("user_email"), "email");
        // Automatically applying the filter
        $email = $request->getPost("user_email", "email");
        // Setting a default value if the param is null
        $email = $request->getPost("user_email", "email", "some@example.com");
        // Setting a default value if the param is null without filtering
        $email = $request->getPost("user_email", null, "some@example.com");
        // Gets variable from the dispatcher
        $host = $request->getParam("id");
        // Gets a variable from put request
        $id = $request->getPut("id");
        // Gets variable from $_GET superglobal
        $id = $request->getQuery("id");
        // Gets variable from $_SERVER superglobal
        $host = $request->getServer("HOST");

    }

    //Another common task is file uploading. Phalcon\Http\Request offers an object-oriented way to achieve this task:
    public function uploadAction()
    {
        // Check if the user has uploaded files
        if ($this->request->hasFiles()) {
            $files = $this->request->getUploadedFiles();

            // Print the real file names and sizes
            foreach ($files as $file) {
                // Print file details
                echo $file->getName(), ' ', $file->getSize(), '\n';

                // Move the file into the application
                $file->moveTo(
                    'files/' . $file->getName()
                );
            }
        }
    }

    //Working with headers
    public function headerAction()
    {
        $request = new Request();
        // Get the Http-X-Requested-With header
        $request->getHeader("HTTP_X_REQUESTED_WITH");
        // Check the request layer
        $request->isSecureRequest();
        // Get the servers's IP address. ie. 192.168.0.100
        $request->getServerAddress();
        // Get the client's IP address ie. 201.245.53.51
        $request->getClientAddress();
        // Get the User Agent (HTTP_USER_AGENT)
        $request->getUserAgent();
        // Get the best acceptable content by the browser. ie text/xml
        $request->getAcceptableContent();
        // Get the best charset accepted by the browser. ie. utf-8
        $request->getBestCharset();
        // Get the best language accepted configured in the browser. ie. en-us
        $request->getBestLanguage();
        // Check if a header exists
        $request->hasHeader('my-header');
    }
}