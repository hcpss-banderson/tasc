<?php

/**
 * A service for registering and retrieveing Providers.
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

namespace HcpssBanderson\Provider;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * A service for registering and retrieveing Providers.
 *
 * @author      Brendan Anderson <brendan_anderson@hcpss.org>
 * @package     Tasc
 * @copyright   2015 Howard County Public Schools
 */
class ProviderManager
{    
    /** @var array */
    private $providers;
    
    /**
     * Set a provider
     * 
     * @param string $name
     * @param ProviderInterface $provider
     */
    public function addProvider(ProviderInterface $provider, $name)
    {
        $this->providers[$name] = $provider;
        
        return $this;
    }
    
    /**
     * Get a provider
     * 
     * @param string $name
     * @return ProviderInterface
     */
    public function getProvider($name)
    {
        return $this->providers[$name];
    }
}
