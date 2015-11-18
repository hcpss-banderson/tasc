<?php

/**
 * A provider for fetching and placeing projects from git repositories.
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

use AdamBrett\ShellWrapper\Command\Builder as CommandBuilder;
use AdamBrett\ShellWrapper\Runners\ShellExec;
use HcpssBanderson\Console\Command\Param;
use AdamBrett\ShellWrapper\Command;

/**
 * A provider for fetching and placeing projects from git repositories.
 * 
 * @author      Brendan Anderson <brendan_anderson@hcpss.org>
 * @package     Tasc
 * @copyright   2015 Howard County Public Schools
 */
class GitProvider extends ProviderBase
{
    /**
     * Get the repo name from a full repo URI
     * 
     * @param string $repo
     * @return string
     */
    protected function getRepoName($repo)
    {
        $parts      = explode('/', $repo);
        $filename   = array_pop($parts);
        $nameParts  = explode('.', $filename);
        
        return array_shift($nameParts);
    }
    
    /**
     * Resolve the parameters
     * 
     * @param array $params
     */
    private function resolveParams(array $params)
    {
        if (!array_key_exists('branch', $params)) {
            $params['branch'] = 'master';
        }
        
        if (array_key_exists('tag', $params)) {
            // If there is a tag, use it instead of the branch.
            $params['branch'] = 'tags/' . $params['tag'];
        }
        
        if (!array_key_exists('destination', $params)) {
            $params['destination'] = null;
        }
        
        return $params;
    }
    
    /**
     * {@inheritDoc}
     * 
     * @see \HcpssBanderson\Provider\ProviderInterface::assemble()
     */
    public function assemble(array $params)
    {   
        $params = $this->resolveParams($params);
        $ds = DIRECTORY_SEPARATOR;

        $tempLocation = vsprintf('%s%s%s', [
            sys_get_temp_dir(),
            DIRECTORY_SEPARATOR,
            uniqid($this->getRepoName($params['source'])),
        ]);
        
        $shell = new ShellExec();
        
        // Clone the repository
        $cloneCommand = new CommandBuilder('git');
        $cloneCommand
            ->addParam('clone')
            ->addParam($params['source'])
            ->addParam($tempLocation);
        $shell->run($cloneCommand);
        
        // Checkout the branch
        $checkoutCommand = new CommandBuilder('git');
        $checkoutCommand
            ->addFlag('C', $tempLocation)
            ->addParam('checkout')
            ->addParam($params['branch']);
        $shell->run($checkoutCommand);
        
        // Delete the .git directory
        $ungitCommand = new CommandBuilder('rm');
        $ungitCommand
            ->addFlag('rf')
            ->addParam($tempLocation . $ds . '.git');
        $shell->run($ungitCommand);
        
        // Create the install location
        $destination = $this->projectBase;
        
        if ($params['destination']) {
            $destination .= $ds . $params['destination'];
        }
        
        if (!empty($params['rename'])) {
            $destination .= $ds . $params['rename'];
        }
        
        if (!file_exists($destination)) {
            $makeDestCmd = new CommandBuilder('mkdir');
            $makeDestCmd
                ->addFlag('p')
                ->addParam($destination);
            
            $shell->run($makeDestCmd);
        }
        
        // Move to the install location
        $moveCommand = new Command('mv');
        $moveCommand->addParam(
            new Param($tempLocation . $ds . '*', false)
        );
        $moveCommand->addParam(new Param($destination, false));
        
        // Hidden files have to be specially invited
        $moveHiddenCommand = new Command('mv');
        $moveHiddenCommand->addParam(
            new Param($tempLocation . $ds . '.[!.]*', false)
        );
        $moveHiddenCommand->addParam(new Param($destination, false));
        
        $shell->run($moveCommand);
        $shell->run($moveHiddenCommand);
        
        // Remove the old git folder
        $cleanTempCommand = new CommandBuilder('rm');
        $cleanTempCommand->addFlag('rf')->addParam($tempLocation);
        
        $shell->run($cleanTempCommand);
    }
}
