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

        return arikaim.put('/api/admin/queue/start',data,onSuccess,onError);          
    };

    this.stop = function(name, onSuccess, onError) {
        var data = {
            name: name
        };

        return arikaim.put('/api/admin/queue/stop',data,onSuccess,onError);          
    };

    this.runCronCommand = function(onSuccess, onError) {
        return arikaim.put('/api/admin/queue/cron/run',{},onSuccess,onError);          
    };

    this.status = function(onSuccess, onError) {
        return arikaim.get('/api/admin/queue/status',onSuccess, onError);          
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
        $('#drivers_dropdown').dropdown({
            onChange: function(value) {          
                self.loadWorkerStatus(value);                       
            }
        });
        
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

        arikaim.ui.button('#run_cron_command',function(element) {
            $('#worker_test_result').html('');

            return self.runCronCommand(function(result) {
                $('#worker_test_result').html(result.output);
            },function(error) {
                $('#worker_test_result').html(error);
            });
        });
    };
}

var workerManager = new WorkerManager();

arikaim.component.onLoaded(function() {
    workerManager.init();   
});