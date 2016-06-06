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