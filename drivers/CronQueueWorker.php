<?php
/**
 * Arikaim
 *
 * @link        http://www.arikaim.com
 * @copyright   Copyright (c)  Konstantin Atanasov <info@arikaim.com>
 * @license     http://www.arikaim.com/license
 * 
*/
namespace Arikaim\Extensions\Queue\Drivers;

use Arikaim\Core\Queue\Cron;
use Arikaim\Core\System\Process;
use Arikaim\Core\Driver\Traits\Driver;
use Arikaim\Core\Interfaces\Driver\DriverInterface;
use Arikaim\Core\Interfaces\WorkerManagerInterface;

/**
 *  Cron Queue worker driver
 */
class CronQueueWorker implements DriverInterface, WorkerManagerInterface
{   
    use Driver;
   
    /**
     * Cron insance
     *
     * @var object
     */
    protected $cron;

    /**
     * Crontab interval
     *
     * @var string
     */
    protected $interval = '5';

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->setDriverParams(
            'cron-queue',
            'queue.worker',
            'Cron Queue Worker',
            'Crontab queue worker driver.'
        );        
    }

    /**
     * Run worker
     *    
     * @return boolean
     */
    public function run(): bool
    {
        return $this->cron->run();
    }

    /**
     * Get host
     *
     * @return string
     */
    public function getHost(): string 
    {
        return '';
    }

    /**
     * Get port
     *
     * @return string
     */
    public function getPort(): string 
    {
        return '';
    }

    /**
     * Get url 
     *
     * @return string
     */
    protected function getUrl(): string
    {
        return '';
    }

    /**
     * Get worker process command
     *
     * @return string
     */
    public function getProccessCommand(): string
    {
        $php = (Process::findPhp() === false) ? 'php' : Process::findPhp();   

        return $php . ' ' . ROOT_PATH . BASE_PATH . '/cli scheduler >> /dev/null 2>&1';
    }

    /**
     * Return true if worker is running
     *    
     * @return boolean
     */
    public function isRunning(): bool
    {
        return $this->cron->isRunning();
    }

    /**
     * Stop worker
     *    
     * @return boolean
     */
    public function stop(): bool
    {
        return $this->cron->stop();
    }

    /**
     * Get title
     *    
     * @return string
     */
    public function getTitle(): string
    {
        return 'Cron Worker';
    }

    /**
     * Get description
     *    
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return 'Crontab queue worker.';
    }

    /**
     * Get worker service details
     *
     * @return array
     */
    public function getDetails(): array
    {
        return $this->cron->getDetails();
    }

    /**
     * Init driver
     *
     * @param Properties $properties
     * @return void
     */
    public function initDriver($properties)
    {     
        $config = $properties->getValues();      
        $this->interval = $config['interval'] ?? '5';

        $this->cron = new Cron($this->interval);
    }

    /**
     * Create driver config properties array
     *
     * @param Arikaim\Core\Collection\Properties $properties
     * @return void
     */
    public function createDriverConfig($properties)
    {
        $properties->property('interval',function($property) {
            $property
                ->title('Crontab Interval')
                ->type('list')
                ->default('5')
                ->value('5')
                ->items([1,5])
                ->readonly(false);              
        });                      
    }
}
