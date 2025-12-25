'use strict';

function Jobs() {

    this.enable = function(uuid, onSuccess, onError) {   
        return arikaim.put('/api/admin/queue/job/status',{ 
            uuid: uuid,
            status: 1 
        },onSuccess,onError)
    };

    this.disable = function(uuid, onSuccess, onError) {
        return arikaim.put('/api/admin/queue/job/status',{ 
            uuid: uuid,
            status: 5 // Suspended 
        },onSuccess,onError);           
    };

    this.deleteCompleted = function(onSuccess, onError) {
        return arikaim.delete('/api/admin/queue/job/completed/delete',onSuccess,onError);           
    };

    this.delete = function(uuid, onSuccess, onError) {
        return arikaim.delete('/api/admin/queue/job/delete/' + uuid,onSuccess,onError);           
    };

    this.saveConfig = function(formId, onSuccess, onError) {
        return arikaim.put('/api/admin/queue/job/config',formId,onSuccess,onError);      
    };

    this.updateInterval = function(uuid, interval, onSuccess, onError) {
        return arikaim.put('/api/admin/queue/job/config/interval',{
            uuid: uuid,
            interval: interval
        },onSuccess,onError);      
    };

    this.run = function(formId, onSuccess, onError) {
        return arikaim.put('/api/admin/queue/job/run',formId,onSuccess,onError)
    };

    this.push = function(formId, onSuccess, onError) {
        return arikaim.put('/api/admin/queue/job/push',formId,onSuccess,onError)
    };
}

var jobs = new Jobs();

arikaim.component.onLoaded(function() {
    $('#extensions_dropdown').on('change', function() {
        var name = $(this).val();
        
        arikaim.page.loadContent({
            id: 'jobs_view_content',
            component: "queue::admin.jobs.view",
            params: { package_name : name }               
        });           
    });
});