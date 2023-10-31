<?php


namespace Http;

class Url {

    private $params = array();

    protected $url;

    protected $protocol;
    
    protected $domain;
    
    protected $basepath;
    
    protected $path;
    
    protected $queryString;
    
    protected $args = [];

    

    public function __construct($url) {
        $this->url = $url;
    }


	public static function fromString($url) {
		
		$query = null; 
		
		if(self::hasQueryString($url)) {
			$query = new QueryString($url);
			$url = explode("?",$url)[0];
		}
		
		$url = self::normalize($url);
		
	
		return self::isAbsolute($url) ? new AbsoluteUrl($url,$query) : new RelativeUrl($url,$query);
	}

	public function getUrl() {
		return $this->url;
	}
	
	public function getPath() {
		return $this->path;
	}

	public function hasProtocol(){
			$parts = explode("//", $this->url);
			return count($parts) > 1 && !empty($parts[0]);
	}

	public static function hasQueryString($url){
		return count(explode("?",$url)) > 1;
	}
	
	public static function isAbsolute($url) {
		return count(explode("//",$url)) > 1;
	}
	
	public function getRelativeUrl($path) {
		return "";
	}

	public static function normalize($path){
		if(strpos($path,"//") === 0 ){
				return $path;
		}
		if(strpos($path,"/") === 0 ){
				$path = substr($path,1);
		}
		return $path;
	}




	public function getArgs(){
			return $this->args;
	}

	
	public function __toString() {
		return print_r(array(
			"url" => $this->url,
			"protocol" => $this->protocol,
			"domain" => $this->domain,
			"basepath" => $this->basepath,
			"path" => $this->path,
			"querystring" => $this->querystring,
			"arguments" => $this->arguments,
			"named" => $this->args
		),true);
	}




    public function setParams($params) {
        $this->params = $params;
    }

    public function getParams() {
        return $this->params;
    }



    public static function formatParams($params, $contentType = "x-www-form-urlencoded") {

        $vars = array_map(function($key,$value) {
            return null == $value ? $key : "${key}=${value}";
        }, array_keys($params), $params);

        return implode("&",$vars);
    }


/*
    public function __toString() {

        $query = self::formatParams($this->params);

        return empty($query) ? $this->url : ($this->url . "?" . $query);
    }
*/
}