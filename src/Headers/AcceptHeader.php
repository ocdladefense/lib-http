<?php

namespace Http;



class AcceptHeader extends HttpHeader {



    public function __construct($value) {
        $this->name = "Accept";
        $this->value = $value;
    }



    public function isSatisfiedBy2($value) {
        
        return true;
    }

    public function isAcceptable($mimeType) {

        $struct = $this->parse();

        return array_key_exists($mimeType, $struct);
        // Build an additional array whose keys are the same, but whose values
        // are *only the q values;
        // Also this lets us specify the "default" q value of 1.0;
     }
     


     
    public function getByWeight() {
        
        $struct = $this->parse();
        
        $weighted = array();

        foreach($struct as $range => $media) {
            $weight = null == $media["q"] ? 1.0 : (float)$media["q"];
            $weighted[$range] = $weight;
        }

        arsort($weighted);
       
        return $weighted;
    }



    public function parse() {

        // Build an array of media ranges.
        // A media range consists of a type and a subtype:
        // For exmaple, text/* or text/html or text/plain.
        // See https://httpwg.org/specs/rfc7231.html#header.accept
        $ranges = array_map(function($media) { return trim($media); }, explode(",", $this->value));

        // A media type can be followed by one or more paramters.
        // These are called media type parameters;
        // or specifically a "q" parameter; or any other optional "extension" parameters.

        $acceptParams = array();

        foreach($ranges as $media) {
    
 
            // "text/html; q=0.8"
            $tmp = explode(";", $media);
            $type = array_shift($tmp);
            $acceptParams[$type] = array();

            // --> array(" q=0.8");
            // array(" q","0.8");
            // array("q","0.8");
            array_walk($tmp, function($param) use(&$acceptParams,$type) {

                $double = explode("=", $param);
                
                $trim = function($v) { return trim($v); };
                
                list($key,$value) = array_map($trim, $double);
                
                $acceptParams[$type][$key] = $value;
            });
        }

        return $acceptParams;
    }




    public function getParameters(){

        $kvp = array();
        
        $params = explode(";", $this->value);

        foreach($params as $p){

            $temp1 = trim($p);

            $temp2 = explode("=", $temp1);

            if(count($temp2) < 2) continue;

            $key = $temp2[0];
            $value = trim($temp2[1], '"');

            $kvp[$key] = $value;

        }

        return $kvp;
    }




    public function __toString() {

        return $this->name . ": " . $this->value;
    }

}