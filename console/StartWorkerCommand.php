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
 * Start queue worker 
 */
class StartWorkerCommand extends ConsoleCommand
{  
    /**
     * Configure command
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('worker:start');
        $this->setDescription('Start queue worker');        
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
        if ($driver == null) {
            $this->showError('React php queue dievr not installed.');
            return;
        }

        $this->writeLn('Host ' . $driver->getHost() . ':' . $driver->getPort());
       
        // start server
        $driver->run();

        $status = ($driver->isRunning() == true) ? 'running' : 'stop';
        $this->writeLn('Status ' . $status);

        $this->showCompleted();       
    }
}
