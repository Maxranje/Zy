<?php

class Zy_Helper_Curl {
    
    public $handle;

    public $response;

    public static $instance = null;

    private function __construct() {
        $this->handle = curl_init ();
        curl_setopt($this->handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($this->handle, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->handle, CURLOPT_HEADER, false);
        curl_setopt($this->handle, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($this->handle, CURLINFO_HEADER_OUT, true);
    }

    public static function getInstance () {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @param $request Request
     */
    function buildRequest($request) {

        $this->setUrl($request->url);

        $this->setMethod($request->method);

        $this->setParams($request->params);

        $this->setHeader($request->header);

        $this->setCookie($request->cookie);

        $this->setUseragent($request->userAgent);

        $this->setOptions($request->option);

    }


    function setUrl($url) {
        curl_setopt ( $this->handle, CURLOPT_URL, $url );
    }

    function setMethod($metdod) {
        switch ($metdod) {
            case "POST":
                curl_setopt($this->handle, CURLOPT_CUSTOMREQUEST, "POST");
                break;
            case "GET":
                curl_setopt($this->handle, CURLOPT_CUSTOMREQUEST, "GET");
                break;
            case "PUT":
                curl_setopt($this->handle, CURLOPT_CUSTOMREQUEST, "PUT");
                break;
            case "DELETE":
                curl_setopt($this->handle, CURLOPT_CUSTOMREQUEST, "DELETE");
                break;
            default:
                curl_setopt($this->handle, CURLOPT_CUSTOMREQUEST, "POST");
                break;
        }
    }

    function setParams($params) {
        !empty($params) && curl_setopt($this->handle, CURLOPT_POSTFIELDS, $params);
    }

    function setHeader($header) {
        !empty($header) && curl_setopt($this->handle, CURLOPT_HTTPHEADER, $header);
    }

    function setCookie($cookie) {
        !empty($header) && curl_setopt($this->handle, CURLOPT_COOKIE, $cookie);
    }

    function setUseragent($userAgent) {
        !empty($userAgent) && curl_setopt($this->handle, CURLOPT_USERAGENT, $userAgent);
    }

    function setOptions($options) {
        if (empty ( $options )) {
            return;
        }
        foreach ( $options as $option => $value ) {
            switch ($option) {
                case 'timeout' :
                    curl_setopt ( $this->handle, CURLOPT_TIMEOUT, $value );
                    break;
                case 'timeout_ms' :
                    curl_setopt ( $this->handle, CURLOPT_TIMEOUT_MS, $value );
                    break;
                case 'connect_timeout' :
                    curl_setopt ( $this->handle, CURLOPT_CONNECTTIMEOUT, $value );
                    break;
                case 'connect_timeout_ms' :
                    curl_setopt ( $this->handle, CURLOPT_CONNECTTIMEOUT_MS, $value );
            }
        }
    }

    function exec() {
        $this->response = curl_exec($this->handle);
    }

    public function fetch() {
        return $this->response;
    }

    public function httpInfo() {
        return curl_getinfo($this->handle);
    }

    function close() {
        curl_close($this->handle);
        $this->handle = null;
    }
}
