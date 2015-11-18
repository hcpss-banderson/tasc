#!/usr/bin/env php
<?php

require __DIR__.'/vendor/autoload.php';

use HcpssBanderson\Tool\TascApplication;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
use HcpssBanderson\DependencyInjection\PatcherCompilerPass;
use HcpssBanderson\DependencyInjection\ProviderCompilerPass;


$container = new ContainerBuilder();
$container->addCompilerPass(new PatcherCompilerPass());
$container->addCompilerPass(new ProviderCompilerPass());

$loader = new YamlFileLoader($container, new FileLocator(__DIR__));
$loader->load('services.yml');
$loader->load('parameters.yml');

$application = new TascApplication(null, null, $container);
$application->run();
