'use strict';

arikaim.component.onLoaded(function() {
    $('#job_status_toggle').checkbox({
        onChecked: function(value) {         
            var uuid = $(this).attr('uuid');           
            jobs.enable(uuid,function(result) {                     
            });
        },
        onUnchecked: function(value) {    
            var uuid = $(this).attr('uuid');
            jobs.disable(uuid,function(result) {               
            });
        }
    });
});