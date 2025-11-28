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
        global $container;

        $this->showTitle();
        
        $name = $input->getArgument('name');
        if (empty($name) == true) {
            $this->showError('Not valid queue worker driver name.');
            return;
        }

        $driver = $container->get('driver')->create($name);
        if ($driver == null) {
            $this->showError('Not valid queue worker driver name.');
            return;
        }

        $this->table()->setHeaders(['Name','Value']);
        $this->table()->setStyle('compact');

        foreach ($driver->getDetails() as $key => $value) {

            if (is_array($value) == true) {
                continue;
            }
          
            $this->table()->addRow([$key, $value]);
        }

        $this->table()->render();
        $this->newLine();

        $this->showCompleted();       
    }
}
