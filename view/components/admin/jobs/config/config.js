'use strict';

arikaim.component.onLoaded(function() {
    arikaim.ui.form.addRules('#job_settings_form');
    
    arikaim.ui.form.onSubmit("#job_settings_form",function() {  
        return jobs.saveConfig('#job_settings_form',function(result) {      
        });
    },function(result) {
        arikaim.ui.form.showMessage(result.message);
    });
});