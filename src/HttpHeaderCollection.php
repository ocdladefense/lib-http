<?php



namespace Http;


class HttpHeaderCollection {


    const SEPERATOR = ": ";


	protected $headers = array();
	
	
	protected $stripOwsFromHeaders = array();
	
	

    public function __construct($headers = null){
        $this->headers = null == $headers ? array() : $headers;
    }

		
	public function addHeader($header) {
		$this->headers[] = $header;
	}

	public function addHeaders(array $headers) {
		$this->headers = array_merge($this->headers,$headers);
	}
		
		
    public function getHeaders() {
    	return $this->headers;
    }

    
	/**
	 * Return the header with the specified name.
	 *  If more than one header with this name
	 *  exists, then return the last one.
	 *
	 *  http spec supports multiple headers with the same name,
	 *  however, multiple pseudo-headers of the same name are prohibited.
	 */
	public function getHeader($name, $strict = true) {


		$filter = function($header) use ($name, $strict) {

			return $strict ? $name == $header->getName() : strToLower($name) == strToLower($header->getName());

		}; 
		
		$tmp = array_filter($this->headers, $filter);

		if(null == $tmp || count($tmp) < 1) return null;
		
		$arrange = array_values($tmp);

		$header = $arrange[count($arrange)-1];

		$headerClass = ("\Http\\".$name."Header");

		return class_exists($headerClass) ? new $headerClass($header->getValue()) : $header;
	}




	public function removeHeader($name, $strict = true) {

		$index = $this->indexOf($name);

		return false === $index ? null : self::fromHeaders(array_splice($this->headers, $index, 1));
	}


	public function indexOf($name, $strict = true) {

		for($index = 0; $index<count($this->headers); $index++) {
			$header = $this->headers[$index];

			if($name == $header->getName()) {
				return $index;
			}
		}

		return false;
	}



		/**
	 * Return the header with the specified name.
	 *  If more than one header with this name
	 *  exists, then return the last one.
	 *
	 *  http spec supports multiple headers with the same name,
	 *  however, multiple pseudo-headers of the same name are prohibited.
	 */
	public function getValue($name, $strict = true) {


		$filter = function($header) use ($name, $strict) {

			return $strict ? $name == $header->getName() : strToLower($name) == strToLower($header->getName());

		}; 
		
		$tmp = array_filter($this->headers, $filter);

		if(null == $tmp || count($tmp) < 1) return null;
		
		$arrange = array_values($tmp);

		return $arrange[count($arrange)-1]->getValue();
	}
    
    
    
    
	public function setStripOwsFromHeaders($names = array()) {
		$this->stripOwsFromHeaders = $names;
	}

    
	public function getHeadersAsArray() {
			$strip = $this->stripOwsFromHeaders;
			
			return array_map(function($header) use($strip) {

				if(in_array($header->getName(),$strip)) {
					return $header->getName() . ":".$header->getValue();
				} else {
					return $header->getName() . ": ".$header->getValue();
				}
			}, $this->headers);
	}

	// Alias of getHeadersAsArray();
	public function getList() {
		return $this->getHeadersAsArray();
	}


	public function getHeadersAsAssociativeArray() {
		$assoc = array();
		$strip = $this->stripOwsFromHeaders;
		

		foreach($this->headers as $header) {
			$assoc[$header->getName()] = $header->getValue();
		}

		return $assoc;
	}
  
    

	public static function fromArray(array $assoc) {
		$tmp = array();
		foreach($assoc as $key => $value) {
			$tmp[] = new HttpHeader($key,$value);
		}
		
		return new self($tmp);
	}

	public static function fromHeaders(array $headers) {
		$collection = new HttpHeaderColection();
		$collection->addHeaders($headers);

		return $collection;
	}
}