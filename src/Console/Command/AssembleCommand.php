<?php

/**
 * Command for assembling projects
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

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

/**
 * A command to assemble a project
 * 
 * @author      Brendan Anderson <brendan_anderson@hcpss.org>
 * @package     Tasc
 * @copyright   2015 Howard County Public Schools
 * @see         Symfony\Component\Console\Command\Command
 */
class AssembleCommand extends Command
{
    use ContainerAwareTrait;
    
    /**
     * {@inheritDoc}
     * 
     * @see \Symfony\Component\Console\Command\Command::configure()
     */
    protected function configure()
    {
        $this
            ->setName('tasc:assemble')
            ->setDescription('Assemble some source code.')
            ->addOption(
                'manifest', 
                'm', 
                InputOption::VALUE_OPTIONAL, 
                'The location of the manifest file.', 
                './manifest.yml'
            )
            ->addOption(
                'destination',
                'd',
                InputOption::VALUE_OPTIONAL,
                'Where to assemble the code.',
                './'
            )
            ->addOption(
                'extra-parameters',
                'p',
                InputOption::VALUE_OPTIONAL,
                'A JSON encoded string with extra parameters.'
            )
        ;
            
        $usage = '--manifest=/var/www/manifest.yml ';
        $usage .= "--extra-parameters='{\"github.access_token\": \"MyToken\"}'";
            
        $this->addUsage($usage);
    }
    
    /**
     * {@inheritDoc}
     * 
     * @see \Symfony\Component\Console\Command\Command::execute()
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //Process extra params passed through the console
        $extraParamsJson    = $input->getOption('extra-parameters');
        if ($extraParamsJson) {
            echo "Extra parameters $extraParamsJson\n";
            $extraParams = json_decode($extraParamsJson);
            
            foreach ($extraParams as $param => $value) {
                echo "Setting param $param to $value\n";
                $this->container->setParameter($param, $value);
            }
        }
        
        // Get the manifest parameter
        $manifestPath   = realpath($input->getOption('manifest'));
        $rawManifest    = Yaml::parse($manifestPath);
        $configBase     = dirname($manifestPath);
        
        // The manifest might have replacement values in it. To compile them
        // they have to be passer through the container.
        $this->container->setParameter('manifest', $rawManifest);
        $this->container->compile();
        
        // Now we can get manifest with replacement values replaced
        $manifest = $this->container->getParameter('manifest');
        print_r($manifest);
        $destination = realpath($input->getOption('destination'));
        
        // Assemble the code
        foreach ($manifest['projects'] as $project) {
            /** @var $provider HcpssBanderson\ProviderInterface */
            $provider = $this->container
                ->get('provider_manager')
                ->getProvider($project['provider']);
                
            $provider->setProjectBase($destination);
            $provider->setConfigBase($configBase);
            $provider->assemble($project);
        }
        
        // patch the code
        if (!empty($manifest['patches'])) {
            foreach ($manifest['patches'] as $patch) {
                $patcher = $this->container
                    ->get('patcher_manager')
                    ->getPatcher($patch['type']);
                
                $patcher->setProjectBase($destination);
                $patcher->setConfigBase($configBase);
                
                $patcher->patch($patch);
            }
        }
    }
}
