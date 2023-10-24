/**
 *  Arikaim
 *  @copyright  Copyright (c)  <info@arikaim.com>
 *  @license    http://www.arikaim.com/license
 *  http://www.arikaim.com
 */
'use strict';

function Jobs() {

    this.enable = function(uuid, onSuccess, onError) {
        var data = { 
            uuid: uuid,
            status: 1 
        };
        
        return arikaim.put('/api/admin/queue/job/status',data,onSuccess,onError)
    };

    this.disable = function(uuid, onSuccess, onError) {
        var data = { 
            uuid: uuid,
            status: 5 // Suspended 
        };

        return arikaim.put('/api/admin/queue/job/status',data,onSuccess,onError);           
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
        var data = {
            uuid: uuid,
            interval: interval
        };

        return arikaim.put('/api/admin/queue/job/config/interval',data,onSuccess,onError);      
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
    arikaim.ui.tab('.tab-item','tab_content',['worker']);   
});