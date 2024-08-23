<?php namespace App\Filters;

use CodeIgniter\config\Services;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\Response;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;


class CorsFilter implements FilterInterface
{
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {


    }

    public function before(RequestInterface $request, $arguments = null)
    {
        // get origins
        if (array_key_exists('HTTP_ORIGIN', $_SERVER)) {
            $origin = $_SERVER['HTTP_ORIGIN'];
        } else if (array_key_exists('HTTP_REFERER', $_SERVER)) {
            $origin = $_SERVER['HTTP_REFERER'];
        } else {
            $origin = $_SERVER['REMOTE_ADDR'];
        }
        $allowed_domains = array(
            'http://localhost:8081',
            'http://localhost:3000',
            'https://conexmetsclm.com',

        );

        // this code work on real host for example  www.example.com

        $response = Services::response();
        $response->setHeader('Access-Control-Allow-Origin', '*');
        $response->setHeader('Access-Control-Allow-Methods', '*');
        $response->setHeader('Access-Control-Allow-Headers', '*');
        $response->setHeader("Access-Control-Allow-Credentials", "true");
        $response->setHeader('Access-Control-Max-Age', '3600');
        $response->setStatusCode(Response::HTTP_OK, 'cors are  enable');
        $response->setContentType('application/json; charset=UTF-8');
        $response->send();

        if ($request->getMethod(true) == "OPTIONS"
        ) {
            die();

        }


        // this below code work on  localhost xammp server  localhost:8080


//        if (in_array($origin, $allowed_domains)) {
//            header('Access-Control-Allow-Origin: ' . $origin);
//        } else {
//            header('Access-Control-Allow-Origin: ' . site_url());
//        }
//
//        header("Access-Control-Allow-Headers: Origin, X-API-KEY, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method, Access-Control-Allow-Headers, Authorization, observe, enctype, Content-Length, X-Csrf-Token");
//        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
//        header("Access-Control-Allow-Credentials: true");
//        header("Access-Control-Max-Age: 3600");
//        header('content-type: application/json; charset=utf-8');
//        $method = $_SERVER['REQUEST_METHOD'];
//        if ($method == "OPTIONS") {
//            die();
//        }


    }


} 

