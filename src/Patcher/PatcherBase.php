<?php

/**
 * Base class for Patchers
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

abstract class PatcherBase implements PatcherInterface
{
    protected $projectBase;
    protected $configBase;
    
    public function setProjectBase($projectBase)
    {
        $this->projectBase = $projectBase;
    }
    
    public function setConfigBase($configBase)
    {
        $this->configBase = $configBase;
    }
}
