<?php

/** @var \Silex\ControllerCollection $controllers */
$controllers
    ->get('/', 'login.controller.home:indexAction')
    ->bind('home')
;

