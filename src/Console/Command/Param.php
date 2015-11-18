<?php

/**
 * A parameter for shell commands
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

namespace HcpssBanderson\Console\Command;

use AdamBrett\ShellWrapper\Command\Param as ParamBase;

/**
 * A parameter for shell commands
 *
 * @author      Brendan Anderson <brendan_anderson@hcpss.org>
 * @package     Tasc
 * @copyright   2015 Howard County Public Schools
 * @see         AdamBrett\ShellWrapper\Command\Param
 */
class Param extends ParamBase
{
    protected $escape;
    
    public function __construct($param, $escape = true) 
    {
        parent::__construct($param);
        $this->escape = $escape;
    }
    
    public function __toString()
    {
        return $this->escape ? parent::__toString() : $this->param;
    }
}
