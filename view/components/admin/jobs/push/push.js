'use strict';

arikaim.component.onLoaded(function() {
    arikaim.ui.form.addRules('#push_job_form',{});

    $('.checkbox').checkbox();
    $('.interval-dropdown').dropdown({});
    
    arikaim.ui.form.onSubmit("#push_job_form",function() {  
        return jobs.push('#push_job_form');
    },function(result) {
        arikaim.page.loadContent({
            id: 'job_registry_details',           
            component: 'queue::admin.jobs.queue',
            params: {
                uuid: result.uuid
            }                  
        });   
    });
});