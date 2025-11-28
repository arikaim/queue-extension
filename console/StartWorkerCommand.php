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
 
        // start queue worker
        $driver->run();

        $status = ($driver->isRunning() == true) ? 'running' : 'stop';
        $this->writeLn('Status ' . $status);

        $this->showCompleted();       
    }
}
