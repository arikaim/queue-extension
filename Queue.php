<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Queue;

use Arikaim\Core\Extension\Extension;

/**
 * Queue extension class
 */
class Queue extends Extension
{
    /**
     * Install extension
     *
     * @return void
     */
    public function install()
    {
        // Control Panel
        $this->addApiRoute('PUT','/api/admin/queue/start','QueueControlPanel','start','session'); 
        $this->addApiRoute('PUT','/api/admin/queue/stop','QueueControlPanel','stop','session'); 
        $this->addApiRoute('GET','/api/admin/queue/status[/{name}]','QueueControlPanel','getStatus','session'); 
        $this->addApiRoute('PUT','/api/admin/queue/cron/run','QueueControlPanel','runCronCommand','session'); 
        // jobs
        $this->addApiRoute('PUT','/api/admin/queue/job/status','JobsControlPanel','setStatus','session'); 
        $this->addApiRoute('DELETE','/api/admin/queue/job/delete/{uuid}','JobsControlPanel','deleteJob','session'); 
        $this->addApiRoute('DELETE','/api/admin/queue/job/completed/delete','JobsControlPanel','deleteCompleted','session'); 
        $this->addApiRoute('PUT','/api/admin/queue/job/config','JobsControlPanel','saveConfig','session'); 
        $this->addApiRoute('PUT','/api/admin/queue/job/config/interval','JobsControlPanel','updateInterval','session'); 
        $this->addApiRoute('PUT','/api/admin/queue/job/run','JobsControlPanel','run','session'); 
        // Console
        $this->registerConsoleCommand('StopWorkerCommand');        
        $this->registerConsoleCommand('StartWorkerCommand');     
        $this->registerConsoleCommand('PushJobCommand');     
        // Options
        $this->createOption('queue.worker.pid',null); 
        $this->createOption('queue.worker.name','cron-queue'); 
        // Drivers
        $this->installDriver('Arikaim\\Extensions\\Queue\\Drivers\\CronQueueWorker');
    }
    
    /**
     * UnInstall extension
     *
     * @return void
     */
    public function unInstall()
    {  
    }
}
