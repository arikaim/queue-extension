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
        $this->addOptionalArgument('name','Worker driver name');        
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
        global $container;

        $this->showTitle();

        $driverName = $input->getArgument('name');
        if (empty($driverName) == true) {
            $this->showError('Worker driver name required!');
            return;
        }

        $driver = $container->get('driver')->create($driverName);
        if ($driver == null) {
            $this->showError($driverName . ' driver not installed.');
            return;
        }
       
        // stop server
        $driver->stop();

        $status = ($driver->isRunning() == true) ? 'running' : 'stop';
        $this->writeLn('Status ' . $status);

        $this->showCompleted();       
    }
}
