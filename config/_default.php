<?php

/* @var Login\Application $app */

$app['locale'] = 'en';

$app['db.options'] = array(
    'driver' => 'pdo_sqlite',
    'path' => DATA_DIR . '/sqlite.db',
);

$app['orm.em.options'] = array(
    'mappings' => array( // TODO configure mapping cache
        array(
            'use_simple_annotation_reader' => FALSE,
            'type' => 'annotation',
            'namespace' => 'Login\Entity',
            'path' => SRC_DIR.'/Entity',
        )
    )
);

$app['security.firewalls'] = array(

    'secure' => array(
        'pattern' => '^/secure',
        'users' => $app['login.user.provider.bridge'],
        'form' => array(
            'login_path' => '/login',
            'check_path' => '/secure/login-check',
            'default_target_path' => '/secure/userprofile'
        ),
        'logout' => array('logout_path' => '/secure/logout', 'invalidate_session' => true),

    ),

    'default' => array(
        'pattern' => '^/',
        'anonymous' =>  true,
    ),
);


$app['validator.validator_service_ids'] = array(
    \Login\Validator\UniqueEmail::SERVICE_NAME => 'login.service.validator.unique.email'
);

