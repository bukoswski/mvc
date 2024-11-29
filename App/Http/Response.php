<?php

namespace App\Http;

class Response
{
    private $httpCode = 200; // Código HTTP
    private $headers = [];  // Cabeçalhos HTTP
    private $contentType = 'text/html'; // Tipo de conteúdo
    private $content; // Conteúdo da resposta

    public function __construct($httpCode = 200, $content = '', $contentType = 'text/html')
    {
        $this->httpCode = $httpCode;
        $this->content = $content;
        $this->setContentType($contentType);
    }

    // Define o tipo de conteúdo e adiciona o cabeçalho correspondente
    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
        $this->addHeader('Content-Type', $contentType);
    }

    // Adiciona um cabeçalho HTTP personalizado
    public function addHeader($key, $value)
    {
        $this->headers[$key] = $value;
    }
    private function sendHeades()
    {
        http_response_code($this->httpCode);

        foreach ($this->headers as $key => $value) {
        }
        header($key . ': ' . $value);
    }

    // Envia a resposta HTTP
    public function sendResponse()
    {
        $this->sendHeades();
        switch ($this->contentType) {
            case 'text/html';
                echo $this->content;
                exit;
        }
    }
}
