<?php

/**
 * Compile Providers
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
 */

namespace HcpssBanderson\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Provider compiler
 *
 * @author      Brendan Anderson <brendan_anderson@hcpss.org>
 * @package     Tasc
 * @copyright   2015 Howard County Public Schools
 */
class ProviderCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->has('provider_manager')) {
            return;
        }
        
        $definition = $container->findDefinition('provider_manager');
        
        $provicerServices = $container->findTaggedServiceIds('provider');
        foreach ($provicerServices as $id => $tags) {
            foreach ($tags as $attributes) {
                $definition->addMethodCall(
                    'addProvider',
                    [new Reference($id), $attributes['type']]
                );
            }
        }
    }
}
