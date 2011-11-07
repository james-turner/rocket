<?php

class Image {
    private $uri;

    public function __construct($uri){
        $this->uri = $uri;
    }

    public function getExtension(){
        $parts  = explode('.', $this->uri);
        return end($parts);
    }

    public function __toString(){
        return file_get_contents($this->uri);
    }
}
