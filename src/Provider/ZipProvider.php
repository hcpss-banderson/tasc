<?php

/**
 * A provider for fetching and placeing projects from remote zip files.
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

use Buzz\Message\Response;
use Buzz\Client\Curl;
use Buzz\Browser;

/**
 * A provider for fetching and placeing projects from remote zip files.
 *
 * @author      Brendan Anderson <brendan_anderson@hcpss.org>
 * @package     Tasc
 * @copyright   2015 Howard County Public Schools
 */
class ZipProvider extends ProviderBase
{
    /** @var \Buzz\Browser */
    private $browser;
    
    /** @var string */
    private $source;
    
    /** @var string */
    private $destination;
    
    /** @var string */
    private $rename;
    
    public function __construct()
    {
        $curl = new Curl();
        $curl->setTimeout(60);
        
        $this->browser = new Browser($curl);
    }
    
    /**
     * Helper function to return the filename for a given source. For instance:
     * 
     * source:  "http://www.example.com/myarchive.zip"
     * returns: "myarchive.zip"
     * 
     * @param string $source
     * @return string
     */
    private function getFilenameFromSource($source)
    {
        $segments = explode('/', $source);
    
        return array_pop($segments);
    }
    
    /**
     * Set parameters
     * 
     * @param array $params
     * @throws \InvalidArgumentException
     */
    protected function resolveParams(array $params)
    {
        if (!array_key_exists('source', $params)) {
            throw new \InvalidArgumentException('No source found.');
        }
        
        if (!array_key_exists('destination', $params)) {
            // Default destination
            $params['destination'] = null;
        }
        
        if (!array_key_exists('rename', $params)) {
            $params['rename'] = $this->getFolderNameFromSource(
                $params['source']
            );
        }
        
        return $params;
    }
    
    /**
     * Get the folder name from a source
     * 
     * @param string $source
     * @return string
     */
    private function getFolderNameFromSource($source)
    {
        $filename   = $this->getFilenameFromSource($source);
        $nameParts  = explode('.', $filename);
        $extension  = array_pop($nameParts);
        
        return implode('.', $nameParts);
    }
    
    /**
     * {@inheritDoc}
     * 
     * @see \HcpssBanderson\Provider\ProviderInterface::assemble()
     */
    public function assemble(array $params) 
    {
        $params = $this->resolveParams($params);
        
        $filename = $this->getFilenameFromSource($params['source']);
        
        /** @var $response \Buzz\Message\Response */
        $response = $this->browser->get($params['source']);
        
        if (!$response->isSuccessful()) {
            throw new \Exception(sprintf(
                'Could not download file from %s', 
                $params['source']
            ));
        }
        
        // Write the Zip file
        $tempFile = tempnam(sys_get_temp_dir(), $filename);        
        file_put_contents($tempFile, $response->getContent());
        
        // Open the Zip file
        $zip = new \ZipArchive();
        if ($zip->open($tempFile) !== true) {
            throw new \Exception(sprintf(
                'Could not open archive %s', 
                $tempFile
            ));
        }
        
        // Extract zip
        $zip->extractTo("{$this->projectBase}/{$params['destination']}/");
        $zip->close();
        
        // Delete zip
        unlink($tempFile);
    }
}
