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
        $this->addApiRoute('PUT','/api/queue/admin/start','QueueControlPanel','start','session'); 
        $this->addApiRoute('PUT','/api/queue/admin/stop','QueueControlPanel','stop','session'); 
        $this->addApiRoute('GET','/api/queue/admin/status[/{name}]','QueueControlPanel','getStatus','session'); 
        // jobs
        $this->addApiRoute('PUT','/api/queue/admin/job/status','JobsControlPanel','setStatus','session'); 
        $this->addApiRoute('DELETE','/api/queue/admin/job/delete/{uuid}','JobsControlPanel','deleteJob','session'); 
        $this->addApiRoute('POST','/api/queue/admin/job/config','JobsControlPanel','saveConfig','session'); 
 
        // Options
        $this->createOption('queue.worker.pid',null); 
        $this->createOption('queue.worker.name',null); 
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
