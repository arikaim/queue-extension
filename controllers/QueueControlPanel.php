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
        $this->onDataValid(function($data) {
            $name = $data->getSring('name','cron');
            $manager = $this->get('queue')->createWorkerManager($name);

            $running = $manager->isRunning();

            $this->setResponse(\is_object($manager),function() use($running,$name) {                                
                $this
                    ->message('worker.status')
                    ->field('name',$name)
                    ->field('running',$running);                                                                                        
            },'errors.worker.status');
        });
        $data->validate();        
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
        $this->onDataValid(function($data) {
            $name = $data->getString('name','cron');
            if (empty($name) == true || $name == 'cron') {
                $manager = $this->get('queue')->createWorkerManager($name);
            } else {
                $manager = $this->get('driver')->create($name);
            }
            if (\is_object($manager) == false) {
                $this->error('Not valid queue manager name');
                return false;
            }

            $result = $manager->run();
           
            $this->setResponse($result,function() use($name) {                                
                $this
                    ->message('worker.start')
                    ->field('name',$name);                                                                                        
            },'errors.worker.start');
        });
        $data->validate();        
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
        $this->onDataValid(function($data) {
            $name = $data->getString('name','cron');
            if (empty($name) == true || $name == 'cron') {
                $manager = $this->get('queue')->createWorkerManager($name);
            } else {
                $manager = $this->get('driver')->create($name);
            }
            if (\is_object($manager) == false) {
                $this->error('Not valid queue manager name');
                return false;
            }
        
            $result = $manager->stop();
            
            $this->setResponse($result,function() use($name) {                                
                $this
                    ->message('worker.stop')
                    ->field('name',$name);        ;                                                                                        
            },'errors.worker.stop');
        });
        $data->validate();        
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
