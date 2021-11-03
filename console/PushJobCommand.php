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
 * Push job to queue 
 */
class PushJobCommand extends ConsoleCommand
{  
    /**
     * Configure command
     *
     * @return void
     */
    protected function configure()
    {
        $this->setName('job:push');
        $this->setDescription('Push job to queue');    
        $this->addOptionalArgument('name','Job Name');   
        $this->addOptionalArgument('extension','Extension Name');     
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
            $this->showError('Job name required!');
            return;
        }
        $extension = $input->getArgument('extension');

        $this->writeFieldLn('Name',$name);
        $this->writeFieldLn('Extension',$extension);
        
        $result = Arikaim::queue()->push($name,$extension,[],true);

        if ($result === false) {
            $this->showError('Error');
            return;
        }
      
        $this->showCompleted();       
    }
}
