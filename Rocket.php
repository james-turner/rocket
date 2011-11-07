<?php

namespace Rocket;

function each(&$enumerable){
    return function($block) use($enumerable){
        $func = new \ReflectionFunction($block);
        $num_of_params = $func->getNumberOfParameters();
        foreach($enumerable as $key => &$value){
            if($num_of_params > 1){
                $block($key, $value);
            } else {
                $block($value);
            }
        };
    };
}

function init($obj){
    if(is_string($obj) && trim(strlen($obj)) > 0){
        if(class_exists($obj)){
            $obj = new $obj();
        }
    }
    return new Object($obj);
}

function with($obj){
    return function($block)use($obj){
        $block($obj);
        return $obj;
    };
}

function map(&$enumerable){
    return function($block) use($enumerable){
        $clone = clone $enumerable;
        $enumerable->each(function($k, $v)use($clone, $block){
            $clone[$k] = $block($v);
        });
        return $clone;
    };
}

class Object extends \ArrayObject {

    private $obj;

    public function __construct($obj = null){
        if(is_object($obj)){
            $this->obj = $obj;
        }elseif(is_array($obj)){
            parent::__construct($obj);
        }elseif(null !== $obj){
            throw new \RuntimeException("$obj is not a valid class obj.");
        }
    }

    public function __set($key, $value){
        if($this->obj && method_exists($this->obj, '__set')){
            return $this->obj->__set($key, $value);
        }
        $this[$key] = $value;
    }

    public function __get($key){
        if($this->obj && method_exists($this->obj, '__get')){
            return $this->obj->__get($key);
        }
        return $this[$key];
    }

    public function __call($method, $args){
        $func = __NAMESPACE__ . "\\$method";
        if($this->obj){
            try {
                $method = new \ReflectionMethod($this->obj, $method);
                $method->setAccessible(true);
                return $method->invokeArgs($this->obj, $args);
            } catch(\ReflectionException $ex){
                // fallback onto the __call() method if there is one, else handle further down.
                if(method_exists($this->obj, '__call')){
                    return $this->obj->__call($method, $args);
                }
            }
        }
        if(function_exists($func)){
            return call_user_func_array($func($this), $args);
        }
        throw new \BadMethodCallException();
    }

    public static function init($obj){
        return init($obj);
    }
}
