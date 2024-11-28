<?php

namespace App\Http;

class Request
{
    private $httpMethod;
    private $uri;
    private $queryParam = [];
    private $PostVars = [];
    private $headers = [];

    public function __construct()
    {
        $this->httpMethod = $_SERVER['REQUEST_METHOD'] ?? '';
        $this->uri = $_SERVER['REQUEST_URI'] ?? '';
        $this->queryParam = $_GET ?? '';
        $this->PostVars = $_POST ?? '';
        $this->headers = getallheaders() ?? '';
    }
    public function getUri()
    {
        return $this->uri;
    }

    public function getHttpMethod()
    {
        return $this->httpMethod;
    }
    public function getQueryParams()
    {
        return $this->queryParam;
    }
    public function getPostVars()
    {
        return $this->PostVars;
    }
    public function getHeaders()
    {
        return $this->headers;
    }
}