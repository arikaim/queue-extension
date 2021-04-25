<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
 */
namespace Arikaim\Extensions\Queue\Console;

use Arikaim\Core\Console\ConsoleCommand;
use Arikaim\Core\Arikaim;

/**
 * Stop queue worker 
 */
class StopWorkerCommand extends ConsoleCommand
{  
    /**
     * Configure command
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('worker:stop');
        $this->setDescription('Stop queue worker');        
    }

    /**
     * Run command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function executeCommand($input,$output)
    {
        $this->showTitle();

        $driver = Arikaim::get('driver')->create('reactphp-queue');
        if (\is_object($driver) == false) {
            $this->showError('React php queue dievr not installed.');
            return;
        }

        $this->writeLn('Host ' . $driver->getHost() . ':' . $driver->getPort());
       
        // stop server
        $driver->stop();

        $status = ($driver->isRunning() == true) ? 'running' : 'stop';
        $this->writeLn('Status ' . $status);

        $this->showCompleted();       
    }
}
