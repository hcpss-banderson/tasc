<?php

/**
 * Our application
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

namespace HcpssBanderson\Tool;

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\InputInterface;
use HcpssBanderson\Console\Command\AssembleCommand;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Our application class
 *
 * @author      Brendan Anderson <brendan_anderson@hcpss.org>
 * @package     Tasc
 * @copyright   2015 Howard County Public Schools
 */
class TascApplication extends Application
{
    use ContainerAwareTrait;
    
    public function __construct($name, $version, ContainerBuilder $container)
    {
        $this->setContainer($container);
        
        parent::__construct($name, $version);
    }
    
    /**
     * {@inheritDoc}
     * 
     * @see \Symfony\Component\Console\Application::getCommandName()
     */
    protected function getCommandName(InputInterface $input)
    {
        return 'tasc:assemble';
    }
    
    /**
     * {@inheritDoc}
     * 
     * @see \Symfony\Component\Console\Application::getDefaultCommands()
     */
    protected function getDefaultCommands()
    {   
        $assemble = new AssembleCommand();
        $assemble->setContainer($this->container);        
        
    	$defaultCommands = parent::getDefaultCommands();
    	$defaultCommands[] = $assemble;
    	
    	return $defaultCommands;
    }
    
    /**
     * {@inheritDoc}
     * 
     * @see \Symfony\Component\Console\Application::getDefinition()
     */
    public function getDefinition()
    {
    	$inputDefinition = parent::getDefinition();
    	
    	// Clear out the normal first argument, which is the command name
    	$inputDefinition->setArguments();
    	
    	return $inputDefinition;
    }
}
