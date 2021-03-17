'use strict';

arikaim.component.onLoaded(function() {

    arikaim.ui.form.addRules('#job_settings_form');
    arikaim.ui.form.onSubmit("#job_settings_form",function() {  
        return jobs.saveConfig('#job_settings_form',function(result) {     
            arikaim.page.loadContent({
                id: 'job_config_content',           
                component: 'queue::admin.jobs.config.view',
                params: { uuid: result.uuid }  
            });      
        });
    },function(result) {
        arikaim.ui.form.showMessage(result.message);
    });
});