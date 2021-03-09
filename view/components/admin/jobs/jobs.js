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
        
        return arikaim.put('/api/queue/admin/job/status',data,onSuccess,onError)
    };

    this.disable = function(uuid, onSuccess, onError) {
        var data = { 
            uuid: uuid,
            status: 5 // Suspended 
        };

        return arikaim.put('/api/queue/admin/job/status',data,onSuccess,onError);           
    };

    this.delete = function(uuid, onSuccess, onError) {
        return arikaim.delete('/api/queue/admin/job/delete/' + uuid,onSuccess,onError);           
    };

    this.saveConfig = function(formId, onSuccess, onError) {
        return arikaim.put('/api/queue/admin/job/config',formId,onSuccess,onError);      
    }
}

var jobs = new Jobs();
