<?php

namespace Rocket;

function to_base64($obj){
    return function()use($obj){
        if(is_object($obj)){
            try {
                $string = $obj->__toString();
            } catch(\BadMethodCallException $ex){
                $string = get_class($obj);
            }
        } else {
            $string = (string)$obj;
        }
        return base64_encode($string);
    };
}