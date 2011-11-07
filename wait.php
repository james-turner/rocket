<?php


namespace Rocket;

include "Rocket.php";

function wait($obj){
    return function($milliseconds, $block = null) use($obj){
        usleep($milliseconds*1000);
        $block = $block?:(function(){});
        return $block($milliseconds, $obj);
    };
}


Object::init(new \stdClass())->with(function($c)use(&$registry){
    $c->wait(1000, function($c){
        echo "hola!";
    });
    $c->test = "blah";
    $registry["my_class"] = $c;
});

var_dump($registry);