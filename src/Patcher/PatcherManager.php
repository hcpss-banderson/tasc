<?php

/**
 * A class for fetching and registering Patchers
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

/**
 * A class for fetching and registering Patchers
 *
 * @author      Brendan Anderson <brendan_anderson@hcpss.org>
 * @package     Tasc
 * @copyright   2015 Howard County Public Schools
 */
class PatcherManager
{
    /** @var array */
    private $patchers;
    
    /**
     * Add a patcher
     * 
     * @param PatcherInterface $patcher
     */
    public function addPatcher(PatcherInterface $patcher, $type)
    {
        $this->patchers[$type] = $patcher;
    }
    
    /**
     * Get a patcher by type
     * 
     * @param string $type
     * @return PatcherInterface
     */
    public function getPatcher($type)
    {
        return $this->patchers[$type];
    }
}
