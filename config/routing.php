<?php

/** @var \Silex\ControllerCollection $controllers */
$controllers
    ->get('/', 'login.controller.home:indexAction')
    ->bind('home')
;

$controllers
    ->get('/registration', 'login.controller.registration:indexAction')
    ->bind('registration')
;
