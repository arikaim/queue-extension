/**
 *  Arikaim
 *  @copyright  Copyright (c) Konstantin Atanasov <info@arikaim.com>
 *  @license    http://www.arikaim.com/license
 *  http://www.arikaim.com
 */
'use strict';

function WorkerManager() {
    var self = this;

    this.start = function(name, onSuccess, onError) {
        var data = {
            name: name
        };

        return arikaim.put('/api/queue/admin/start',data,onSuccess,onError);          
    };

    this.stop = function(name, onSuccess, onError) {
        var data = {
            name: name
        };

        return arikaim.put('/api/queue/admin/stop',data,onSuccess,onError);          
    };

    this.status = function(onSuccess, onError) {
        return arikaim.get('/api/queue/admin/status',onSuccess, onError);          
    };

    this.loadWorkerStatus = function(name) {
        arikaim.page.loadContent({
            id: 'worker_status_content',
            component: 'queue::admin.worker.status',
            params: { worker_name: name }
        },function(result) {
            self.init();
        });
    };

    this.init = function() {     
        arikaim.ui.button('#start_worker',function(element) {
            var name = $(element).attr('worker-name');

            return self.start(name,function(result) {
                self.loadWorkerStatus(result.name);
            });
        });

        arikaim.ui.button('#stop_worker',function(element) {
            var name = $(element).attr('worker-name');

            return self.stop(name,function(result) {
                self.loadWorkerStatus(result.name);
            });
        });

        arikaim.ui.button('#run-worker-test',function(element) {
            $('#cron_run_result').html('');

            return self.start(function(result) {
                $('#cron_run_result').html(result.output);
            },function(error) {
                $('#cron_run_result').html(error);
            });
        });
    };
}

var workerManager = new WorkerManager();

arikaim.component.onLoaded(function() {
    workerManager.init();   
});