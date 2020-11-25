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
 * Queue class
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
       // $this->addApiRoute('PUT','/api/blog/admin/post/add','PostControlPanel','add','session');   
        $this->addApiRoute('PUT','/api/queue/admin/job/update','QueueControlPanel','update','session'); 
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
