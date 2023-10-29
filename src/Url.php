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



    public static function formatParams($params, $contentType = "x-www-form-urlencoded") {

        $vars = array_map(function($value,$key) {
            return "${key}=${value}";
        }, $params, array_keys($params));

        return implode("&",$vars);
    }



    public function __toString() {

        $query = self::formatParams($this->params);

        return empty($query) ? $this->url : ($this->url . "?" . $query);
    }

}