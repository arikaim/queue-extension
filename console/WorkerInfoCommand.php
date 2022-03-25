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
 *  Queue worker details command 
 */
class WorkerInfoCommand extends ConsoleCommand
{  
    /**
     * Configure command
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('worker:info');
        $this->setDescription('Queue worker info');   
        $this->addOptionalArgument('name','Driver name');         
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
        $name = $input->getArgument('name');
        if (empty($name) == true) {
            $this->showError('Not valid queue driver name.');
            return;
        }

        $driver = Arikaim::get('driver')->create($name);
        if (\is_object($driver) == false) {
            $this->showError('Not valid queue driver name.');
            return;
        }

        $this->writeLn('Host ' . $driver->getHost() . ':' . $driver->getPort());
        $status = ($driver->isRunning() == true) ? 'running' : 'stop';
        $this->writeLn('Status ' . $status);
        
        $details = $driver->getDetails();

        $this->writeLn('Command ' . $details['command'] ?? '');
        $this->writeLn('Interval ' . $details['interval'] ?? '');
        $this->writeLn('User ' . $details['user'] ?? '');
        $this->writeLn('Jobs ' . print_r($details['jobs'] ?? []));

        $this->showCompleted();       
    }
}
