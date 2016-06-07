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

$controllers
    ->post('/registration', 'login.controller.registration:saveAction')
    ->bind('registration_save')
;

$controllers
    ->get('/registration/success', 'login.controller.registration:successAction')
    ->bind('registration_success')
;


$controllers
    ->get('/login', 'login.controller.security:loginAction')
    ->bind('login')
;

$controllers
    ->post('/secure/login-check', 'login.controller.security:loginCheckAction')
    ->bind('login_check')
;

$controllers
    ->get('/secure/logout', 'login.controller.security:logoutAction')
    ->bind('logout')
;


$controllers
    ->get('/secure/userprofile', 'login.controller.userprofile:indexAction')
    ->bind('userprofile')
;
