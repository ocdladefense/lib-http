<?php
class Url {

    private $params = array();

    private $url; 


    public function __construct($url) {
        $this->url = $url;
    }

    public function setParams($params) {
        $this->params = $params;
    }

    public function getParams() {
        return $this->params;
    }

    public function __toString() {

        $vars = array_map(function($value,$key) {
            return "${key}=${value}";
        }, $this->params, array_keys($this->params));

        $query = implode("&",$vars);

        return empty($query) ? $this->url : ($this->url . "?" . $query);
    }

}