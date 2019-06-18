<?php

namespace app\src\response;

class Response
{
    /**
     * @var string
     */
    private $content;

    /**
     * @var int
     */
    private $statusCode;

    /**
     * @var array
     */
    private $headers;

    /**
     * Response constructor
     *
     * @param string $content
     * @param int $statusCode
     * @param array $headers
     */
    public function __construct(string $content, int $statusCode = 200, array $headers = [])
    {
        $this->content = $content;
        $this->statusCode = $statusCode;
        $this->headers = array_merge(['Content-Type' => 'text/html'], $headers); // In the context of a website, we will always send 'text/html' headers (because we send html files)
    }

    /**
     * Get status code of the response
     * @return int
     */
    public function getStatusCode() : int {
        return $this->statusCode;
    }

    /**
     * Get content of the response
     * @return string
     */
    public function getContent() : string{
        return $this->content;
    }

    /**
     * Send headers to the browser
     * Always send headers before content, if you do not do this, you risk the browser to not understand what you are sending
     */
    public function sendHeaders()
    {
        http_response_code($this->statusCode);

        foreach ($this->headers as $name => $value){
            header(sprintf('%s: %s', $name, $value));
        }
    }

    /**
     * Send the content of the response to the browser
     */
    public function send()
    {
        $this->sendHeaders();

        echo $this->content;
    }
}