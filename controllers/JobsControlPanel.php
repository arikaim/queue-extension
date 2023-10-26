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
use Arikaim\Core\Interfaces\Job\JobInterface;

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
        $data
            ->validate(true);    

        $uuid = $data->get('uuid');  
        $status = $data->get('status',1);

        $result = $this->get('queue')->getStorageDriver()->setJobStatus($uuid,$status);
        $this->setResponse($result,function() use($status,$uuid) {                  
            $this
                ->message('jobs.status')
                ->field('status',$status)
                ->field('uuid',$uuid);   
        },'errors.jobs.status');
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
        $data
            ->validate(true); 
                  
        $jobId = $data->get('job');           
        $data->offsetUnset('job');

        $job = $this->get('queue')->jobsRegistry()->findJob($jobId);
        if ($job == null) {
            $this->error('Not valid job id');
            return false;
        }

        $jobInstance = $this->get('queue')->create($job->name);
        // check access
        if ($jobInstance->descriptor()->getValue('allow.admin.config') == false) {
            $this->error('Not allowed edit job config form admin panel.');
            return false;
        }

        $result = $job->saveOptions($data->toArray());
        
        $this->setResponse($result,function() use($job) {                  
            $this
                ->message('jobs.config')                  
                ->field('uuid',$job->uuid);   
        },'errors.jobs.config');          
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
    public function run($request, $response, $data)
    {
        $data
            ->validate(true);  
      
        $jobId = $data->get('job');           

        $job = $this->get('queue')->run($jobId);
        if ($job == null) {
            $this->error('Error job execution.');
            return;
        }
       
        $this->setResponse($job->hasSuccess(),function() use($job) {                  
            $this
                ->message('jobs.run')                  
                ->field('uuid',$job->getId());   
        },'errors.jobs.run');          
    }

    /**
     * Push job in queue
     *
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     * @param Validator $data
     * @return Psr\Http\Message\ResponseInterface
    */
    public function push($request, $response, $data)
    {
        $data
            ->validate(true);  
      
        $uuid = $data->get('uuid');           
        $type = $data->get('type');             
        $interval = null;
        $scheduleTime = null;

        if ($type == 'recurring') {
            $interval = $data->get('interval',null);  
        }

        if ($type == 'scheduled') {
            $scheduleTime = $data->get('schedule_time',null);    
        }
      
        $model = $this->get('queue')->jobsRegistry()->findJob($uuid);
        if ($model == null) {
            $this->error('Error job not found.');
            return;
        }
       
        $job = $this->get('queue')->create($model->name);
        if ($job == null) {
            $this->error('job not found.');
            return;
        }

        // set props values
        $job->descriptor()->collection('parameters')->setPropertyValues($data->toArray());
        $params = $job->descriptor()->collection('parameters')->getValues();

        $result = $this->get('queue')->push($model->name,$params,$model->package_name,$interval,$scheduleTime);

        $this->setResponse($result,function() use($uuid) {                  
            $this
                ->message('jobs.push')                  
                ->field('uuid',$uuid);   
        },'errors.jobs.push');          
    }
}
