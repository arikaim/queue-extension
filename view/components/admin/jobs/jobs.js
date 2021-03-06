/**
 *  Arikaim
 *  @copyright  Copyright (c) Konstantin Atanasov <info@arikaim.com>
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

    this.delete = function(uuid, onSuccess, onError) {
        return arikaim.delete('/api/admin/queue/job/delete/' + uuid,onSuccess,onError);           
    };

    this.saveConfig = function(formId, onSuccess, onError) {
        return arikaim.put('/api/admin/queue/job/config',formId,onSuccess,onError);      
    };

    this.run = function(name, onSuccess, onError) {
        var data = { 
            name: name        
        };
        
        return arikaim.put('/api/admin/queue/job/run',data,onSuccess,onError)
    };
}

var jobs = new Jobs();
