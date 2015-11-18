<?php

/**
 * Patch project from a patch file
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

namespace HcpssBanderson\Patcher;

use AdamBrett\ShellWrapper\Runners\ShellExec;
use AdamBrett\ShellWrapper\Command\Builder as CommandBuilder;

/**
 * Patch project from a patch file
 *
 * @author      Brendan Anderson <brendan_anderson@hcpss.org>
 * @package     Tasc
 * @copyright   2015 Howard County Public Schools
 */
class PatchPatcher extends PatcherBase
{
    /**
     * {@inheritDoc}
     * 
     * @see \HcpssBanderson\Patcher\PatcherInterface::patch()
     */
    public function patch(array $params)
    {
        list($source, $destination) = $this->resolveParams($params);
        $ds = DIRECTORY_SEPARATOR;
        
        $shell = new ShellExec();
        
        $patchCommand = new CommandBuilder('patch');
        $patchCommand
            ->addSubCommand($this->projectBase . $ds . $destination)
            ->addSubCommand($this->configBase . $ds . $source);
            
        return $shell->run($patchCommand);
    }
    
    /**
     * Resolve the prameters
     * 
     * @param array $params
     * @throws \InvalidArgumentException
     * @return array
     */
    protected function resolveParams(array $params)
    {
        if (!array_key_exists('source', $params)) {
            throw new \InvalidArgumentException('No src found.');
        }
        
        if (!array_key_exists('destination', $params)) {
            throw new \InvalidArgumentException('No dest found.');
        }
        
        return [$params['source'], $params['destination']];
    }
}
