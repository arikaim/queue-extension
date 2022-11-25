<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Queue\Controllers;

use Arikaim\Core\Controllers\ControlPanelApiController;
use Arikaim\Core\Queue\Cron;

/**
 * Category control panel controler
*/
class QueueControlPanel extends ControlPanelApiController
{
    /**
     * Init controller
     *
     * @return void
     */
    public function init()
    {
        $this->loadMessages('queue::admin.messages');
    }

    /**
     * Get queue worker status
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function getStatusController($request, $response, $data) 
    {         
        $data->validate(true);    

        $defaultWorker = $this->get('options')->get('queue.worker.name','');
        $name = $data->getSring('name',$defaultWorker);
        $driver = $this->get('driver')->create($name);
        
        $running = (\is_object($driver) == true) ? $driver->isRunning() : false;

        $this->setResponse(\is_object($driver),function() use($running,$name) {                                
            $this
                ->message('worker.status')
                ->field('name',$name)
                ->field('running',$running);                                                                                        
        },'errors.worker.status');
    }

    /**
     * Start queue worker
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function startController($request, $response, $data) 
    {         
        $data->validate(true);    

        $defaultWorker = $this->get('options')->get('queue.worker.name','');
        $name = $data->getString('name',$defaultWorker);
        
        $driver = $this->get('driver')->create($name);
        
        if (\is_object($driver) == false) {
            $this->error('Not valid queue driver name');
            return false;
        }

        $result = $driver->run();
      
        $this->setResponse($result,function() use($name) {                                
            $this
                ->message('worker.start')
                ->field('name',$name);                                                                                        
        },'errors.worker.start');
    }

    /**
     * Stop worker
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function stopController($request, $response, $data) 
    {         
        $data->validate(true);        

        $defaultWorker = $this->get('options')->get('queue.worker.name','');
        $name = $data->getString('name',$defaultWorker);
        
        $driver = $this->get('driver')->create($name);
        
        if (\is_object($driver) == false) {
            $this->error('Not valid queue driver name.');
            return false;
        }
    
        $result = $driver->stop();
        var_dump($result);
        exit();
        
        
        $this->setResponse($result,function() use($name) {                                
            $this
                ->message('worker.stop')
                ->field('name',$name);        ;                                                                                        
        },'errors.worker.stop');
    }

    /**
     * Run cron command
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
     */
    public function runCronCommandController($request, $response, $data)
    {         
        $output = Cron::runCronCommand();

        $this->setResponse(true,function() use($output) {                  
            $this
                ->message('cron.run')                   
                ->field('output',$output);   
        },'errors.cron.run');                   
    }
}
