<?php

$finder = Symfony\CS\Finder\DefaultFinder::create()
    ->path('src')
    ->in(__DIR__);

return Symfony\CS\Config\Config::create()
    ->level(Symfony\CS\FixerInterface::SYMFONY_LEVEL)
    ->finder($finder);
