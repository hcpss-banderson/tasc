#!/usr/bin/env php
<?php

/**
 * Tasc
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @copyright   2015 Howard County Public Schools
 * @author      Brendan Anderson <brendan_anderson@hcpss.org>
 * @license     http://www.gnu.org/licenses/ GPLv3
 * @version     0.1.0
 */

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

if (file_exists(__DIR__ . DIRECTORY_SEPARATOR . 'parameters.yml')) {
    $loader->load('parameters.yml');
}

$application = new TascApplication('Tasc', '0.1.0', $container);
$application->run();
