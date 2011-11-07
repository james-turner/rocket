<?php

include 'Rocket.php';
include 'Container.php';
include 'Image.php';
include 'to_base64.php';
include 'wait.php';

use Rocket\Object;

Object::init(new Container())->with(function($c) use(&$container) {

    // parameters
    $c->mailer_class = function () { return 'stdClass'; };
    $c->mailer_username = function () { return 'fabien'; };
    $c->mailer_password = function () { return 'myPass'; };

    // objects / services
    $c->mailer_transport = function ($c) {
      return new \stdClass(
        'smtp.gmail.com',
        array(
          'auth'     => 'login',
          'username' => $c->mailer_username,
          'password' => $c->mailer_password,
          'ssl'      => 'ssl',
          'port'     => 465,
        )
      );
    };
    $c->mailer = function ($c) {
      $obj = new $c->mailer_class();
//      $obj->setDefaultTransport($c->mailer_transport);
      return $obj;
    };

    $container = $c;

});

Object::init(new Image('http://upload.wikimedia.org/wikipedia/commons/thumb/d/d4/George-W-Bush.jpeg/220px-George-W-Bush.jpeg'))->with(function($i)use(&$base64){
    $base64 = $i->to_base64();
});

echo $base64;