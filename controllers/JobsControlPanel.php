<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace  Arikaim\Extensions\Queue\Controllers;

use Arikaim\Core\Controllers\ControlPanelApiController;
use Arikaim\Core\Collection\PropertiesFactory;
use Arikaim\Core\Interfaces\Job\JobInterface;
use Arikaim\Core\Interfaces\Job\JobLogInterface;

/**
 * Jobs control panel controller
*/
class JobsControlPanel extends ControlPanelApiController
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
     * Delete completed jobs
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
     */
    public function deleteCompletedController($request, $response, $data)
    {
        $this->onDataValid(function($data) {                 
            $result = $this->get('queue')->deleteJobs(['status' => JobInterface::STATUS_COMPLETED]);
           
            $this->setResponse($result,function() {                  
                $this
                    ->message('jobs.completed');   
            },'errors.jobs.completed');
        });
        $data->validate();      
    }

    /**
     * Delete job
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
     */
    public function deleteJobController($request, $response, $data)
    {
        $this->onDataValid(function($data) {       
            $uuid = $data->get('uuid');

            $result = $this->get('queue')->deleteJob($uuid);
           
            $this->setResponse($result,function() use($uuid) {                  
                $this
                    ->message('jobs.delete')                   
                    ->field('uuid',$uuid);   
            },'errors.jobs.delete');
        });
        $data->validate();      
    }
    
    /**
     * Enable/Disable job
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
     */
    public function setStatusController($request, $response, $data)
    {
        $this->onDataValid(function($data) {   
            $uuid = $data->get('uuid');  
            $status = $data->get('status',1);

            $result = $this->get('queue')->getStorageDriver()->setJobStatus($uuid,$status);
            $this->setResponse($result,function() use($status,$uuid) {                  
                $this
                    ->message('jobs.status')
                    ->field('status',$status)
                    ->field('uuid',$uuid);   
            },'errors.jobs.status');
        });
        $data->validate();          
    }

    /**
     * Save job config
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function saveConfigController($request, $response, $data)
    {
        $this->onDataValid(function($data) {            
            $jobName = $data->get('name');           
            $data->offsetUnset('name');

            $job = $this->get('queue')->getJob($jobName);
            // change config valus
            $config = PropertiesFactory::createFromArray($job['config']); 
            $config->setPropertyValues($data->toArray());

            $result = $this->get('queue')->saveJobConfig($jobName,$config->toArray());
            
            $this->setResponse($result,function() use($job) {                  
                $this
                    ->message('jobs.config')                  
                    ->field('uuid',$job['uuid']);   
            },'errors.jobs.config');          
        });
        $data->validate();       
    }

    /**
     * Update job recurring interval
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function updateIntervalController($request, $response, $data)
    {
        $this->onDataValid(function($data) {            
            $uuid = $data->get('uuid');           
            $interval = $data->get('interval'); 

            $job = $this->get('queue')->getStorageDriver()->findById($uuid);
            if (\is_object($job) == false) {
                $this->error('Not valid job Id.');
                return false;
            }       
            $result = $job->update([
                'recuring_interval' => $interval
            ]);   

            $this->setResponse(($result !== false),function() use($job) {                  
                $this
                    ->message('jobs.interval')  
                    ->field('interval',$job['recuring_interval'])                
                    ->field('uuid',$job['uuid']);   
            },'errors.jobs.interval');          
        });
        $data->validate();       
    }

    /**
     * Run job 
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function runController($request, $response, $data)
    {
        $this->onDataValid(function($data) {            
            $jobName = $data->get('name');           

            $job = $this->get('queue')->execute($jobName);
            if ($job instanceof JobLogInterface) {
                $this->get('logger')->info($job->getLogMessage(),$job->getLogContext());
            }

            $this->setResponse(\is_object($job),function() use($job) {                  
                $this
                    ->message('jobs.run')                  
                    ->field('uuid',$job->getId());   
            },'errors.jobs.run');          
        });
        $data->validate();  
    }
}
