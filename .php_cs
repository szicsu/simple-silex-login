<?php

return Symfony\CS\Config\Config::create()
    ->setUsingLinter(true)
    ->setUsingCache(true)
    ->finder(
        Symfony\CS\Finder\DefaultFinder::create()
        ->in(__DIR__)
    );