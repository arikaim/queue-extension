'use strict';

arikaim.component.onLoaded(function() {

    arikaim.ui.button('.edit-job-config',function(element) {
        var uuid = $(element).attr('uuid');

        arikaim.page.loadContent({
            id: 'job_config_content',           
            component: 'queue::admin.jobs.config',
            params: { uuid: uuid }            
        },function(result) {               
        });  
    });

});